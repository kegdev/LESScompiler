
    /**
     * These source files are subject to the Academic Free License (AFL 3.0)
     * that is bundled with this package in the file LICENSE_AFL.txt.
     * It is also available through the world-wide-web at this URL:
     * http://opensource.org/licenses/afl-3.0.php
     *
     * @category    KEGdev
     * @package     KEGdev_LESScompiler
     * @copyright   Copyright (c) 2015 Kevin Earl Gardner <keg@kegdev.com>
     * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
     *
     */ 


LESScompiler
==============

This module was created to handle all LESS files on a Magento 1.x installation and convert them to CSS using the [lessphp](http://leafo.net/lessphp/) compiler. I have also included support for logging (var/log/LESS_Compiler.log) and css minification. 

How does it work?
------------

The compiler works using a custom cache entry under System > Cache Management called 'LESS > CSS Output'.

![Screenshot of Cache Management](https://raw.github.com/kegdev/LESScompiler/master/cache.png)

When this cache entry is enabled and refreshed (either individually or with other cache entries), a list of all LESS files will be found under '/skin/frontend' using the paths defined under 'System > Config > KEGdev Addons > LESS Compiler'.

![Screenshot of System Config](https://raw.github.com/kegdev/LESScompiler/master/config.png)

The list of LESS files will be compiled to the specified css directory in the general hierarchy. By default, the folders 'less' and 'css' are used. Once compiled, those CSS files will be minified if the option is set to do so.

As in the config screenshot above, you also have the option of enabling activity, minification and error logging. All three act independent of each other and are all enabled by default.

Installation
-------------
1. Clone/download the repository. 
2. Copy the app/ and /lib folders to your Magento root directory. 
3. Log into the Magento Admin and flush layout, block and full page cache (if Enterprise). 
4. Log out and back in to activate extension configuration page.
5. Go to System > Configuration > KEGdev Addons > LESS Compiler and configure settings.
6. Go to System > Cache Management. 
	1. Enable LESS > CSS Output Cache entry. 
	2. Refresh LESS > CSS Output Cache entry. 
7. If logging is enabled in config (it is by default), check *var/log/LESS_Compiler.log* for complete compiler activity. 

Compatibility
------------

This extension was designed on Magento Enterprise 1.x platform and has been tested on versions ranging from 1.12 to 1.14.2. As no Enterprise-specific functionality is utilized, this should also work fine on the latest Community versions as well.

If you have any issues, please let me know. This plugin is 'as-is', meaning no warranty of any sort is implied.

Compiling Issues
------------
If you are having any issues compiling and the log shows parsing errors:
1. check your code where the parser indicates the issue is. (That is almost always the fix.)
2. Still happening? Check for the latest version of [lessphp](http://leafo.net/lessphp/) and make sure your */lib/lessphp* directory contains the latest version of *lessc.inc.php*. 

TODO List
------

* The plugin only works at the default global level - it has no support currently for enabling-disabling config options on store or website levels. Be warned: if you have a large installation, running this will process everything with a LESS file within the skin/frontend directory. This is not an issue if you keep the hierarchy of your less and css files the same.
* Adding a check for the latest version of [lessphp](http://leafo.net/lessphp/). 
* Add a frame within the configuration screen to show refreshable output of *var/log/LESS_Compiler.log* file if logging enabled.