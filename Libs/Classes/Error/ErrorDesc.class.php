<?php
/**
 * $Id$
 * $Revision$
 * $Author$
 * $LastChangedDate$
 *
 * @package
 * @version
 * @example $const = ErrorDesc::getErrorDesc(10000);
            echo $const;
 * @author Kilin WANG <zaixin.wang@tellmemore.cn>
 */
require_once(dirname(__FILE__) . '/../ClassAbstract.class.php');

final class ErrorDesc extends ClassAbstract
{
    ##########################################
    # 100~999  HTTP error
    ##########################################
    CONST ERR_BAD_REQUEST = 100;
    CONST ERR_DESC_100 = 'Bad request!';

    CONST ERR_HTTP_RESPONSE_ERROR = 101;
    CONST ERR_DESC_101 = 'Http responds error.';

    CONST ERR_HTTP_RESPONSE_CODE_ERROR = 102;
    CONST ERR_DESC_102 = 'Http responds error.';

    ##########################################
    # 10000~19999  Weixin error
    ##########################################

    ##########################################
    # 20000~29999  Yixin error
    ##########################################

    ##########################################
    # 30000~39999  Dianping.com error
    ##########################################
    // 请求超时，请稍后再试
    CONST ERR_DIANPING_HTTP_TIMEOUT_ERROR = 30001;
    CONST ERR_DESC_30001 = 'Request time out.';
    // 无App Key参数
    CONST ERR_DIANPING_NO_APP_KEY_ERROR = 30002;
    CONST ERR_DESC_30002 = 'Appkey is missing.';
    // App Key参数值无效
    CONST ERR_DIANPING_HTTP_APP_KEY_INVALID_ERROR = 30003;
    CONST ERR_DESC_30003 = 'Invalid appkey.';
    // 无Sign参数
    CONST ERR_DIANPING_HTTP_NO_SIGN_ERROR = 30004;
    CONST ERR_DESC_30004 = 'Sign is missing.';
    // Sign参数值无效
    CONST ERR_DIANPING_HTTP_SIGN_INVALID_ERROR = 30005;
    CONST ERR_DESC_30005 = 'Invalid sign.';
    // 无当前API访问权限
    CONST ERR_DIANPING_HTTP_API_UNACCESSIBLE_ERROR = 30006;
    CONST ERR_DESC_30006 = 'This API is not accessible.';
    // 当日API访问量已达到上限
    CONST ERR_DIANPING_HTTP_REACHED_LIMIT_ERROR = 30007;
    CONST ERR_DESC_30007 = 'You have reached the daily limit.';
    // App Key不可用（黑名单）
    CONST ERR_DIANPING_APP_KEY_NOT_AVAILABLE_ERROR = 30008;
    CONST ERR_DESC_30008 = 'Appkey is unavailable.';
    // API地址不存在
    CONST ERR_DIANPING_API_NOT_EXIST_ERROR = 30009;
    CONST ERR_DESC_30009 = 'API does not exist.';
    // 缺少必选请求参数
    CONST ERR_DIANPING_MISS_PARAM_ERROR = 30010;
    CONST ERR_DESC_30010 = 'Required parameter is missing.';
    // 请求参数值无效
    CONST ERR_DIANPING_PARAM_VALUE_INVALID_ERROR = 30011;
    CONST ERR_DESC_30011 = 'Parameter value is invalid.';
    // 请求参数无效
    CONST ERR_DIANPING_PARAM_INVALID_ERROR = 30012;
    CONST ERR_DESC_30012 = 'Parameter is invalid.';
    // 请求参数组合无效
    CONST ERR_DIANPING_PARAM_SET_INVALID_ERROR = 30013;
    CONST ERR_DESC_30013 = 'Parameters set is invalid.';
    // 请求IP无效
    CONST ERR_DIANPING_IP_INVALID_ERROR = 30014;
    CONST ERR_DESC_30014 = 'IP is invalid.';
    // 请求方法错误
    CONST ERR_DIANPING_METHOD_INVALID_ERROR = 30015;
    CONST ERR_DESC_30015 = 'Error method.';
    // 禁止访问
    CONST ERR_DIANPING_CAN_NOT_ACCESS_ERROR = 30016;
    CONST ERR_DESC_30016 = 'Access forbidden.';
    // 访问过于频繁
    CONST ERR_DIANPING_REQUEST_FREQUENT_ERROR = 30017;
    CONST ERR_DESC_30017 = 'You have exceeded the allowed frequency.';
    // 无效请求
    CONST ERR_DIANPING_INVALID_REQUEST_ERROR = 30018;
    CONST ERR_DESC_30018 = 'Invalid Request.';
    // HTTP Header 错误
    CONST ERR_DIANPING_HEADER_INVALID_ERROR = 30019;
    CONST ERR_DESC_30019 = 'Invalid request-header.';
    // 无效请求(包含非UTF-8编码)
    CONST ERR_DIANPING_INVALID_UTF8_ERROR = 30020;
    CONST ERR_DESC_30020 = 'Request contains invalid UTF-8 characters.';
    // 请求参数值的个数超过上限
    CONST ERR_DIANPING_MORE_PARAM_ALLOWED_ERROR = 30021;
    CONST ERR_DESC_30021 = 'Parameter contains more than {1} items: {0}. (请求参数值数量超过{1}上限: {0})';
    // 请求参数值的个数不足
    CONST ERR_DIANPING_LESS_PARAM_ALLOWED_ERROR = 30022;
    CONST ERR_DESC_30022 = 'Parameter contains less than {1} items: {0}.';
    // 请求参数值格式错误
    CONST ERR_DIANPING_PARAM_FORMAT_INVALID_ERROR = 30023;
    CONST ERR_DESC_30023 = 'Parameter value format invalid: {0}';
    // 未知异常
    CONST ERR_DIANPING_SERVICE_NOT_AVALABLE_ERROR = 39901;
    CONST ERR_DESC_39901 = 'API service is temporarily unavailable.';
    // 服务调用超时
    CONST ERR_DIANPING_SERVICE_TIMEOUT_ERROR = 39902;
    CONST ERR_DESC_39902 = 'API service timeout.';


    private $_errorCode = null;

    private $_errorDesc = '';

    static public function getInstance($errorCode)
    {
        $errorDescModel = new ErrorDesc($errorCode);

        return $errorDescModel;
    }

    public function __construct($errorCode)
    {
        $this->reset();
        $this->setErrorCode($errorCode);
    }

    public function reset()
    {
        $this->_errorCode = null;
        $this->_errorDesc = '';
    }

    public function setErrorCode($code)
    {
        if(self::getErrorDesc($code)) {
            $this->_errorCode = $code;
            $this->_errorDesc = self::getErrorDesc($code);
        }

        return $this;
    }

    public function setErrorDesc($errorDesc)
    {
        $this->_errorDesc = $errorDesc;

        return $this;
    }

    public function getError()
    {
        if($this->_errorCode) {
            $error = array('code'=>$this->_errorCode, 'desc'=>$this->_errorDesc);
        } else {
            $error = array();
        }

        return $error;
    }

    static public function getErrorDesc($errorCode)
    {
        eval('$errorDesc = @ self::ERR_DESC_'.$errorCode.';');
        if(!empty($errorDesc)) {
            return $errorDesc;
        }

        return null;
    }

    public function __toString()
    {
        $errorInfo = $this->getError();
        if(isset($errorInfo['desc'])) {
            return 'Error: ' . $errorInfo['code'] . ' - ' . $errorInfo['desc'];
        }

        return '';
    }
}

