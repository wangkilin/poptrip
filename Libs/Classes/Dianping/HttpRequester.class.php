<?php
require_once(dirname(__FILE__) . '/../ClassAbstract.class.php');
require_once(dirname(__FILE__) . '/../Error/ErrorDesc.class.php');

class DianpingRequester extends ClassAbstract
{
    protected $appKey = null;
    protected $appSecret = null;

    protected $urlList = array(
        #######################
        # 团购类API：
        #######################
        // 获取当前在线的全部团购ID列表
        'deal/get_all_id_list' => 'http://api.dianping.com/v1/deal/get_all_id_list',

        // 获取每日新增团购ID列表
        'deal/get_daily_new_id_list' => 'http://api.dianping.com/v1/deal/get_daily_new_id_list',

        /*
         *获取指定时间内卖完的团购ID列表
         *
            请求参数

              必选参数
                名称 	类型 	说明
                appkey 	string 	App Key，应用的唯一标识
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
                city 	string 	包含团购信息的城市名称，可选范围见相关API返回结果
                begin_time 	string 	查询起始时间，格式为“YYYY-MM-DD hh:mm:ss”
                end_time 	string 	查询结束时间，格式为“YYYY-MM-DD hh:mm:ss”
              可选参数
                名称 	类型 	说明
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的单页团购ID数量
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                id_list 	list 	团购单ID列表
         */
        'deal/get_sold_out_id_list' => 'http://api.dianping.com/v1/deal/get_sold_out_id_list',

        /*
         *批量获取指定团购信息
         *
            请求参数

              必选参数
                名称 	类型 	说明
                deal_ids 	string 	一个或多个团购ID集合，多ID之间以英文逗号分隔，如“1-120239,1-121039,1-87299”，一次传入的ID数量上限为40个，其他参数限制请参考下方注意事项
                appkey 	string 	App Key，应用的唯一标识
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
              可选参数
                名称 	类型 	说明
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的单页团购数量
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                deal_id 	string 	团购单ID
                title 	string 	团购标题
                description 	string 	团购描述
                city 	string 	城市名称，city为＂全国＂表示全国单，其他为本地单，城市范围见相关API返回结果
                list_price 	float 	团购包含商品原价值
                current_price 	float 	团购价格
                regions 	list 	团购适用商户所在商区
                categories 	list 	团购所属分类
                purchase_count 	int 	团购当前已购买数
                publish_date 	string 	团购发布上线日期
                details 	string 	团购详情
                purchase_deadline 	string 	团购单的截止购买日期
                image_url 	string 	团购图片链接，最大图片尺寸450×280
                s_image_url 	string 	小尺寸团购图片链接，最大图片尺寸160×100
                more_image_urls 	list 	更多大尺寸图片
                more_s_image_urls 	list 	更多小尺寸图片
                is_popular 	int 	是否为热门团购，0：不是，1：是
                restrictions 	list 	团购限制条件
                restrictions.is_reservation_required 	int 	是否需要预约，0：不是，1：是
                restrictions.is_refundable 	int 	是否支持随时退款，0：不是，1：是
                restrictions.special_tips 	string 	特别提示(一般为团购的限制信息)
                notice 	string 	重要通知(一般为团购信息的临时变更)
                deal_url 	string 	团购Web页面链接，适用于网页应用
                deal_h5_url 	string 	团购HTML5页面链接，适用于移动应用和联网车载应用
                commission_ratio 	float 	当前团单的佣金比例
                businesses 	list 	团购所适用的商户列表
                businesses.name 	string 	商户名
                businesses.id 	int 	商户ID
                businesses.address 	string 	商户地址
                businesses.latitude 	float 	商户纬度
                businesses.longitude 	float 	商户经度
                businesses.url 	string 	商户页链接
         */
        'deal/get_batch_deals_by_id' => 'http://api.dianping.com/v1/deal/get_batch_deals_by_id',

        // 获取指定团购信息
        'deal/get_single_deal' => 'http://api.dianping.com/v1/deal/get_single_deal',

        // 获取指定商户的团购信息
        'deal/get_deals_by_business_id' => 'http://api.dianping.com/v1/deal/get_deals_by_business_id',

        // 搜索团购
        'deal/find_deals' => 'http://api.dianping.com/v1/deal/find_deals',

        /*
         *获取变更的团购ID列表
         *
            请求参数

              必选参数
                名称 	类型 	说明
                appkey 	string 	App Key，应用的唯一标识
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
                begin_time 	string 	变更的开始时间，格式为“yyyy-MM-dd HH:mm:ss“，开始时间不得早于当前时间12个小时
                city 	string 	包含团购信息的城市名称，支持最多5个城市合并查询，城市之间使用英文逗号分隔，可选范围见相关API返回结果
              可选参数
                名称 	类型 	说明
                category 	string 	包含团购信息的分类名称，支持最多5个category合并查询，多个category用逗号分割，可选范围见相关API返回结果
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的单页团购ID数量
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                id_list 	list 	团购单ID列表
                id_list.deal_id 	string 	团购单ID
                id_list.status 	int 	当前状态，0：已下线，1：新上线，2：内容变更
                id_list.city 	string 	团购单所在的城市
                id_list.update_time 	string 	当前团单的变更具体时间戳
                additional_info.update_time 	string 	当前数据的变更时间戳，建议作为下一次变更数据查询的开始时间
         */
        'deal/get_incremental_id_list' => 'http://api.dianping.com/v1/deal/get_incremental_id_list',

        // 搜索商户
        'business/find_businesses' => 'http://api.dianping.com/v1/business/find_businesses',

        ##############
        # 预订类API：
        ##############
        // 搜索支持在线预订的商户
        'reservation/find_businesses_with_reservations' => 'http://api.dianping.com/v1/reservation/find_businesses_with_reservations',

        /*
         *获取支持在线预订的全部商户ID列表
         *
            请求参数

              必选参数
                名称 	类型 	说明
                appkey 	string 	App Key，应用的唯一标识
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
                city 	string 	包含团购信息的城市名称，可选范围见相关API返回结果
              可选参数
                名称 	类型 	说明
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的ID数量
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                id_list 	list 	商户ID列表
         */
        'reservation/get_all_id_list' => 'http://api.dianping.com/v1/reservation/get_all_id_list',

        /*
         *批量获取支持在线预订的商户信息
         *
            请求参数

              必选参数
                名称 	类型 	说明
                appkey 	string 	App Key，应用的唯一标识
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
                business_ids 	string 	一个或多个商户ID集合，多ID之间以英文逗号分隔，如“4659232,5257123,5185318”，一次传入的ID数量上限为40个，其他参数限制请参考下方注意事项
              可选参数
                名称 	类型 	说明
                out_offset_type 	int 	传出经纬度偏移类型，1:高德坐标系偏移，2:图吧坐标系偏移，如不传入，默认值为1
                platform 	int 	传出链接类型，1:web站链接（适用于网页应用），2:HTML5站链接（适用于移动应用和联网车载应用），如不传入，默认值为1
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的商户数量（调用此API，返回值恒为1）
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                business_id 	int 	商户ID
                name 	string 	商户名
                branch_name 	string 	分店名
                address 	string 	地址
                telephone 	string 	带区号的电话
                city 	string 	所在城市
                regions 	list 	所在区域信息列表，如[徐汇区，徐家汇]
                categories 	list 	所属分类信息列表，如[宁波菜，婚宴酒店]
                latitude 	float 	纬度坐标
                longitude 	float 	经度坐标
                photo_url 	string 	照片链接，照片最大尺寸700×700
                s_photo_url 	string 	小尺寸照片链接，照片最大尺寸278×200
                has_online_reservation 	int 	是否支持在线预订，0:没有，1:有
                online_reservation_url 	string 	在线预订WWW站点页面链接
                online_reservation_h5_url 	string 	在线预订页面HTML5站点链接
         */
        'reservation/get_batch_businesses_with_reservations_by_id' => 'http://api.dianping.com/v1/reservation/get_batch_businesses_with_reservations_by_id',

        ######################
        # 优惠券类API：
        ######################
        // 搜索优惠券
        'coupon/find_coupons' => 'http://api.dianping.com/v1/coupon/find_coupons',

        /*
         *获取指定优惠券信息
         *
            请求参数

              必选参数
                名称 	类型 	说明
                appkey 	string 	App Key，应用的唯一标识
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
                coupon_id 	int 	优惠券ID
              可选参数
                名称 	类型 	说明
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的单页优惠券数量
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                coupon_id 	int 	优惠券ID
                title 	string 	优惠券标题
                description 	string 	优惠券描述
                regions 	list 	优惠券适用商户所在行政区
                categories 	list 	优惠券所属分类
                download_count 	int 	优惠券当前已下载量
                publish_date 	string 	优惠券发布上线日期
                expiration_date 	string 	优惠券的截止使用日期
                logo_img_url 	string 	优惠券的图标，尺寸120＊90
                coupon_url 	string 	优惠券Web页面链接，适用于网页应用
                coupon_h5_url 	string 	优惠券HTML5页面链接，适用于移动应用和联网车载应用
                businesses 	list 	优惠券所适用的商户列表
                businesses.name 	string 	商户名
                businesses.id 	int 	商户ID
                businesses.url 	string 	商户Web页面链接，适用于网页应用
                businesses.h5_url 	string 	商户HTML5页面链接，适用于移动应用和联网车载应用
         */
        'coupon/get_single_coupon' => 'http://api.dianping.com/v1/coupon/get_single_coupon',

        #################
        # 元数据类API：
        #################
        // 获取支持商户搜索的最新城市列表
        'metadata/get_cities_with_businesses' => 'http://api.dianping.com/v1/metadata/get_cities_with_businesses',

        // 获取支持商户搜索的最新城市下属区域列表
        'metadata/get_regions_with_businesses' => 'http://api.dianping.com/v1/metadata/get_regions_with_businesses',

        // 获取支持商户搜索的最新分类列表
        'metadata/get_categories_with_businesses' => 'http://api.dianping.com/v1/metadata/get_categories_with_businesses',

        // 获取支持团购搜索的最新城市列表
        'metadata/get_cities_with_deals' => 'http://api.dianping.com/v1/metadata/get_cities_with_deals',

        // 获取支持团购搜索的最新城市下属区域列表
        'metadata/get_regions_with_deals' => 'http://api.dianping.com/v1/metadata/get_regions_with_deals',

        // 获取支持团购搜索的最新分类列表
        'metadata/get_categories_with_deals' => 'http://api.dianping.com/v1/metadata/get_categories_with_deals',

        // 获取支持优惠券搜索的最新城市列表
        'metadata/get_cities_with_coupons' => 'http://api.dianping.com/v1/metadata/get_cities_with_coupons',

        // 获取支持优惠券搜索的最新城市下属区域列表
        'metadata/get_regions_with_coupons' => 'http://api.dianping.com/v1/metadata/get_regions_with_coupons',

        // 获取支持优惠券搜索的最新分类列表
        'metadata/get_categories_with_coupons' => 'http://api.dianping.com/v1/metadata/get_categories_with_coupons',

        // 获取支持在线预订搜索的最新分类列表
        'metadata/get_categories_with_online_reservations' => 'http://api.dianping.com/v1/metadata/get_categories_with_online_reservations',

        // 获取支持在线预订商户搜索的最新城市下属区域列表
        'metadata/get_regions_with_online_reservations' => 'http://api.dianping.com/v1/metadata/get_regions_with_online_reservations',

        // 获取支持在线预订的最新城市列表
        'metadata/get_cities_with_online_reservations' => 'http://api.dianping.com/v1/metadata/get_cities_with_online_reservations',

        ################
        # 数据统计类API：
        ################
        /*
         *获取应用导入的团购交易的历史记录
         *
            请求参数

              必选参数
                名称 	类型 	说明
                sign 	string 	请求签名，生成方式见《API请求签名生成文档》
                begin_time 	string 	查询起始日期，格式为“YYYY-MM-DD HH:MM:SS”，其中HH代表24小时制
                end_time 	string 	查询结束日期，格式为“YYYY-MM-DD HH:MM:SS”，其中HH代表24小时制
                transaction_status 	int 	交易状态，1:用户下单，2:用户付费，3:退款，4:验券
                appkey 	string 	App Key，应用的唯一标识
              可选参数
                名称 	类型 	说明
                city 	string 	包含团购信息的城市名称，可以按城市为单位过滤结果，可选范围见相关API返回结果
                deal_id 	string 	团购ID，可以按单个团购为单位过滤结果
                uid 	string 	合作方在链接后加上的明文传输的自定义参数,使用方法如：注意事项．
                limit 	int 	每页返回的记录条目数上限，最小值1，最大值40，如不传入默认为40
                page 	int 	页码，如不传入默认为1，即第一页
                format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

            返回结果

              状态字段
                名称 	类型 	说明
                status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
                count 	int 	本次API访问所获取的单页记录数量
                total_count 	int 	所有页面记录总数
              结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
                名称 	类型 	说明
                update_time 	string 	交易状态更新时间
                deal_id 	string 	团购ID
                order_id 	int 	订单号
                unit_price 	float 	团购单价
                transaction_count 	int 	交易下单量
                transaction_amount 	float 	团购订单总价
                transaction_status 	int 	交易状态，1:用户下单，2:用户付费，3:退款，4:验券
                uid 	string 	第三方应用用户的标识，使用方法如：注意事项．
                commission_ratio 	float 	当前团单的佣金比例
         */
        'stats/get_deal_transaction_history' => 'http://api.dianping.com/v1/stats/get_deal_transaction_history',
        );

