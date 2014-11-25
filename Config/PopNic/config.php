<?php
return array(
        'DEFAULT_CHARSET'       => 'utf-8', //
        //'DEFAULT_THEME' => 'my_theme',

        //'APP_GROUP_LIST' => 'Index', // enable app group. there is no Space between group names
        //'DEFAULT_GROUP' => 'Index', // default group
        'APP_GROUP_MODE' => 0, //
        //'APP_GROUP_PATH' => 'Modules', //

        // template config
        'TMPL_VAR_IDENTIFY' => '', // 'array' or 'obj'
        'TMPL_FILE_DEPR' => '_' , // the separator used in template file between Module name and Action name
        'TMPL_TEMPLATE_SUFFIX' => '.php',
        'TPML_PARSE_STRING' => array(
                '__PUBLIC__' => __ROOT__ . '/' . APP_NAME . '/Tpl/Admin/Public'
        ),
        'URL_HTML_SUFFIX' => '.html',
        'TMPL_PATH' => 'app/Tpl/',

        //
        'DEFAULT_FILTER' => 'htmlspecialchars',

        /* DB setting */
        'DB_TYPE'               => 'mysql',     //
        'DB_HOST'               => 'localhost', //
        'DB_NAME'               => 'pop',          //
        'DB_USER'               => 'root',      //
        'DB_PWD'                => '',          //
        'DB_PORT'               => '',        //
        'DB_PREFIX'             => '',    //
        'DB_SQL_LOG'            => true, //

        'LOAD_EXT_CONFIG' => 'thirdParty', //
        'APP_AUTOLOAD_PATH' => '@.Pintag,@.Pinlib,@.ORG', //
        'LOAD_EXT_FILE'=>'AppTools',

 // session
 //'SESSION_TYPE' => 'DB',

);

/* EOF */