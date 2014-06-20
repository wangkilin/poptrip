<?php
/**
 * $Id$
 * $Revision$
 * $Author$
 * $LastChangedDate$
 *
 * @package
 * @version
 * @author Kilin WANG <zaixin.wang@tellmemore.cn>
 */
class HttpListener
{
    private $httpResponser = null;
    public function __construct()
    {
    }

    public function listen()
    {
        $xmlInfo = @simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA']);
        if(!$xmlInfo) {
            return;
        }

        $requestName = $xmlInfo->getName();

        switch($requestName) {
            case 'DemoAccountCreation':
                require_once(API_FILE_FOLDER . '/studentCreation.php');
                $funcName = 'StudentCreation';
                break;

            default:
                break;
        }

        if(isset($funcName) && function_exists($funcName)) {
            $return = $funcName($xmlInfo);
        } else {
            $return = ErrorDesc::ERR_INTERNAL_ERROR;
        }

        if(is_string($return)) {
            echo $return;
        } else if(is_int($return)) {
            $errorDesc = new ErrorDesc($return);
            $errorDesc = $errorDesc->getError();
            if($errorDesc) {
                $return = '<result>'
                    . '<errorcode>'.$errorDesc['code'].'</errorcode>'
                    . '<errortext>'.$errorDesc['desc'].'</errortext>'
                    . '</result>';
                echo htmlspecialchars($return);
            }
        } else {
            echo 'No response';
        }
    }
}