    protected $params = null;

    public function __construct ($appKey, $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
    }

    public function getSignature()
    {
        //按照参数名排序
        ksort($this->params);
        reset($this->params);
        //连接待加密的字符串
        $codes = $this->appKey;
        while (list($key, $val) = each($this->params)) {
            $codes .=($key.$val);
        }
        $codes .= $this->appSecret;
        $sign = strtoupper(sha1($codes));

        return $sign;
    }

    public function getQueryString()
    {
        //按照参数名排序
        ksort($this->params);
        reset($this->params);
        //请求的URL参数
        $queryString = '';
        while (list($key, $val) = each($this->params)) {
            $queryString .= ('&'.$key.'='.urlencode($val));
        }
        $sign = $this->getSignature();

        $queryString = '?appkey='. $this->appKey . '&sign=' . $sign. $queryString;

        return $queryString;
    }

    public function setCmdName($cmdName)
    {
        $this->cmdName = $cmdName;

        return $this;
    }

    public function callApi($cmdName, $params=array())
    {
        settype($params, 'array');
        $this->cmdName = $cmdName;
        $this->params = $params;

        $url = $this->getRequestUrl($cmdName);
        echo $url;
        $httpClientModel = $this->getHttpClient($url);
        $response = $httpClientModel->request('GET');

        if(200!=$response->getStatus()) {
            return new ErrorDesc(ErrorDesc::ERR_HTTP_RESPONSE_CODE_ERROR);
        }
        $response = @ ConvertFormat::json_decode($response->getBody());
        if(isset($response->error)) {
            $response = $this->_parseDianpingError($response);
        }

        return $response;
    }

