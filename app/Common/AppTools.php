<?php
class AppTools
{
    static public function autoload($dir='')
    {
        set_include_path(get_include_path() . PATH_SEPARATOR .
                     realpath(dirname(__FILE__).'/../ThinkPHP/Extend/Vendor/'));
        if($dir) {
            set_include_path(get_include_path() . PATH_SEPARATOR . realpath($dir));
        }

        //require_once('Zend/Loader/Autoloader.php');
        vendor('Zend.Loader.Autoloader', VENDOR_PATH);
        Zend_Loader_Autoloader::getInstance();
    }
}