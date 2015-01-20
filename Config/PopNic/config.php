<?php
$globalConfigFile = CONF_PATH . '..' . DS . 'config.php';
if (is_file($globalConfigFile)) {
    $globalConfig = include($globalConfigFile);
}

$appConfigParameters = array(
        'APP_GROUP_LIST' => 'Index,Ebook,Test', // enable app group. there is no Space between group names
        //'DEFAULT_GROUP' => 'Index', // default group
        'APP_GROUP_MODE' => 0, //
        //'APP_GROUP_PATH' => 'Modules', //

        // template config
        'TMPL_PARSE_STRING' => array(
                '__PUBLIC__' => __ROOT__ ,
        ),

        /* DB setting */
        'DB_NAME'               => 'dict',          //
        'DB_PREFIX'             => '',    //

        'LOAD_EXT_FILE'=>'editFormHelper',

);
return array_merge($globalConfig, $appConfigParameters);

/* EOF */