    protected function _parseDianpingError($response)
    {
        $errorCode = null;

        switch($response->error->errorCode) {
            case 10001:
            case 10002:
            case 10003:
            case 10004:
            case 10005:
            case 10006:
            case 10007:
            case 10008:
            case 10009:
            case 10010:
            case 10011:
            case 10012:
            case 10013:
            case 10014:
            case 10015:
            case 10016:
            case 10017:
            case 10018:
            case 10019:
            case 10020:
            case 10021:
            case 10022:
            case 10023:
                $errorCode = $response->error->errorCode + 20000;
                break;

            case 100:
                $errorCode = 39901;
                break;
            case 101:
                $errorCode = 39902;

            default:
                break;
        }

        if($errorCode) {
            $error = new ErrorDesc($errorCode);
            if(isset($response->error->errorMessage)) {
                $error->setErrorDesc($response->error->errorMessage);
            }
            return $error;
        }

        return null;
    }

    protected function getHttpClient ($url=null)
    {
        if(function_exists('curl_init')) {
            $httpRequestConfig = array('ssltransport' => 'tls',
                                       'adapter'=>'Zend_Http_Client_Adapter_Curl',
                                       'curloptions'=>array(CURLOPT_SSL_VERIFYPEER=>false));
        } else {
            $httpRequestConfig = array('adapter'=>'Zend_Http_Client_Adapter_Socket',);
        }
        $client = new Zend_Http_Client($url, $httpRequestConfig);

        return $client;
    }

    public function getRequestUrl ($requestType)
    {
        $url = null;

        $urlList = array_change_key_case($this->urlList);
        $requestType = strtolower($requestType);
        if(isset($urlList[$requestType])) {
            $url = $urlList[$requestType] . $this->getQueryString();
        }

        return $url;
    }

