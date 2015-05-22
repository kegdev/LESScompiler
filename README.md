
    /**
     * These source files is subject to the Academic Free License (AFL 3.0)
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

Those LESS files will be compiled to the specified css directory in the general hierarchy. By default, the folders 'less' and 'css' are used. Once compiled, those CSS files will be minified if the option is set to do so.

As in the config screenshot above, you also have the option of enabling activity, minification and error logging. All three act independent of each other and are all enabled by default.

Compatibility
------------

This extension was designed on Magento Enterprise 1.x platform and has been tested on versions ranging from 1.12 to 1.14.2. As no Enterprise-specific functionality is utilized, this should also work fine on the latest Community version.

If you have any issues, please let me know. This plugin is 'as-is', meaning no warranty of any sort is implied.


TODO List
------

The plugin only works at the default global level - it has no support currently for enabling-disabling config options on store or website levels. Be warned: if you have a large installation, running this will process everything with a LESS file within the skin/frontend directory. This is not an issue if you keep the hierarchy of your less and css files the same.