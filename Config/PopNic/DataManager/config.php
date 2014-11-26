<?php
return array(
    'DISPLAY_NUMBER_PERPAGE' => 30,
    'VAR_PAGE' => 'pageIndex',


    'DataManageConfig' =>
        array (
            'Country' =>
                array(
                        'title'=>'Country',
                        'desc' => 'Country Management',
                        'table'=> 'country',
                        'method'=>'auto',
                        'columnTitles' => array('country_id', 'country_name'),
                ),

            'Province' =>
                array(
                        'title'=>'Province',
                        'desc' => 'Province Management',
                        'table'=> 'province',
                        'method'=>'auto',
                        'columnTitles' => array('country_id', 'country_name'),
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'step' => array('getCountry'), // step can not be duplicate

                                        'stepForm' => array('getCountry'=>array('autoSubmit'=>false, 'hasEmptyOption'=>false)),
                                ),
                        'list' => array (
                                        'prepare'=>array('getCountry'),
                                        'columnMap'=>array('country_id'=>'getCountry'),
                                        'replaceMap'  => array('country_id'=>'getCountry'),
                                ),
                        'steps' => array(
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                ),
                ),

            'City' =>
                array(
                        'title'=>'City',
                        'desc' => 'City Management',
                        'table'=> 'city',
                        'method'=>'auto',
                        'columnTitles' => array('country_id', 'country_name'),
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'step' => array('getCountry', 'getProvince'), // step can not be duplicate

                                        'stepForm' => array('getCountry'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true), // this filter form is submit automatically
                                                        'getProvince'=>array('autoSubmit'=>false, 'hasEmptyOption'=>false),
                                                ),
                        ),
                        'list' => array (
                                        'prepare'=>array('getCountry'),
                                        'columnMap'=>array('province_id'=>'getProvince'),
                                        'replaceMap'  => array('country_id'=>'getCountry'),
                                ),
                        'steps' => array(
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                    'getProvince'=>array('label' => 'Choose Province',
                                        'table'=>'province',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'province_id', 'value'=>'province_name'),
                                        //'sql'=>'select *',,
                                        'columnMap'=>array('country_id'=>'getCountry')
                                    ),
                                ),
                ),

            'Scenery' =>
                array(
                        'title'   => 'Scenery',
                        'desc'    => 'scenery management',
                        'table'   => 'scenery',
                        'method'  => 'defined', // the table management is defined in list
                        'crudKey' => array('scenery_id'), // can NOT be changed.
                        'crudFieldChild' => array('country_id'=>'province_id', // while country change, province will change accordingly
                                                  'province_id'=>'city_id'),
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'cacheData' => array('step2'),
                                        'step' => array('getCountry',
                                                        'getProvince',
                                                        'getCity'), // step can not be duplicate

                                        'stepForm' => array('getCountry'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true), // this filter form is submit automatically
                                                        'getProvince'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getCity'=>array('autoSubmit'=>false, 'hasEmptyOption'=>true)),
                                ),
                        'list' => array (
                                        'prepare'=>array('getCity', 'step_2'),
                                        'listColumns' => array(
                                                               'scenery_name',
                                                               'city_id',
                                                               'summary',
                                                               'score',
                                                               'rank',
                                                               'play_time',
                                                               'longitude',
                                                               'latitude',
                                                               'total_spot',
                                                               'total_image',
                                                         ),
                                        'columnMap'   => array('city_id'=>'getCity'),
                                        'sql' => 'select count(distinct ss.spot_id) as total_spot, count(distinct si.img_id) as total_image, s.*
                                                  from scenery s
                                                  left join scenery_spot ss on s.scenery_id = ss.scenery_id
                                                  left join scenery_img si on s.scenery_id = si.scenery_id
                                                  where s.city_id = %d
                                                  group by s.scenery_id
												  order by s.rank, s.scenery_id',
                                        'sqlParam' => array('getCity'),
                                        'replaceMap'  => array('city_id'=>'getCity'),
                                        'orderBy'     => array('rank , scenery_id'),
                                        'moreLinks' => array(
                                                array('text'=>'Images', 'class'=>'addImageLink',
                                                      'url'=>'DataManager/Index/manageTable',
                                                       // if the parameter value string is in the CrudKeyList, it will be replaced by CRUD key
                                                      'urlParam'=>array('tableId'=>'SceneryImages',
                                                                        'getScenery'=>'scenery_id',
                                                                        'getCountry','getProvince',
                                                                        'getCity','stepIndex'=>4)
                                                ),
                                                array('text'=>'Spots', 'class'=>'addImageLink',
                                                      'url'=>'DataManager/Index/manageTable',
                                                       // if the parameter value string is in the CrudKeyList, it will be replaced by CRUD key
                                                      'urlParam'=>array('tableId'=>'ScenerySpot',
                                                                        'getScenery'=>'scenery_id',
                                                                        'getCountry','getProvince',
                                                                        'getCity','stepIndex'=>4)
                                                ),
                                                array('text'=>'Story', 'class'=>'addImageLink',
                                                      'url'=>'DataManager/Index/addItem',
                                                       // if the parameter value string is in the CrudKeyList, it will be replaced by CRUD key
                                                      'urlParam'=>array('tableId'=>'SceneryStory',
                                                                        'getScenery'=>'scenery_id',
                                                                        'getCountry','getProvince',
                                                                        'getCity','stepIndex'=>4,
                                                                        'story_link_id'=>'scenery_id',
                                                      )
                                                ),
                                            ),
                                ),
                        'edit' => array (
                                        'prepare'=>array('editPrepareCity', 'editPrepareProvince', 'editPrepareCountry'),// prepare data
                                        'columnMap'   => array('scenery_id'=>'scenery_id'),
                                        'replaceMap'  => array('city_id'=>'editPrepareCity.city_id'),
                                        // append more information as condition to filter a field
                                        'appendBefore'=> array('city_id'=>array('country_id'=>'editPrepareCountry','province_id'=>'editPrepareProvince')),
                                        'appendAfter' => array(),
                                ),
                        'steps' => array(
                                    'editPrepareCountry'=>array(
                                        'table'=>'country',
                                        'column'=>   array('country_id', 'country_name'),
                                    ),
                                    'editPrepareProvince'=>array(
                                        'table'=>'province',
                                        'column'=>   array('province_id', 'province_name', 'country_id'),
                                    ),
                                    'editPrepareCity'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name', 'province_id'),
                                    ),

                                    'step_1'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name'),
                                        'sql'=>'select scenery_id, scenery_name from scenery where o_id = "%s" and s_id="%d"',
                                        'sqlParam' => array('step1_1', 'step1_2'),// parameter in GET, POST
                                    ),
                                    'step_2'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name'),
                                        'columnMap'=>array('city_id'=>'step1_1'),// parameter in GET, POST

                                    ),
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                    'getProvince'=>array('label' => 'Choose Province',
                                        'table'=>'province',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'province_id', 'value'=>'province_name'),
                                        //'sql'=>'select *',,
                                        'columnMap'=>array('country_id'=>'getCountry'),
                                        'orderBy' => array('zip_code'),
                                    ),
                                    'getCity'=>array('label' => 'Choose City',
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name'),
                                        //'sql'=>'select *',
                                        //'columnMap'=>array('id'=>'step1_1'),
                                        'columnMap'=>array('province_id'=>'getProvince'),
                                        'orderBy' => array('zip_code'),
                                    ), // map column1 in step1_1
                                    'step2'=>array('name' => 'search step2',
                                        'table'=>'scenery',
                                        'column'=>   array('column1'),
                                        'sql'=>'select scenery_id, scenery_name from scenery where o_id = "%s" and s_id="%d" order by scenery_id',
                                        'sqlParam' => array('step1_1', 'step1_2'),
                                        'columnMap'=>array('column1'=>'step1_1')
                                    ),
                                ),
                        'listColumns' => array('column1'),
                        'searchColumns' => array('column1', 'column2'),
                        'columnTitles' => array('scenery_name'=>'SceneryName',
                                                'summary'=>'Desc',
                                                'city_id'=>'City'),
                        'crudRef' => array(array('step1_1', 'step1_2'), 'step2'),
                        'crudColumns' =>  array(
                                'scenery_id'   => array('type'=>'hidden',
                                                        'format'=>'int'),
                                'scenery_name' => array('type'=>'input',
                                                        'format'=>'string'),
                                'city_id'   => array('type'=>'select',
                                                     'format'=>'int'),
                                'summary'   => array('type'=>'input',
                                                     'format'=>'text'),
                                'score'     => array('type'=>'input',
                                                     'format'=>'float'),
                                'rank'      => array('type'=>'input',
                                                     'format'=>'int'),
                                'play_time' => array('type'=>'input',
                                                     'format'=>'string'),
                                'longitude' => array('type'=>'input',
                                                     'format'=>'float'),
                                'latitude'  =>  array('type'=>'input',
                                                      'format'=>'float'),
                        ),

                        'step1_1'=>array('name' => 'step1_1',
                                'table'=>'table2',
                                'columnTitles' => array(),
                                'column'=>   array('column1', 'column2'),
                                'sql'=>'select *',
                                'columnMap'=>array('column1'=>'step2.column1')), // map column1 in step2
                        'step2'=>array('name' => 'step1',
                                'table'=>'table2',
                                'columnTitles' => array(),
                                'column'=>   array('column1', 'column2'),
                                'sql'=>'select * from table2 where column1 = "%s" and column2 = %d order by',
                                'sqlParam' => array('column1', 'column2'),
                                'columnMap'=>array('column1'=>'.column1')), // .column1 means it will be used outside of steps
                        'editWhereColumns' => array(), // used to construct WHERE condition in update sql
                ),
            'SceneryImages' =>
                array(
                        'title'   => 'SceneryImages',
                        'desc'    => 'scenery images management',
                        'table'   => 'scenery_img',
                        'method'  => 'defined', // the table management is defined in list
                        'crudKey' => array('img_id'), // can NOT be changed.
                        'crudFieldChild' => array('country_id'=>'province_id', // while country change, province will change accordingly
                                                  'province_id'=>'city_id',
                                                  'city_id' => 'scenery_id'),
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'step' => array('getCountry',
                                                        'getProvince',
                                                        'getCity',
                                                        'getScenery',
                                                        ), // step can not be duplicate

                                        'stepForm' => array('getCountry'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true), // this filter form is submit automatically
                                                        'getProvince'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getCity'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getScenery'=>array('autoSubmit'=>false, 'hasEmptyOption'=>true)),
                                ),
                        'list' => array (
                                        'prepare'=>array('step_1', 'step_2'),
                                        'listColumns' => array(
                                                               'img_id',
                                                               'img_url'=>array('type'=>'image', 'class'=>'sceneryImgListStyle'),
                                                         ),
                                        'column'      => array('scenery_id',
                                                               'img_id',
                                                               'img_url',
                                                         ),
                                        'columnMap'   => array('scenery_id'=>'getScenery'),
                                        'replaceMap'  => array('scenery_id'=>'getScenery'),
                                        'orderBy'     => array(' scenery_id'),
                                ),
                        'edit' => array (
                                        'prepare'=>array('editPrepareScenery',
                                                         'editPrepareCity',
                                                         'editPrepareProvince',
                                                         'editPrepareCountry',
                                                         'editPrepareSceneryByImgId'),// prepare data
                                        // used to get item data ('column_name'=>'request_parameter_name')
                                        'columnMap'   => array('img_id'=>'img_id'),
                                        'replaceMap'  => array('scenery_id'=>'editPrepareSceneryByImgId.scenery_id'),
                                        // append more information as condition to filter a field
                                        'appendBefore'=> array('scenery_id'=>array('country_id'=>'editPrepareCountry',
                                                                                   'province_id'=>'editPrepareProvince',
                                                                                   'city_id'=>'editPrepareCity'
                                                                            )
                                                         ),
                                        'appendAfter' => array(),
                                        // the attributes to be assigned to the form elements
                                        'columnAttributes' => array(
                                                            'city_id' => array('class'=>'loadByAjax',
                                                                               'ajaxUrl'=>'DataManager/Ajax/getData',
                                                                               'urlParam'=>array('tableId'=>'SceneryImages','stepKey'=>'getCityByProvinceId'),
                                                                    ),
                                                            'scenery_id' => array('class'=>'loadByAjax',
                                                                                  'ajaxUrl'=>'DataManager/Ajax/getData',
                                                                                  'urlParam'=>array('tableId'=>'SceneryImages','stepKey'=>'editPrepareScenery'),
                                                                    ),
                                                         ),
                                ),
                        'steps' => array(
                                    'editPrepareCountry'=>array(
                                        'table'=>'country',
                                        'column'=>   array('country_id', 'country_name'),
                                    ),
                                    'editPrepareProvince'=>array(
                                        'table'=>'province',
                                        'column'=>   array('province_id', 'province_name', 'country_id'),
                                        //'columnMap'=>   array('country_id'=>'getCountry'),
                                    ),
                                    'editPrepareCity'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name', 'province_id'),
                                        'columnMap'=>   array('province_id'=>'getProvince'),
                                        /*
                                        'ajax'=>array('url'=>'DataManager/Ajax/getData',
                                                      'urlParam'=>array('tableId'=>'SceneryImages','stepKey'=>'editPrepareCity'),
                                        ),
                                        */
                                    ),
                                    'getCityByProvinceId'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name', 'province_id'),
                                        'columnMap'=>   array('province_id'=>'province_id'),
                                    ),
                                    'editPrepareScenery'=>array(
                                        'table'=>'scenery',
                                        'column'=>   array('scenery_id', 'scenery_name', 'city_id'),
                                        'columnMap'=>   array('city_id'=>'city_id'),
                                        'ajax'=>array('url'=>'DataManager/Ajax/getData',
                                                      'urlParam'=>array('tableId'=>'SceneryImages','stepKey'=>'editPrepareScenery'),
                                        ),
                                    ),
                                    'editPrepareSceneryByImgId'=>array(
                                        'table'=>'scenery',
                                        'column'=>   array('scenery_id', 'scenery_name', 'city_id'),
                                        'sql' => 'select scenery_id, scenery_name, city_id from scenery where city_id =
                                            (select city_id from scenery where scenery_id = (select scenery_id from scenery_img where img_id = %d))',
                                        'sqlParam'=>array('img_id'),
                                    ),

                                    'step_1'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name'),
                                        'sql'=>'select scenery_id, scenery_name from scenery where o_id = "%s" and s_id="%d"',
                                        'sqlParam' => array('step1_1', 'step1_2'),// parameter in GET, POST
                                    ),
                                    'step_2'=>array(
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name'),
                                        'columnMap'=>array('city_id'=>'step1_1'),// parameter in GET, POST

                                    ),
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                    'getProvince'=>array('label' => 'Choose Province',
                                        'table'=>'province',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'province_id', 'value'=>'province_name'),
                                        //'sql'=>'select *',,
                                        'columnMap'=>array('country_id'=>'getCountry')
                                    ),
                                    'getCity'=>array('label' => 'Choose City',
                                        'table'=>'city',
                                        'column'=>   array('city_id', 'city_name'),
                                        //'sql'=>'select *',
                                        //'columnMap'=>array('id'=>'step1_1'),
                                        'columnMap'=>array('province_id'=>'getProvince')
                                    ), // map column1 in step1_1,
                                    'getScenery'=>array('label' => 'Choose Scenery',
                                        'table'=>'scenery',
                                        'column'=>   array('scenery_id', 'scenery_name'),
                                        //'sql'=>'select *',
                                        //'columnMap'=>array('id'=>'step1_1'),
                                        'columnMap'=>array('city_id'=>'getCity')
                                    ), // map column1 in step1_1
                                    'step2'=>array('name' => 'search step2',
                                        'table'=>'scenery',
                                        'column'=>   array('column1'),
                                        'sql'=>'select scenery_id, scenery_name from scenery where o_id = "%s" and s_id="%d" order by scenery_id',
                                        'sqlParam' => array('step1_1', 'step1_2'),
                                        'columnMap'=>array('column1'=>'step1_1')
                                    ),
                                ),
                        'listColumns' => array('column1'),
                        'searchColumns' => array('column1', 'column2'),
                        'columnTitles' => array('scenery_name'=>'SceneryName',
                                                'summary'=>'Desc',
                                                'city_id'=>'City'),
                        'crudRef' => array(array('step1_1', 'step1_2'), 'step2'),
                        'crudColumns' =>  array(
                                'scenery_id'   => array('type'=>'hidden',
                                                        'format'=>'int'),
                                'scenery_name' => array('type'=>'input',
                                                        'format'=>'string'),
                                'city_id'   => array('type'=>'select',
                                                     'format'=>'int'),
                                'summary'   => array('type'=>'input',
                                                     'format'=>'text'),
                                'score'     => array('type'=>'input',
                                                     'format'=>'float'),
                                'rank'      => array('type'=>'input',
                                                     'format'=>'int'),
                                'play_time' => array('type'=>'input',
                                                     'format'=>'string'),
                                'longitude' => array('type'=>'input',
                                                     'format'=>'float'),
                                'latitude'  =>  array('type'=>'input',
                                                      'format'=>'float'),
                        ),

                        'step1_1'=>array('name' => 'step1_1',
                                'table'=>'table2',
                                'columnTitles' => array(),
                                'column'=>   array('column1', 'column2'),
                                'sql'=>'select *',
                                'columnMap'=>array('column1'=>'step2.column1')), // map column1 in step2
                        'step2'=>array('name' => 'step1',
                                'table'=>'table2',
                                'columnTitles' => array(),
                                'column'=>   array('column1', 'column2'),
                                'sql'=>'select * from table2 where column1 = "%s" and column2 = %d order by',
                                'sqlParam' => array('column1', 'column2'),
                                'columnMap'=>array('column1'=>'.column1')), // .column1 means it will be used outside of steps
                        'editWhereColumns' => array(), // used to construct WHERE condition in update sql
                ),

            'ScenerySpot' =>
                array(
                        'title'=>'ScenerySpot',
                        'desc' => 'Scenery Spot Management',
                        'table'=> 'scenery_spot',
                        'method'=>'defined',
                        'columnTitles' => array(),
                        'crudKey' => array('spot_id'), // can NOT be changed.
                        //*
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'step' => array('getCountry', 'getProvince',
                                                        'getCity', 'getScenery'), // step can not be duplicate

                                        'stepForm' => array(
                                                        'getCountry'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true), // this filter form is submit automatically
                                                        'getProvince'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getCity'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getScenery'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                ),
                        ),
                        //*/
                        'list' => array (
                                        'prepare'=>array('getCountry'),
                                        'listColumns' => array(
                                                               'spot_id',
                                                               'spot_title'=>array('class'=>'sceneryImgListStyle'),
                                                               'spot_detail'=>array('class'=>'sceneryImgListStyle'),
                                                               'score'=>array('class'=>'sceneryImgListStyle'),
                                                               'scenery_name'=>array('class'=>'sceneryImgListStyle'),
                                                               'city_name',
                                                               'province_name'
                                                         ),
                                        'column'      => array('spot_id',
                                                               'spot_title',
                                                               'spot_detail',
                                                               'score',
                                                               'scenery_name',
                                                               'city_name',
                                                               'province_name'
                                                         ),
                                        'moreLinks' => array(
                                                array('text'=>'Story', 'class'=>'addImageLink',
                                                      'url'=>'DataManager/Index/addItem',
                                                       // if the parameter value string is in the CrudKeyList, it will be replaced by CRUD key
                                                      'urlParam'=>array('tableId'=>'SpotStory',
                                                                        'getScenery',
                                                                        'getCountry','getProvince',
                                                                        'getCity','stepIndex'=>5,
                                                                        'getScenerySpot'=>'spot_id',
                                                                        'story_type'=>1,
                                                      )
                                                ),
                                            ),
                                        'sql'=>'select sp.*, s.scenery_name, c.city_name,c.city_id, p.province_name, p.province_id, p.country_id
                                                from scenery_spot sp
                                                inner join scenery s
                                                    on sp.scenery_id = s.scenery_id
                                                inner join city c
                                                    on s.city_id = c.city_id
                                                inner join province p
                                                    on c.province_id = p.province_id
                                                where c.city_id = %d
                                                  and s.scenery_id = %d ',
                                        'sqlParam' => array('getCity', 'getScenery'),
                                ),
                        'steps' => array(
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                    'getProvince'=>array('label' => 'Choose Province',
                                        'table'=>'province',
                                        'column'=>   array('key'=>'province_id', 'value'=>'province_name'),
                                        'columnMap'=>array('country_id'=>'getCountry')
                                    ),
                                    'getCity'=>array('label' => 'Choose City',
                                        'table'=>'city',
                                        'column'=>   array('key'=>'city_id', 'value'=>'city_name'),
                                        'columnMap'=>array('province_id'=>'getProvince')
                                    ),
                                    'getScenery'=>array('label' => 'Choose Scenery',
                                        'table'=>'scenery',
                                        'column'=>   array('key'=>'scenery_id', 'value'=>'scenery_name'),
                                        'columnMap'=>array('city_id'=>'getCity')
                                    ),
                                ),
                ),

            'SpotStory' =>
                array(
                        'title'=>'SpotStory',
                        'desc' => 'Scenery Spot story Management',
                        'table'=> 'story',
                        'method'=>'auto',
                        'columnTitles' => array(),
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'step' => array('getCountry', 'getProvince',
                                                        'getCity', 'getScenery','getScenerySpot'), // step can not be duplicate

                                        'stepForm' => array(
                                                        'getCountry'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true), // this filter form is submit automatically
                                                        'getProvince'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getCity'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getScenery'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getScenerySpot'=>array('autoSubmit'=>false, 'hasEmptyOption'=>true),
                                                ),
                                         'addButtonParam' => array('story_type'=>1),
                        ),
                        'edit'=> array('defaultValue'=>array(
                                                        'story_type'=>array('param'=>'story_type'), // default value is from: if key is 'param', it is from request; or, it is normal string
                                                        'story_link_id'=>array('param'=>'getScenerySpot'), // default value is from: if key is 'param', it is from request; or, it is normal string
                                                       ),
                                       'radioLabel'=>array(
                                                        'story_type' => array('0'=>'Of Scenery ', '1'=>'Of Scenery Spot ')
                                                       ),
                                 ),
                        'list' => array (
                                        'prepare'=>array('getCountry'),
                                        'columnMap'=>array('story_link_id'=>'getScenery'),
                                        'replaceMap'  => array('story_link_id'=>'getScenery'),
                                ),
                        'steps' => array(
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                    'getProvince'=>array('label' => 'Choose Province',
                                        'table'=>'province',
                                        'column'=>   array('key'=>'province_id', 'value'=>'province_name'),
                                        'columnMap'=>array('country_id'=>'getCountry'),
                                        'orderBy'=>array('zip_code')
                                    ),
                                    'getCity'=>array('label' => 'Choose City',
                                        'table'=>'city',
                                        'column'=>   array('key'=>'city_id', 'value'=>'city_name'),
                                        'columnMap'=>array('province_id'=>'getProvince'),
                                        'orderBy'=>array('weather_code', 'zip_code')
                                    ),
                                    'getScenery'=>array('label' => 'Choose Scenery',
                                        'table'=>'scenery',
                                        'column'=>   array('key'=>'scenery_id', 'value'=>'scenery_name'),
                                        'columnMap'=>array('city_id'=>'getCity'),
                                        'orderBy'=>array('rank')
                                    ),
                                    'getScenerySpot'=>array('label' => 'Choose Spot',
                                        'table'=>'scenery_spot',
                                        'column'=>   array('key'=>'spot_id', 'value'=>'spot_title'),
                                        'columnMap'=>array('scenery_id'=>'getScenery')
                                    ),
                                ),
                ),

            'SceneryStory' =>
                array(
                        'title'=>'SceneryStory',
                        'desc' => 'Scenery story Management',
                        'table'=> 'story',
                        'method'=>'auto',
                        'columnTitles' => array(),
                        'listFilter' => array(
                                        'filterTemplate' => 'defaultFilter',
                                        'step' => array('getCountry', 'getProvince',
                                                        'getCity', 'getScenery'), // step can not be duplicate

                                        'stepForm' => array(
                                                        'getCountry'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true), // this filter form is submit automatically
                                                        'getProvince'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getCity'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                        'getScenery'=>array('autoSubmit'=>true, 'hasEmptyOption'=>true),
                                                ),
                        ),
                        'edit'=> array('defaultValue'=>array(
                                                        'story_type'=>array('param'=>'story_type'), // default value is from: if key is 'param', it is from request; or, it is normal string
                                                        'story_link_id'=>array('param'=>'story_link_id'), // default value is from: if key is 'param', it is from request; or, it is normal string
                                                       ),
                                       'radioLabel'=>array(
                                                        'story_type' => array('0'=>'Of Scenery ', '1'=>'Of Scenery Spot ')
                                                       ),
                                 ),
                        'list' => array (
                                        'prepare'=>array('getCountry'),
                                        'columnMap'=>array('scenery_id'=>'getScenery'),
                                        'replaceMap'  => array('scenery_id'=>'getScenery'),
                                ),
                        'steps' => array(
                                    'getCountry'=>array('label' => 'Choose Country',
                                        'table'=>'country',
                                        // generate select box. key is option text, value is option value
                                        'column'=>   array('key'=>'country_id', 'value'=>'country_name'),
                                        //'sql'=>'select *',
                                    ),
                                    'getProvince'=>array('label' => 'Choose Province',
                                        'table'=>'province',
                                        'column'=>   array('key'=>'province_id', 'value'=>'province_name'),
                                        'columnMap'=>array('country_id'=>'getCountry'),
                                        'orderBy'=>array('zip_code')
                                    ),
                                    'getCity'=>array('label' => 'Choose City',
                                        'table'=>'city',
                                        'column'=>   array('key'=>'city_id', 'value'=>'city_name'),
                                        'columnMap'=>array('province_id'=>'getProvince'),
                                        'orderBy'=>array('weather_code', 'zip_code')
                                    ),
                                    'getScenery'=>array('label' => 'Choose Scenery',
                                        'table'=>'scenery',
                                        'column'=>   array('key'=>'scenery_id', 'value'=>'scenery_name'),
                                        'columnMap'=>array('city_id'=>'getCity'),
                                        'orderBy'=>array('rank')
                                    ),
                                ),
                ),
        )