    public function setRequestUrl ($requestType, $url)
    {
        if(isset($this->urlList[$requestType])) {
            $this->urlList[$requestType] = $url;
        }

        return $this;
    }

    /**
     *获取支持团购搜索的最新城市下属区域列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    city 	string 	城市名称，可选范围见相关API返回结果
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持团购搜索的最新城市列表
    districts 	list 	支持团购搜索的最新城市下属行政区列表
    neighborhoods 	list 	支持团购搜索的最新行政区下属商区列表
    */
    public function getAllCitiesAndDistrictsForGroupon($city=null)
    {
        $params = array();
        if($city) {
            $params['city'] = $city;
        }
        return $this->callApi('metadata/get_regions_with_deals', $params);
    }

    /**
     *获取支持商户搜索的最新城市列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持商户搜索的最新城市列表
    */
    public function getAllCitiesForRetail()
    {
        return $this->callApi('metadata/get_cities_with_businesses');
    }

    /**
     * 获取支持商户搜索的最新城市下属区域列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    city 	string 	城市名称，可选范围见相关API返回结果
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持商户搜索的最新城市列表
    districts 	list 	支持商户搜索的最新城市下属行政区列表
    neighborhoods 	list 	支持商户搜索的最新行政区下属商区列表
    */
    public function getAllCitiesAndDistrictsForRetail($city=null)
    {
        $params = array();
        if($city) {
            $params['city'] = $city;
        }
        return $this->callApi('metadata/get_regions_with_businesses', $params);
    }

    /**
     *获取支持商户搜索的最新分类列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json
    city 	string 	城市名称，可选范围见相关API返回结果

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    categories 	list 	支持商户搜索的最新大分类列表
    categories.category_name 	string 	分类名称
    categories.subcategoriess 	list 	支持商户搜索的最新大分类下属小分类列表
    */
    public function getRetailCatetory($city=null)
    {
        $params = array();
        if($city) {
            $params['city'] = $city;
        }
        return $this->callApi('metadata/get_categories_with_businesses', $params);
    }

    /**
     *获取支持团购搜索的最新城市列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持团购搜索的最新城市列表
    */
    public function getGrouponCities()
    {
        return $this->callApi('metadata/get_cities_with_deals');
    }

    /**
     *获取支持团购搜索的最新分类列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    categories 	list 	支持团购搜索的最新大分类列表
    subcategories 	list 	支持团购搜索的最新大分类下属小分类列表
    */
    public function getGrouponCatetory()
    {
        return $this->callApi('metadata/get_categories_with_deals', $params);
    }

    /**
     *获取支持优惠券搜索的最新城市列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持优惠券搜索的最新城市列表
    */
    public function getCouponsCities()
    {
        return $this->callApi('metadata/get_cities_with_coupons');
    }
    /**
     *获取支持优惠券搜索的最新城市下属区域列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    city 	string 	城市名称，可选范围见相关API返回结果
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持优惠券搜索的最新城市列表
    districts 	list 	支持优惠券搜索的最新城市下属行政区列表
    neighborhoods 	list 	支持优惠券搜索的最新行政区下属商区列表
    */
    public function getAllCitiesAndDistrictsForCoupons($city=null)
    {
        $params = array();
        if($city) {
            $params['city'] = $city;
        }
        return $this->callApi('metadata/get_regions_with_coupons', $params);
    }

    /**
     *获取支持优惠券搜索的最新分类列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    categories 	list 	支持优惠券搜索的最新大分类列表
    subcategories 	list 	支持优惠券搜索的最新大分类下属小分类列表
    */
    public function getCouponsCatetory()
    {
        return $this->callApi('metadata/get_categories_with_coupons', $params);
    }
    /**
     *获取支持在线预订搜索的最新分类列表
     *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    categories 	list 	支持在线预订搜索的最新大分类列表
    subcategories 	list 	支持在线预订搜索的最新大分类下属小分类列表
    */
    public function getBookingCategory()
    {
        return $this->callApi('metadata/get_categories_with_online_reservations');
    }
    /**
     *获取支持在线预订商户搜索的最新城市下属区域列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    city 	string 	城市名称，可选范围见相关API返回结果
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持在线预订商户搜索的最新城市列表
    districts 	list 	支持在线预订商户搜索的最新城市下属行政区列表
    neighborhoods 	list 	支持在线预订商户搜索的最新行政区下属商区列表
    */
    public function getAllCitiesAndDistrictsForBooking($city=null)
    {
        $params = array();
        if($city) {
            $params['city'] = $city;
        }
        return $this->callApi('metadata/get_regions_with_online_reservations', $params);
    }

    /**
     *获取支持在线预订的最新城市列表
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    cities 	list 	支持在线预订的最新城市列表
    */
    public function getBookingCities()
    {
        return $this->callApi('metadata/get_cities_with_online_reservations');
    }

    /**
     * 获取当前在线的全部团购ID列表
     * <pre>
     请求参数

     必选参数
     名称 	类型 	说明
     appkey 	string 	App Key，应用的唯一标识
     sign 	string 	请求签名，生成方式见《API请求签名生成文档》
     city 	string 	包含团购信息的城市名称，可选范围见相关API返回结果
     可选参数
     名称 	类型 	说明
     format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json
     category 	string 	包含团购信息的分类名，支持多个category合并查询，多个category用逗号分割。可选范围见相关API返回结果

     返回结果

     状态字段
     名称 	类型 	说明
     status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
     count 	int 	本次API访问所获取的单页团购ID数量
     结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
     名称 	类型 	说明
     id_list 	list 	团购单ID列表
     *</pre>
     *
     * @param string $city The city name
     * @param array $categoryList The category list
     *
     * 'deal/get_all_id_list' => 'http://api.dianping.com/v1/deal/get_all_id_list',
     *
     */
    public function getAllGrouponIds($city, $categoryList=array())
    {
        $params = array('city'=>$city);
        settype($categoryList, 'array');
        if($categoryList) {
            $params['category'] = join(',', $categoryList);
        }

        return $this->callApi('deal/get_all_id_list', $params);
    }

