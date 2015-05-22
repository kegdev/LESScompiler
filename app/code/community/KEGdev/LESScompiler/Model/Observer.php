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

class KEGdev_LESScompiler_Model_Observer
{
    // watch the admin cache refresh action
    public function adminhtml_cache_refresh_type($observer)
    {
        // grab all types refreshed
        $request = Mage::app()->getRequest()->getPost('types');
        $isEnabled = Mage::getStoreConfig('lesscompiler/general/enable');
        // returned value contains less compiler cache
        if(in_array('lesscompiler', $request) && $isEnabled == 1) {
            // run compile helper
            Mage::helper('lesscompiler')->compile();
        }
    }
}