/*
 * 1. list all editable item
 * 2. choose an item
 *     a. check if has 'listFilter', if yes, get filter stepIndex parameter.
 *     b. check if the stepIndex step exists.
 *        basing on the stepIndex step parameters in config, prepare data for this step.
 *             if has SQL, query SQL. or get all data in table
 *        create a map by using columMap config parameter:
 *             array key is the column name from this step.
 *             array value is linked to other steps
 *     c. check if has next step, if has, go to b.
 *     d. cache columns data mentioned in 'filterMap' config parameter
 *     d. render filter template , and bind to a view parameter
 *          render filterTemplate in config if it is specified
 *          Or render the default filter page
 * 3. list item, replace column names.
 *     a. get item by specified column names
 *        or get item by SQL
 *     b. get table structure, and get fixed values
 *     c. according to 'columnMap', find the crudRef steps
 *     d. according to steps, get associated records, and replace columns data in item list
 *     e. display 'Edit', 'Delete'. according to Primary key, bind right link to 'Edit' and 'Delete'
 * 4. choose an item to edit
 *     a. get crudRef information
 *         A. get current crudRef steps,
 *         B. cache the parameters mentioned in 'columnMap'.
 *            In next step, cached parameters will be used in 'sqlParam';
 *            cache the query result, it will be used in list page.
 *            list columns mentioned in step in a dropdown,
 *         C. cache step number, go to next step
 *     b. get table structure, and get fixed values
 *     c. according to GET['whereClause'] to search and display item for update
 *         A. replace associated columns with cached parameters
 * 5. choose an item to delete
 */
);

/* EOF */