    /**
     * 获取每日新增团购ID列表
     请求参数

     必选参数
     名称 	类型 	说明
     appkey 	string 	App Key，应用的唯一标识
     sign 	string 	请求签名，生成方式见《API请求签名生成文档》
     city 	string 	包含团购信息的城市名称，可选范围见相关API返回结果
     date 	string 	查询日期，格式为“YYYY-MM-DD”
     可选参数
     名称 	类型 	说明
     category 	string 	包含团购信息的分类名，支持多个category合并查询，多个category用逗号分割。可选范围见相关API返回结果
     format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

     返回结果

     状态字段
     名称 	类型 	说明
     status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
     count 	int 	本次API访问所获取的单页团购ID数量
     结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
     名称 	类型 	说明
     id_list 	list 	团购单ID列表
     */
    public function getDailyNewGrouponIds($city, $date=null, $categoryList=array())
    {
        $params = array('city'=>$city);
        $params['date'] = $date==null ? date('Y-m-d') : $date;
        settype($categoryList, 'array');
        if($categoryList) {
            $params['category'] = join(',', $categoryList);
        }

        return $this->callApi('deal/get_daily_new_id_list', $params);
    }

    /**
     *获取指定团购信息
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    deal_id 	string 	团购ID
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的单页团购数量
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    deal_id 	string 	团购单ID
    title 	string 	团购标题
    description 	string 	团购描述
    city 	string 	城市名称，city为＂全国＂表示全国单，其他为本地单，城市范围见相关API返回结果
    list_price 	float 	团购包含商品原价值
    current_price 	float 	团购价格
    regions 	list 	团购适用商户所在商区
    categories 	list 	团购所属分类
    purchase_count 	int 	团购当前已购买数
    publish_date 	string 	团购发布上线日期
    details 	string 	团购详情
    purchase_deadline 	string 	团购单的截止购买日期
    image_url 	string 	团购图片链接，最大图片尺寸450×280
    s_image_url 	string 	小尺寸团购图片链接，最大图片尺寸160×100
    more_image_urls 	list 	更多大尺寸图片
    more_s_image_urls 	list 	更多小尺寸图片
    is_popular 	int 	是否为热门团购，0：不是，1：是
    restrictions 	list 	团购限制条件
    restrictions.is_reservation_required 	int 	是否需要预约，0：不是，1：是
    restrictions.is_refundable 	int 	是否支持随时退款，0：不是，1：是
    restrictions.special_tips 	string 	附加信息(一般为团购信息的特别提示)
    notice 	string 	重要通知(一般为团购信息的临时变更)
    deal_url 	string 	团购Web页面链接，适用于网页应用
    deal_h5_url 	string 	团购HTML5页面链接，适用于移动应用和联网车载应用
    commission_ratio 	float 	当前团单的佣金比例
    businesses 	list 	团购所适用的商户列表
    businesses.name 	string 	商户名
    businesses.id 	int 	商户ID
    businesses.address 	string 	商户地址
    businesses.latitude 	float 	商户纬度
    businesses.longitude 	float 	商户经度
    businesses.url 	string 	商户页链接
    */
    public function getGrouponById($grouponId)
    {
        $params = array('deal_id'=>$grouponId);

        return $this->callApi('deal/get_single_deal', $params);
    }

    /**
     *获取指定商户的团购信息
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    city 	string 	包含团购信息的城市名称，可选范围见相关API返回结果
    business_id 	int 	商户ID
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的单页团购数量
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    deal_id 	string 	团购单ID
    title 	string 	团购标题
    description 	string 	团购描述
    city 	string 	城市名称，city为＂全国＂表示全国单，其他为本地单，城市范围见相关API返回结果
    list_price 	float 	团购包含商品原价值
    current_price 	float 	团购价格
    regions 	list 	团购适用商户所在商区
    categories 	list 	团购所属分类
    purchase_count 	int 	团购当前已购买数
    publish_date 	string 	团购发布上线日期
    details 	string 	团购详情
    purchase_deadline 	string 	团购单的截止购买日期
    image_url 	string 	团购图片链接，最大图片尺寸450×280
    s_image_url 	string 	小尺寸团购图片链接，最大图片尺寸160×100
    more_image_urls 	list 	更多大尺寸图片
    more_s_image_urls 	list 	更多小尺寸图片
    is_popular 	int 	是否为热门团购，0：不是，1：是
    restrictions 	list 	团购限制条件
    restrictions.is_reservation_required 	int 	是否需要预约，0：不是，1：是
    restrictions.is_refundable 	int 	是否支持随时退款，0：不是，1：是
    restrictions.special_tips 	string 	特别说明
    notice 	string 	重要通知(一般为团购信息的临时变更)
    deal_url 	string 	团购Web页面链接，适用于网页应用
    deal_h5_url 	string 	团购HTML5页面链接，适用于移动应用和联网车载应用
    commission_ratio 	float 	当前团单的佣金比例
    businesses 	list 	团购所适用的商户列表
    businesses.name 	string 	商户名
    businesses.id 	int 	商户ID
    businesses.address 	string 	商户地址
    businesses.latitude 	float 	商户纬度
    businesses.longitude 	float 	商户经度
    businesses.url 	string 	商户页链接
    */
    public function getGrouponByRetailIdAndCity($retailId, $city)
    {
        $params = array('city'=>$city, 'business_id'=>$retailId);

        return $this->callApi('deal/get_deals_by_business_id', $params);
    }

