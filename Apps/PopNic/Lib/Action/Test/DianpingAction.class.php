<?php
/********************************************************/
/*****                 @!!@                          ****/
/********************************************************/
/**
 *@FileName    : DianpingAction.class.php
 *@Author      : Kilin WANG <wangkilin@126.com>
 *@Date        : 2014-5-19
 *@Homepage    : http://www.kinful.com
 *@Version     : 0.1
 */
class DianpingAction extends Action
{
    public $dianpingModel;

    public function _initialize()
    {
        set_time_limit(0);
        AppTools::autoload();
        import('Dianping.HttpRequester', MY_CLASS_PATH);
        import('Widget.ConvertFormat', MY_CLASS_PATH);

        $dianpingConfig = C('dianping');
        $this->dianpingModel = new DianpingRequester($dianpingConfig['appkey'], $dianpingConfig['secret']);

        //echo __CLASS__ . '/' . __FUNCTION__;
    }

    public function updateCityDistrict()
    {
        //$params = array('city'=>'');
        // get all cities and districts
        $city = M('city')->field('city_id, city_name')->where('province_id<35')->select();
        $city = ConvertFormat::bindIdWithName($city, 'city_name', 'city_id');
        $baseList = $this->dianpingModel->getAllCitiesAndDistrictsForGroupon();
        $couponList = $this->dianpingModel->getAllCitiesAndDistrictsForCoupons();
        $bookingList = $this->dianpingModel->getAllCitiesAndDistrictsForBooking();
        $retailList = $this->dianpingModel->getAllCitiesAndDistrictsForRetail();

        $cityList = array();
        $districtList = array();
        $infoList = array();
        foreach($baseList->cities as $cityKey=>$cityModel) {
            $cityName = $cityModel->city_name;
            $cityList[$cityName] = $cityKey;
            $districtList[$cityName] = array();
            if(!isset($infoList[$cityName]))
                $infoList[$cityName] = array('hasGroupon'=>1,
                                             'hasCoupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>0,
                                             'district'=>array());
            else
                $infoList[$cityName]['hasGroupon']=1;
            $infoList[$cityName]['city_id']= isset($city[$cityName]) ? $city[$cityName] : 0;
            $baseList->cities[$cityKey]->hasGroupon = 1;
            foreach($cityModel->districts as $districtKey=>$districtModel) {
                $districtName = $districtModel->district_name;
                if(!isset($infoList[$cityName]['district'][$districtName]))
                    $infoList[$cityName]['district'][$districtName] = array('hasGroupon'=>1,
                                             'hasCoupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>0,
                                             'zone'=>array());
                else
                    $infoList[$cityName]['district'][$districtName]['hasGroupon'] = 1;
                $baseList->cities[$cityKey]->districts[$districtKey]->hasGroupon = 1;
                $zoneList = $districtModel->neighborhoods;
                foreach($zoneList as $zoneName) {
                    if(!isset($infoList[$cityName]['district'][$districtName]['zone'][$zoneName]))
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName] = array('hasGroupon'=>1,
                                             'hasCoupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>0,
                                             );
                    else
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName]['hasGroupon']=1;
                }
            }
        }
        foreach($couponList->cities as $cityKey=>$cityModel) {
            $cityName = $cityModel->city_name;
            $cityList[$cityName] = $cityKey;
            $districtList[$cityName] = array();
            if(!isset($infoList[$cityName]))
                $infoList[$cityName] = array('hasCoupon'=>1,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>0,
                                              'district'=>array());
            else
                $infoList[$cityName]['hasCoupon']=1;
            $infoList[$cityName]['city_id']= isset($city[$cityName]) ? $city[$cityName] : 0;
            foreach($cityModel->districts as $districtKey=>$districtModel) {
                $districtName = $districtModel->district_name;
                if(!isset($infoList[$cityName]['district'][$districtName]))
                    $infoList[$cityName]['district'][$districtName] = array('hasCoupon'=>1,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>0,
                                               'zone'=>array());
                else
                    $infoList[$cityName]['district'][$districtName]['hasCoupon'] = 1;
                $zoneList = $districtModel->neighborhoods;
                foreach($zoneList as $zoneName) {
                    if(!isset($infoList[$cityName]['district'][$districtName]['zone'][$zoneName]))
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName] = array('hasCoupon'=>1,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>0,
                                              );
                    else
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName]['hasCoupon']=1;
                }
            }
        }
        foreach($bookingList->cities as $cityKey=>$cityModel) {
            $cityName = $cityModel->city_name;
            $cityList[$cityName] = $cityKey;
            $districtList[$cityName] = array();
            if(!isset($infoList[$cityName]))
                $infoList[$cityName] = array('hasCoupon'=>0,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>1,
                                             'hasRetail'=>0,
                                              'district'=>array());
            else
                $infoList[$cityName]['hasBooking']=1;
            $infoList[$cityName]['city_id']= isset($city[$cityName]) ? $city[$cityName] : 0;
            foreach($cityModel->districts as $districtKey=>$districtModel) {
                $districtName = $districtModel->district_name;
                if(!isset($infoList[$cityName]['district'][$districtName]))
                    $infoList[$cityName]['district'][$districtName] = array('hasCoupon'=>0,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>1,
                                             'hasRetail'=>0,
                                               'zone'=>array());
                else
                    $infoList[$cityName]['district'][$districtName]['hasBooking'] = 1;
                $zoneList = $districtModel->neighborhoods;
                foreach($zoneList as $zoneName) {
                    if(!isset($infoList[$cityName]['district'][$districtName]['zone'][$zoneName]))
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName] = array('hasCoupon'=>0,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>1,
                                             'hasRetail'=>0,
                                              );
                    else
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName]['hasBooking']=1;
                }
            }
        }
        foreach($retailList->cities as $cityKey=>$cityModel) {
            $cityName = $cityModel->city_name;
            $cityList[$cityName] = $cityKey;
            $districtList[$cityName] = array();
            if(!isset($infoList[$cityName]))
                $infoList[$cityName] = array('hasCoupon'=>0,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>1,
                                              'district'=>array());
            else
                $infoList[$cityName]['hasRetail']=1;
            $infoList[$cityName]['city_id']= isset($city[$cityName]) ? $city[$cityName] : 0;
            foreach($cityModel->districts as $districtKey=>$districtModel) {
                $districtName = $districtModel->district_name;
                if(!isset($infoList[$cityName]['district'][$districtName]))
                    $infoList[$cityName]['district'][$districtName] = array('hasCoupon'=>0,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>1,
                                               'zone'=>array());
                else
                    $infoList[$cityName]['district'][$districtName]['hasRetail'] = 1;
                $zoneList = $districtModel->neighborhoods;
                foreach($zoneList as $zoneName) {
                    if(!isset($infoList[$cityName]['district'][$districtName]['zone'][$zoneName]))
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName] = array('hasCoupon'=>0,
                                             'hasGroupon'=>0,
                                             'hasBooking'=>0,
                                             'hasRetail'=>1,
                                              );
                    else
                        $infoList[$cityName]['district'][$districtName]['zone'][$zoneName]['hasRetail']=1;
                }
            }
        }

        $cityModel = M('dianping_city');
        $districtModel = M('dianping_district');
        $zoneModel = M('dianping_zone');
        foreach($infoList as $cityName=>$cityInfo) {
            $sysCityInfo = $cityModel->where("city_name='" . addslashes($cityName)."'")->select();
            //echo $cityModel->getLastSql();exit;
            $data = array('sys_city_id'=>$cityInfo['city_id'],
                              'city_name'=>$cityName,
                              'has_retail'=>$cityInfo['hasRetail'],
                              'has_groupon'=>$cityInfo['hasGroupon'],
                              'has_coupon'=>$cityInfo['hasCoupon'],
                              'has_booking'=>$cityInfo['hasBooking']
                        );
            if($sysCityInfo) {
                $cityId = $sysCityInfo[0]['city_id'];
                $where = 'city_id = ' . $cityId;
                $result = $cityModel->save($data, array('where'=>$where));
            } else {
                $cityId = $result = $cityModel->add($data);
            }
            if(false===$result) {
                echo 'Save city failed: ' . print_r($data, true);
                continue;
            }
            foreach($cityInfo['district'] as $districtName=>$districtInfo) {
                $districtList = $districtModel->where('city_id='.$cityId . ' AND district_name=\''.addslashes($districtName).'\'')->select();
                $data = array(
                        'city_id'=>$cityId,
                        'district_name'=>$districtName,
                        'has_retail'=>$districtInfo['hasRetail'],
                        'has_groupon'=>$districtInfo['hasGroupon'],
                        'has_coupon'=>$districtInfo['hasCoupon'],
                        'has_booking'=>$districtInfo['hasBooking']
                );
                if($districtList) {
                    $districtId = $districtList[0]['district_id'];
                    $where = 'district_id = ' . $districtId;
                    $result = $districtModel->save($data, array('where'=>$where));
                } else {
                    $districtId = $result = $districtModel->add($data);
                }
                if(false===$result) {
                    echo 'Save district failed: ' . print_r($data, true);
                    continue;
                }

                foreach($districtInfo['zone'] as $zoneName=>$zoneInfo) {
                    $zoneList = $zoneModel->where('district_id='.$districtId . ' AND zone_name=\''.addslashes($zoneName).'\'')->select();
                    $data = array(
                            'district_id'=>$districtId,
                            'zone_name'=>$zoneName,
                            'has_retail'=>$zoneInfo['hasRetail'],
                            'has_groupon'=>$zoneInfo['hasGroupon'],
                            'has_coupon'=>$zoneInfo['hasCoupon'],
                            'has_booking'=>$zoneInfo['hasBooking']
                    );
                    if($zoneList) {
                        $zoneId = $zoneList[0]['zone_id'];
                        $where = 'zone_id = ' . $zoneId;
                        $result = $zoneModel->save($data, array('where'=>$where));
                    } else {
                        $zoneId = $result = $zoneModel->add($data);
                    }
                    if(false===$result) {
                        echo 'Save zone failed: ' . print_r($data, true);
                        continue;
                    }
                }
            }
        }

        //$string = print_r($infoList, true);
        //echo $string;

        //var_dump($response);
        //echo __CLASS__ . '/' . __FUNCTION__;
    }



    public function updateCategory()
    {
        $baseList = $this->dianpingModel->getGrouponCatetory();
        $couponList = $this->dianpingModel->getCouponsCatetory();
        $bookingList = $this->dianpingModel->getBookingCategory();
        $retailList = $this->dianpingModel->getRetailCatetory();

        echo print_r($baseList, true);
        echo print_r($couponList, true);
        echo print_r($bookingList, true);
        echo print_r($retailList, true);


       // exit;

        $infoList = array();
        foreach($baseList->categories as $categoryModel) {
            if(is_string($categoryModel)) {
                $categoryName = $categoryModel;
                if(!isset($infoList[$categoryName]))
                    $infoList[$categoryName] = array('hasGroupon'=>1,
                            'hasCoupon'=>0,
                            'hasBooking'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['hasGroupon']=1;
                continue;
            }
            $categoryName = $categoryModel->category_name;
            if(!isset($infoList[$categoryName]))
                $infoList[$categoryName] = array('hasGroupon'=>1,
                        'hasCoupon'=>0,
                        'hasBooking'=>0,
                        'hasRetail'=>0,
                        'sub'=>array());
            else
                $infoList[$categoryName]['hasGroupon']=1;

            foreach($categoryModel->subcategories as $subModel) {
                if(is_string($subModel)) {
                    $categoryName1 = $subModel;
                    if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                        $infoList[$categoryName]['sub'][$categoryName1] = array('hasGroupon'=>1,
                            'hasCoupon'=>0,
                            'hasBooking'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                    else
                        $infoList[$categoryName]['sub'][$categoryName1]['hasGroupon'] = 1;

                    continue;
                }
                $categoryName1 = $subModel->category_name;
                if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                    $infoList[$categoryName]['sub'][$categoryName1] = array('hasGroupon'=>1,
                            'hasCoupon'=>0,
                            'hasBooking'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['sub'][$categoryName1]['hasGroupon'] = 1;
                foreach($subModel->subcategories as $subModel1) {
                    if(is_string($subModel1)) {
                        $categoryName2 = $subModel1;
                        if(!isset($infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]))
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2] = array('hasGroupon'=>1,
                                    'hasCoupon'=>0,
                                    'hasBooking'=>0,
                                    'hasRetail'=>0,
                                    'sub'=>array());
                        else
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]['hasGroupon'] = 1;

                        continue;
                    }
                }
            }
        }
        foreach($couponList->categories as $categoryModel) {
            if(is_string($categoryModel)) {
                $categoryName = $categoryModel;
                if(!isset($infoList[$categoryName]))
                    $infoList[$categoryName] = array('hasCoupon'=>1,
                        'hasGroupon'=>0,
                        'hasBooking'=>0,
                        'hasRetail'=>0,
                        'sub'=>array());
                else
                    $infoList[$categoryName]['hasCoupon']=1;

                continue;
            }
            $categoryName = $categoryModel->category_name;
            if(!isset($infoList[$categoryName]))
                $infoList[$categoryName] = array('hasCoupon'=>1,
                        'hasGroupon'=>0,
                        'hasBooking'=>0,
                        'hasRetail'=>0,
                        'sub'=>array());
            else
                $infoList[$categoryName]['hasCoupon']=1;

            foreach($categoryModel->subcategories as $subModel) {
                if(is_string($subModel)) {
                    $categoryName1 = $subModel;
                    if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                        $infoList[$categoryName]['sub'][$categoryName1] = array('hasCoupon'=>1,
                            'hasGroupon'=>0,
                            'hasBooking'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                    else
                        $infoList[$categoryName]['sub'][$categoryName1]['hasCoupon'] = 1;

                    continue;
                }
                $categoryName1 = $subModel->category_name;
                if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                    $infoList[$categoryName]['sub'][$categoryName1] = array('hasCoupon'=>1,
                            'hasGroupon'=>0,
                            'hasBooking'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['sub'][$categoryName1]['hasCoupon'] = 1;

                foreach($subModel->subcategories as $subModel1) {
                    if(is_string($subModel1)) {
                        $categoryName2 = $subModel1;
                        if(!isset($infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]))
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2] = array('hasGroupon'=>1,
                                    'hasCoupon'=>0,
                                    'hasBooking'=>0,
                                    'hasRetail'=>0,
                                    'sub'=>array());
                        else
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]['hasGroupon'] = 1;

                        continue;
                    }
                }
            }
        }

        foreach($bookingList->categories as $categoryModel) {
            if(is_string($categoryModel)) {
                $categoryName = $categoryModel;
                if(!isset($infoList[$categoryName]))
                    $infoList[$categoryName] = array('hasBooking'=>1,
                            'hasCoupon'=>0,
                            'hasGroupon'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['hasBooking']=1;
                continue;
            }
            $categoryName = $categoryModel->category_name;
            if(!isset($infoList[$categoryName]))
                $infoList[$categoryName] = array('hasBooking'=>1,
                        'hasCoupon'=>0,
                        'hasGroupon'=>0,
                        'hasRetail'=>0,
                        'sub'=>array());
            else
                $infoList[$categoryName]['hasBooking']=1;

            foreach($categoryModel->subcategories as $subModel) {
                if(is_string($subModel)) {
                    $categoryName1 = $subModel;
                    if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                        $infoList[$categoryName]['sub'][$categoryName1] = array('hasBooking'=>1,
                            'hasCoupon'=>0,
                            'hasGroupon'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                    else
                        $infoList[$categoryName]['sub'][$categoryName1]['hasBooking'] = 1;

                    continue;
                }
                $categoryName1 = $subModel->category_name;
                if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                    $infoList[$categoryName]['sub'][$categoryName1] = array('hasBooking'=>1,
                            'hasCoupon'=>0,
                            'hasGroupon'=>0,
                            'hasRetail'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['sub'][$categoryName1]['hasBooking'] = 1;

                foreach($subModel->subcategories as $subModel1) {
                    if(is_string($subModel1)) {
                        $categoryName2 = $subModel1;
                        if(!isset($infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]))
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2] = array('hasBooking'=>1,
                                    'hasCoupon'=>0,
                                    'hasGroupon'=>0,
                                    'hasRetail'=>0,
                                    'sub'=>array());
                        else
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]['hasBooking'] = 1;

                        continue;
                    }
                }
            }
        }

        foreach($retailList->categories as $categoryModel) {
            if(is_string($categoryModel)) {
                $categoryName = $categoryModel;
                if(!isset($infoList[$categoryName]))
                    $infoList[$categoryName] = array('hasRetail'=>1,
                            'hasCoupon'=>0,
                            'hasBooking'=>0,
                            'hasGroupon'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['hasRetail']=1;
                continue;
            }
            $categoryName = $categoryModel->category_name;
            if(!isset($infoList[$categoryName]))
                $infoList[$categoryName] = array('hasRetail'=>1,
                        'hasCoupon'=>0,
                        'hasBooking'=>0,
                        'hasGroupon'=>0,
                        'sub'=>array());
            else
                $infoList[$categoryName]['hasRetail']=1;

            foreach($categoryModel->subcategories as $subModel) {
                if(is_string($subModel)) {
                    $categoryName1 = $subModel;
                    if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                        $infoList[$categoryName]['sub'][$categoryName1] = array('hasRetail'=>1,
                            'hasCoupon'=>0,
                            'hasBooking'=>0,
                            'hasGroupon'=>0,
                            'sub'=>array());
                    else
                        $infoList[$categoryName]['sub'][$categoryName1]['hasRetail'] = 1;

                    continue;
                }
                $categoryName1 = $subModel->category_name;
                if(!isset($infoList[$categoryName]['sub'][$categoryName1]))
                    $infoList[$categoryName]['sub'][$categoryName1] = array('hasRetail'=>1,
                            'hasCoupon'=>0,
                            'hasBooking'=>0,
                            'hasGroupon'=>0,
                            'sub'=>array());
                else
                    $infoList[$categoryName]['sub'][$categoryName1]['hasRetail'] = 1;

                foreach($subModel->subcategories as $subModel1) {
                    if(is_string($subModel1)) {
                        $categoryName2 = $subModel1;
                        if(!isset($infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]))
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2] = array('hasRetail'=>1,
                                    'hasCoupon'=>0,
                                    'hasBooking'=>0,
                                    'hasGroupon'=>0,
                                    'sub'=>array());
                        else
                            $infoList[$categoryName]['sub'][$categoryName1]['sub'][$categoryName2]['hasRetail'] = 1;

                        continue;
                    }
                }
            }
        }


        $categoryModel = M('dianping_category');
        foreach($infoList as $categoryName=>$subInfo) {
            $sysCategoryInfo = $categoryModel->where("category_name='" . addslashes($categoryName)."' and parent_id = 0")
                                             ->select();
            //echo $cityModel->getLastSql();exit;
            $data = array('parent_id'=>0,
                    'category_name'=>$categoryName,
                    'has_retail'=>$subInfo['hasRetail'],
                    'has_groupon'=>$subInfo['hasGroupon'],
                    'has_coupon'=>$subInfo['hasCoupon'],
                    'has_booking'=>$subInfo['hasBooking']
            );
            if($sysCategoryInfo) {
                $categoryId = $sysCategoryInfo[0]['category_id'];
                $where = 'category_id = ' . $categoryId;
                $result = $categoryModel->save($data, array('where'=>$where));
            } else {
                $categoryId = $result = $categoryModel->add($data);
            }
            if(false===$result) {
                echo __LINE__ . ' Save category failed: ' . print_r($data, true);
                continue;
            }
            foreach($subInfo['sub'] as $categoryName=>$subInfo1) {
                $sysCategoryInfo = $categoryModel->where("category_name='" . addslashes($categoryName)."' and parent_id = " . $categoryId)
                                                 ->select();
                //echo $cityModel->getLastSql();exit;
                $data = array('parent_id'=>$categoryId,
                        'category_name'=>$categoryName,
                        'has_retail'=>$subInfo1['hasRetail'],
                        'has_groupon'=>$subInfo1['hasGroupon'],
                        'has_coupon'=>$subInfo1['hasCoupon'],
                        'has_booking'=>$subInfo1['hasBooking']
                );
                if($sysCategoryInfo) {
                    $categoryId1 = $sysCategoryInfo[0]['category_id'];
                    $where = 'category_id = ' . $categoryId1;
                    $result = $categoryModel->save($data, array('where'=>$where));
                } else {
                    $categoryId1 = $result = $categoryModel->add($data);
                }
                if(false===$result) {
                    echo $categoryModel->getLastSql();
                    echo __LINE__ . ' Save category failed: ' . print_r($data, true);
                    continue;
                }

                foreach($subInfo1['sub'] as $categoryName=>$subInfo2) {
                    $sysCategoryInfo = $categoryModel->where("category_name='" . addslashes($categoryName)."' and parent_id = " . $categoryId1)
                                                     ->select();
                    //echo $cityModel->getLastSql();exit;
                    $data = array('parent_id'=>$categoryId1,
                            'category_name'=>$categoryName,
                            'has_retail'=>$subInfo2['hasRetail'],
                            'has_groupon'=>$subInfo2['hasGroupon'],
                            'has_coupon'=>$subInfo2['hasCoupon'],
                            'has_booking'=>$subInfo2['hasBooking']
                    );
                    if($sysCategoryInfo) {
                        $categoryId2 = $sysCategoryInfo[0]['category_id'];
                        $where = 'category_id = ' . $categoryId2;
                        $result = $categoryModel->save($data, array('where'=>$where));
                    } else {
                        $categoryId2 = $result = $categoryModel->add($data);
                    }
                    if(false===$result) {
                        echo __LINE__ . ' Save category failed: ' . print_r($data, true);
                        continue;
                    }
                }

            }
        }

        //$string = print_r($infoList, true);
        //echo $string;

        //var_dump($response);
        //echo __CLASS__ . '/' . __FUNCTION__;
    }

    /**
     * Get Retail list on dianping.com
     * @param int $cityId
     */
    public function getRetailByCityId()
    {
        $cityId = I('cityId', 190);
        $cityList = M('dianping_city')->field('city_name,sys_city_id')->where('sys_city_id='.$cityId.' AND has_retail=1')->select();
        foreach($cityList as $cityInfo) {
            $conditions = array('city'=>$cityInfo['city_name']);
            $response = $this->dianpingModel->getRetailByConditions($conditions);
            echo print_r($response, true);
        }
    }
}
