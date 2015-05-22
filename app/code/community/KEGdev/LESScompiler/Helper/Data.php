<?php

/**
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category    KEGdev
 * @package     KEGdev_LESScompiler
 * @copyright   Copyright (c) 2015 Kevin Earl Gardner <keg@kegdev.com>
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */ 

require_once(Mage::getBaseDir('lib') . DS . 'lessphp' . DS .'lessc.inc.php');

class KEGdev_LESScompiler_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getCurrentPackage()
    {
        return Mage::getSingleton('core/design_package')->getPackageName();
    }

    public function getCurrentTheme()
    {
        return Mage::getSingleton('core/design_package')->getTheme();
    }    

    public function getLessDir()
    {
        if (Mage::getStoreConfig('lesscompiler/general/less_folder') != '') {
            return Mage::getStoreConfig('lesscompiler/general/less_folder');
        }
        else {
            return 'less';
        }
    } 

    public function getCssDir()
    {
        if (Mage::getStoreConfig('lesscompiler/general/css_folder') != '') {
            return Mage::getStoreConfig('lesscompiler/general/css_folder');
        }
        else {
            return 'css';
        }
    } 

    public function compile()
    {

        $helper = Mage::helper('lesscompiler');
        $less = new lessc;
        $lessDir = $helper->getLessDir();
        $cssDir = $helper->getCssDir();
        $minify = Mage::getStoreConfig('lesscompiler/general/minify');
        $activityLogging = Mage::getStoreConfig('lesscompiler/logging/activity');
        $minificationLogging = Mage::getStoreConfig('lesscompiler/logging/minification');
        $errorLogging = Mage::getStoreConfig('lesscompiler/logging/error');
        $cacheType = 'lesscompiler';
        $cacheKey = 'less_css';
        $cache = Mage::app()->useCache($cacheType);
        if (true === $cache) {
            $cssFileList = array();
            // Find all .less files under skin/frontend
            $path = Mage::getBaseDir('skin') . DS . 'frontend';         
            $pattern = '/^.+\.less$/i';
            $dir = new RecursiveDirectoryIterator($path);
            $iteration = new RecursiveIteratorIterator($dir);
            $files = new RegexIterator($iteration, $pattern, RegexIterator::GET_MATCH);

            foreach($files as $file) {

                $fileInfo = pathinfo("'" . implode($file) . "'");
                $filePath = $fileInfo['dirname']; // path to .less file
                $fileName = $fileInfo['filename']; // filename
                $fileNameExt = $fileInfo['basename']; // filename.less
                $fullLessPath = $filePath . DS . $fileNameExt; // Full less path with less file
                $fullLessPathTrimmed = str_replace("'", "", $fullLessPath);
                $noLessFilePath = str_replace($lessDir, '', $filePath); // remove the user-defined less dir
                $cssFilePath = $noLessFilePath . $cssDir; // add the user-defined css dir
                $cssFullFilePath = $cssFilePath . DS . $fileName . '.css'; // add filename + css ext to user-defined css dir    
                $cssFullFilePathTrimmed = str_replace("'", "", $cssFullFilePath);
                try {
                    // check the current css timestamp and store. if no file yet, use blank value
                    if (file_exists($cssFullFilePathTrimmed)) {
                       $cssTimeStamp = filemtime($cssFullFilePathTrimmed);
                    }
                    else {
                        $cssTimeStamp = '';
                    }

                    // if associated css is older than less, compile
                    $less->checkedCompile($fullLessPathTrimmed, $cssFullFilePathTrimmed);

                    // compare new css file to stored value for file. if timestamp greater, add to array to be minified
                    if (filemtime($cssFullFilePathTrimmed) >= $cssTimeStamp) {
                        $cssFileList[] = $cssFullFilePathTrimmed;
                        // using this conditional to create compile logs, which essentially uses the same logic as checkCompile
                        if ($activityLogging == 1) {
                            Mage::log('Compiled: '.$fullLessPathTrimmed.' to '.$cssFullFilePathTrimmed, null, 'LESS_Compiler.log');
                        }                          
                    }                  

                } catch (exception $e) {
                    // only log the error if error logging enabled in config
                    if ($errorLogging == 1) {
                        Mage::log('Error: '.$e->getMessage(), null, 'LESS_Compiler.log');
                    }
                }                   
            }

            if ($minify == 1) {
                // minify the css file list
                $temp = "";
                $cssFileContent = array();
                foreach ($cssFileList as $cssFile) {
                    $temp = file_get_contents($cssFile);
                    $temp = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $temp);
                    $temp = str_replace(': ', ':', $temp);
                    $temp = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $temp);
                    //ob_start("ob_gzhandler"); // disabling ob functionality due to zlib.output_compression being enabled by default for Magento (See note: http://php.net/manual/en/function.ob-gzhandler.php)
                    header("Content-type: text/css");
                    file_put_contents($cssFile, $temp);
                    //ob_end_flush();

                    // create a log entry for minification if option set in config
                    if ($minificationLogging == 1 && $activityLogging == 1) {
                        Mage::log('Minified: '.$cssFile, null, 'LESS_Compiler.log');
                    }                         
                }
            }
        } else {
            return false;
        }
    }
}