    /**
     *搜索团购
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    city 	string 	包含团购信息的城市名称，可选范围见相关API返回结果
    可选参数
    名称 	类型 	说明
    latitude 	float 	纬度坐标，须与经度坐标同时传入
    longitude 	float 	经度坐标，须与纬度坐标同时传入
    radius 	int 	搜索半径，单位为米，最小值1，最大值5000，如不传入默认为1000
    region 	string 	包含团购信息的城市区域名，可选范围见相关API返回结果（不含返回结果中包括的城市名称信息）
    category 	string 	包含团购信息的分类名，支持多个category合并查询，多个category用逗号分割。可选范围见相关API返回结果
    is_local 	int 	根据是否是本地单来筛选返回的团购，1:是，0:不是
    keyword 	string 	关键词，搜索范围包括商户名、商品名、地址等
    sort 	int 	结果排序，1:默认，2:价格低优先，3:价格高优先，4:购买人数多优先，5:最新发布优先，6:即将结束优先，7:离经纬度坐标距离近优先
    limit 	int 	每页返回的团单结果条目数上限，最小值1，最大值40，如不传入默认为20
    page 	int 	页码，如不传入默认为1，即第一页
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的单页团购数量
    total_count 	int 	所有页面团购总数
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    deal_id 	string 	团购单ID
    title 	string 	团购标题
    description 	string 	团购描述
    city 	string 	城市名称，city为＂全国＂表示全国单，其他为本地单，城市范围见相关API返回结果
    list_price 	float 	团购包含商品原价值
    current_price 	float 	团购价格
    regions 	list 	团购适用商户所在行政区
    categories 	list 	团购所属分类
    purchase_count 	int 	团购当前已购买数
    publish_date 	string 	团购发布上线日期
    purchase_deadline 	string 	团购单的截止购买日期
    distance 	int 	团购单所适用商户中距离参数坐标点最近的一家与坐标点的距离，单位为米；如不传入经纬度坐标，结果为-1；如团购单无关联商户，结果为MAXINT
    image_url 	string 	团购图片链接，最大图片尺寸450×280
    s_image_url 	string 	小尺寸团购图片链接，最大图片尺寸160×100
    deal_url 	string 	团购Web页面链接，适用于网页应用
    deal_h5_url 	string 	团购HTML5页面链接，适用于移动应用和联网车载应用
    commission_ratio 	float 	当前团单的佣金比例
    businesses 	list 	团购所适用的商户列表
    businesses.name 	string 	商户名
    businesses.id 	int 	商户ID
    businesses.url 	string 	商户页链接
    */
    public function getGrouponByCityAndConditions($city, $conditions)
    {
        $validParams = array('latitude','longitude', 'radius',
                             'region', 'category', 'is_local',
                             'keyword', 'sort', 'limit',
                             'page');
        $params = array('city'=>$city);
        foreach($validParams as $v) {
            if(!isset($conditions[$v])) continue;

            if(is_array($conditions[$v])) $conditions[$v] = join(',', $conditions);

            $params[$v] = $conditions[$v];
        }

        return $this->callApi('deal/find_deals', $params);
    }

    /**
     *搜索商户
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    可选参数
    名称 	类型 	说明
    latitude 	float 	纬度坐标，须与经度坐标同时传入，与城市名称二者必选其一传入
    longitude 	float 	经度坐标，须与纬度坐标同时传入，与城市名称二者必选其一传入
    offset_type 	int 	偏移类型，0:未偏移，1:高德坐标系偏移，2:图吧坐标系偏移，如不传入，默认值为0
    radius 	int 	搜索半径，单位为米，最小值1，最大值5000，如不传入默认为1000
    city 	string 	城市名称，可选范围见相关API返回结果，与经纬度坐标二者必选其一传入
    region 	string 	城市区域名，可选范围见相关API返回结果（不含返回结果中包括的城市名称信息），如传入城市区域名，则城市名称必须传入
    category 	string 	分类名，可选范围见相关API返回结果；支持同时输入多个分类，以逗号分隔，最大不超过5个。
    keyword 	string 	关键词，搜索范围包括商户名、地址、标签等
    out_offset_type 	int 	传出经纬度偏移类型，1:高德坐标系偏移，2:图吧坐标系偏移，如不传入，默认值为1
    platform 	int 	传出链接类型，1:web站链接（适用于网页应用），2:HTML5站链接（适用于移动应用和联网车载应用），如不传入，默认值为1
    has_coupon 	int 	根据是否有优惠券来筛选返回的商户，1:有，0:没有
    has_deal 	int 	根据是否有团购来筛选返回的商户，1:有，0:没有
    has_online_reservation 	int 	根据是否支持在线预订来筛选返回的商户，1:有，0:没有
    sort 	int 	结果排序，1:默认，2:星级高优先，3:产品评价高优先，4:环境评价高优先，5:服务评价高优先，6:点评数量多优先，7:离传入经纬度坐标距离近优先，8:人均价格低优先，9：人均价格高优先
    limit 	int 	每页返回的商户结果条目数上限，最小值1，最大值40，如不传入默认为20
    page 	int 	页码，如不传入默认为1，即第一页
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的商户数量
    total_count 	int 	所有页面商户总数，最多为40条
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    business_id 	int 	商户ID
    name 	string 	商户名
    branch_name 	string 	分店名
    address 	string 	地址
    telephone 	string 	带区号的电话
    city 	string 	所在城市
    regions 	list 	所在区域信息列表，如[徐汇区，徐家汇]
    categories 	list 	所属分类信息列表，如[宁波菜，婚宴酒店]
    latitude 	float 	纬度坐标
    longitude 	float 	经度坐标
    avg_rating 	float 	星级评分，5.0代表五星，4.5代表四星半，依此类推
    rating_img_url 	string 	星级图片链接
    rating_s_img_url 	string 	小尺寸星级图片链接
    product_grade 	int 	产品/食品口味评价，1:一般，2:尚可，3:好，4:很好，5:非常好
    decoration_grade 	int 	环境评价，1:一般，2:尚可，3:好，4:很好，5:非常好
    service_grade 	int 	服务评价，1:一般，2:尚可，3:好，4:很好，5:非常好
    product_score 	float 	产品/食品口味评价单项分，精确到小数点后一位（十分制）
    decoration_score 	float 	环境评价单项分，精确到小数点后一位（十分制）
    service_score 	float 	服务评价单项分，精确到小数点后一位（十分制）
    avg_price 	int 	人均价格，单位:元，若没有人均，返回-1
    review_count 	int 	点评数量
    distance 	int 	商户与参数坐标的距离，单位为米，如不传入经纬度坐标，结果为-1
    business_url 	string 	商户页面链接
    photo_url 	string 	照片链接，照片最大尺寸700×700
    s_photo_url 	string 	小尺寸照片链接，照片最大尺寸278×200
    has_coupon 	int 	是否有优惠券，0:没有，1:有
    coupon_id 	int 	优惠券ID
    coupon_description 	string 	优惠券描述
    coupon_url 	string 	优惠券页面链接
    has_deal 	int 	是否有团购，0:没有，1:有
    deal_count 	int 	商户当前在线团购数量
    deals 	list 	团购列表
    deals.id 	string 	团购ID
    deals.description 	string 	团购描述
    deals.url 	string 	团购页面链接
    has_online_reservation 	int 	是否有在线预订，0:没有，1:有
    online_reservation_url 	string 	在线预订页面链接，目前仅返回HTML5站点链接
    */
    public function getRetailByConditions($conditions)
    {
        $validParams = array('latitude','longitude', 'offset_type', 'radius',
                             'city', 'region', 'category', 'out_offset_type',
                             'keyword', 'sort', 'limit', 'platform',
                             'page', 'has_coupon', 'has_deal', 'has_online_reservation',
                             );
        $params = array();
        foreach($validParams as $v) {
            if(!isset($conditions[$v])) continue;

            if(is_array($conditions[$v])) $conditions[$v] = join(',', $conditions);

            $params[$v] = $conditions[$v];
        }

        return $this->callApi('business/find_businesses', $params);
    }

    /**
     *搜索支持在线预订的商户
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    reservation_date 	string 	预订的日期，格式为“yyyy-MM-dd”
    reservation_time 	string 	预订的时间，格式为“HH:mm”，24小时制
    number_of_people 	int 	到店人数，最小为1，最大为50
    可选参数
    名称 	类型 	说明
    city 	string 	城市名称，可选范围见相关API返回结果，与经纬度坐标二者必选其一传入
    region 	string 	城市区域名，可选范围见相关API返回结果（不含返回结果中包括的城市名称信息），如传入城市区域名，则城市名称必须传入
    latitude 	float 	纬度坐标，须与经度坐标同时传入，与城市名称二者必选其一传入
    longitude 	float 	经度坐标，须与纬度坐标同时传入，与城市名称二者必选其一传入
    offset_type 	int 	偏移类型，0:未偏移，1:高德坐标系偏移，2:图吧坐标系偏移，如不传入，默认值为0
    radius 	int 	搜索半径，单位为米，最小值1，最大值5000，如不传入默认为1000
    category 	string 	分类名，可选范围见相关API返回结果；支持同时输入多个分类，以逗号分隔，最大不超过5个。
    keyword 	string 	关键词，搜索范围包括支持在线预订的商户名、地址、标签等
    out_offset_type 	int 	传出经纬度偏移类型，1:高德坐标系偏移，2:图吧坐标系偏移，如不传入，默认值为1
    platform 	int 	传出链接类型，1:web站链接（适用于网页应用），2:HTML5站链接（适用于移动应用和联网车载应用），如不传入，默认值为1
    sort 	int 	结果排序，1:默认，2:星级高优先，3:产品评价高优先，4:环境评价高优先，5:服务评价高优先，6:点评数量多优先，7:离传入经纬度坐标距离近优先，8:人均价格低优先，9：人均价格高优先
    limit 	int 	每页返回的支持在线预订的商户结果条目数上限，最小值1，最大值40，如不传入默认为20
    page 	int 	页码，如不传入默认为1，即第一页
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的支持在线预订的商户数量
    total_count 	int 	所有页面支持在线预订的商户总数
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    business_id 	int 	支持在线预订的商户ID
    name 	string 	支持在线预订的商户名
    branch_name 	string 	分店名
    address 	string 	地址
    telephone 	string 	带区号的电话
    city 	string 	所在城市
    regions 	list 	所在区域信息列表，如[徐汇区，徐家汇]
    categories 	list 	所属分类信息列表，如[宁波菜，婚宴酒店]
    latitude 	float 	纬度坐标
    longitude 	float 	经度坐标
    distance 	int 	支持在线预订的商户与参数坐标的距离，单位为米，如不传入经纬度坐标，结果为-1
    business_url 	string 	支持在线预订的商户页面链接
    photo_url 	string 	照片链接，照片最大尺寸700×700
    s_photo_url 	string 	小尺寸照片链接，照片最大尺寸278×200
    has_online_reservation 	int 	是否有在线预订，0:没有，1:有
    online_reservation_url 	string 	在线预订WWW站点页面链接
    online_reservation_h5_url 	string 	在线预订页面HTML5站点链接
    */
    public function getBookableRetail($bookDate, $bookTime, $peopleNumber, $conditions=array())
    {
        $validParams = array('latitude','longitude', 'offset_type', 'radius',
                             'city', 'region', 'category', 'out_offset_type',
                             'keyword', 'sort', 'limit', 'platform',
                             'page',
                             );
        $params = array('reservation_date'=>$bookDate,
                        'reservation_time'=>$bookTime,
                        'number_of_people'=>$peopleNumber);
        foreach($validParams as $v) {
            if(!isset($conditions[$v])) continue;

            if(is_array($conditions[$v])) $conditions[$v] = join(',', $conditions);

            $params[$v] = $conditions[$v];
        }

        return $this->callApi('reservation/find_businesses_with_reservations', $params);
    }

    /**
     *搜索优惠券
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    city 	string 	包含优惠券信息的城市名称，可选范围见相关API返回结果
    可选参数
    名称 	类型 	说明
    latitude 	float 	纬度坐标，须与经度坐标同时传入
    longitude 	float 	经度坐标，须与纬度坐标同时传入
    radius 	int 	搜索半径，单位为米，最小值1，最大值5000，如不传入，默认值为1000
    region 	string 	包含优惠券信息的城市区域名，可选范围见相关API返回结果（不含返回结果中包括的城市名称信息）
    category 	string 	包含优惠券信息的分类名，可选范围见相关API返回结果
    keyword 	string 	关键词，搜索范围包括商户名、优惠券名等
    sort 	int 	结果排序，1:最新发布优先，2:热门优惠优先，3:最优优惠优先，4:即将结束优先，5:离经纬度坐标距离近优先，如不传入，默认值为1
    limit 	int 	每页返回的团单结果条目数上限，最小值1，最大值40，如不传入默认为20
    page 	int 	页码，如不传入默认为1，即第一页
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的单页优惠券数量
    total_count 	int 	所有页面优惠券总数
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    coupon_id 	int 	优惠券ID
    title 	string 	优惠券标题
    description 	string 	优惠券描述
    regions 	list 	优惠券适用商户所在行政区
    categories 	list 	优惠券所属分类
    download_count 	int 	优惠券当前已下载量
    publish_date 	string 	优惠券发布上线日期
    expiration_date 	string 	优惠券的截止使用日期
    distance 	int 	优惠券所适用商户中距离参数坐标点最近的一家与坐标点的距离，单位为米；如不传入经纬度坐标，结果为-1；如优惠券无关联商户，结果为MAXINT
    logo_img_url 	string 	优惠券的图标，尺寸120＊90
    coupon_url 	string 	优惠券Web页面链接，适用于网页应用
    coupon_h5_url 	string 	优惠券HTML5页面链接，适用于移动应用和联网车载应用
    businesses 	list 	优惠券所适用的商户列表
    businesses.name 	string 	商户名
    businesses.id 	int 	商户ID
    businesses.url 	string 	商户页Web页面链接，适用于网页应用
    businesses.h5_url 	string 	商户HTML5页面链接，适用于移动应用和联网车载应用
    */
    public function getCouponsByConditions($city, $conditions=array())
    {
        $validParams = array('latitude','longitude', 'radius',
                             'region', 'category',
                             'keyword', 'sort', 'limit',
                             'page',
                             );
        $params = array('city'=>$city);
        foreach($validParams as $v) {
            if(!isset($conditions[$v])) continue;

            if(is_array($conditions[$v])) $conditions[$v] = join(',', $conditions);

            $params[$v] = $conditions[$v];
        }

        return $this->callApi('coupon/find_coupons', $params);
    }
    /**
     *获取指定优惠券信息
    *
    请求参数

    必选参数
    名称 	类型 	说明
    appkey 	string 	App Key，应用的唯一标识
    sign 	string 	请求签名，生成方式见《API请求签名生成文档》
    coupon_id 	int 	优惠券ID
    可选参数
    名称 	类型 	说明
    format 	string 	返回数据格式，可选值为json或xml，如不传入，默认值为json

    返回结果

    状态字段
    名称 	类型 	说明
    status 	string 	本次API访问状态，如果成功返回"OK"，并返回结果字段，如果失败返回"ERROR"，并返回错误说明
    count 	int 	本次API访问所获取的单页优惠券数量
    结果字段（接口返回商户列表，以下为单个商户信息集合中的返回字段说明）
    名称 	类型 	说明
    coupon_id 	int 	优惠券ID
    title 	string 	优惠券标题
    description 	string 	优惠券描述
    regions 	list 	优惠券适用商户所在行政区
    categories 	list 	优惠券所属分类
    download_count 	int 	优惠券当前已下载量
    publish_date 	string 	优惠券发布上线日期
    expiration_date 	string 	优惠券的截止使用日期
    logo_img_url 	string 	优惠券的图标，尺寸120＊90
    coupon_url 	string 	优惠券Web页面链接，适用于网页应用
    coupon_h5_url 	string 	优惠券HTML5页面链接，适用于移动应用和联网车载应用
    businesses 	list 	优惠券所适用的商户列表
    businesses.name 	string 	商户名
    businesses.id 	int 	商户ID
    businesses.url 	string 	商户Web页面链接，适用于网页应用
    businesses.h5_url 	string 	商户HTML5页面链接，适用于移动应用和联网车载应用
    */
    public function getCrouponById($couponId)
    {
        $params = array('coupon_id'=>$couponId);

        return $this->callApi('coupon/get_single_coupon', $params);
    }


}