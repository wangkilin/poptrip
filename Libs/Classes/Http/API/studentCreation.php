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
function StudentCreation(SimpleXMLElement $xmlInfo)
{
    $return = null;
    $studentInfo = (array)$xmlInfo;
    try {
        if(!isset($studentInfo['FirstName'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_FIRSTNAE_IS_INVALID), ErrorDesc::ERR_FIRSTNAE_IS_INVALID);
        }
        if(!isset($studentInfo['LastName'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_LASTNAME_IS_INVALID), ErrorDesc::ERR_LASTNAME_IS_INVALID);
        }
        if(!isset($studentInfo['Email'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_EMAIL_IS_INVALID), ErrorDesc::ERR_EMAIL_IS_INVALID);
        }
        if(!isset($studentInfo['Login'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_LOGIN_IS_INVALID), ErrorDesc::ERR_LOGIN_IS_INVALID);
        }
        if(!isset($studentInfo['Password'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_PASSWORD_IS_INVALID), ErrorDesc::ERR_PASSWORD_IS_INVALID);
        }
        if(!isset($studentInfo['ClassroomId'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_CLASSROOM_IS_INVALID), ErrorDesc::ERR_CLASSROOM_IS_INVALID);
        }
        if(!isset($studentInfo['DisciplineId'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_LEARNING_LANGUAGE_IS_INVALID), ErrorDesc::ERR_LEARNING_LANGUAGE_IS_INVALID);
        }
        if(!isset($studentInfo['InterfaceLanguage'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_INTERFACE_LANGUAGE_IS_INVALID), ErrorDesc::ERR_INTERFACE_LANGUAGE_IS_INVALID);
        }
        if(!isset($studentInfo['WorkgroupDemoKey'])) {
            throw new Exception(ErrorDesc::getErrorDesc(ErrorDesc::ERR_INVALID_DEMO_KEY), ErrorDesc::ERR_INVALID_DEMO_KEY);
        }
        $studentInfo = array(
                         'email'        => $studentInfo['Email'],
                         'learnLanguage'=> $studentInfo['DisciplineId'],
                         'firstname'    => (isset($studentInfo['FirstName']) ? $studentInfo['FirstName'] : ''),
                         'lastname'     => $studentInfo['LastName'],
                         'classroomId'  => $studentInfo['ClassroomId'],
                         'demoKey'      => $studentInfo['WorkgroupDemoKey'],
                         'login'        => $studentInfo['Login'],
                         'password'     => $studentInfo['Password'],
                         'interfaceLanguage' => $studentInfo['InterfaceLanguage']
                   );
        $studentModel = new Student();
        $resultInfo = $studentModel->createStudent($studentInfo);
    } catch (Exception $e) {
        $resultInfo = $e->getCode();
    }
    if(is_array($resultInfo)) {
        $return = '<DemoAccountCreation xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.datacontract.org/2004/07/Auralog.MobileGateway.Services.V1">'."\n"
             .'<Email>'.$studentInfo['email'].'</Email>'."\n"
             .'<FirstName>'.$studentInfo['firstname'].'</FirstName>'."\n"
             .'<InterfaceLanguage>'.$studentInfo['interfaceLanguage'].'</InterfaceLanguage>'."\n"
             .'<LastName>'.$studentInfo['lastname'].'</LastName>'."\n"
             .'<Login>'.$studentInfo['login'].'</Login>'."\n"
             .'<Password>'.$studentInfo['password'].'</Password>'."\n"
             .'<ClassroomId>'.$studentInfo['classroomId'].'</ClassroomId>'."\n"
             .'<DisciplineId>'.$studentInfo['learnLanguage'].'</DisciplineId>'."\n"
             .'<UserId>'.$resultInfo['userId'].'</UserId>'."\n"
             .'<WorkgroupDemoKey>'.$studentInfo['demoKey'].'</WorkgroupDemoKey>'."\n"
             .'</DemoAccountCreation>'."\n";
    } else if(is_int($resultInfo)){
        switch($resultInfo) {
            case ErrorDesc::ERR_LOGIN_EXIST:
                $errorCode = 409;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_LOGIN_EXIST);
                break;

            case ErrorDesc::ERR_EMAIL_IS_DUPLICATE:
                $errorCode = 406;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_EMAIL_IS_DUPLICATE);
                break;

            case ErrorDesc::ERR_EMAIL_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_EMAIL_IS_INVALID);
                break;
            case ErrorDesc::ERR_LOGIN_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_LOGIN_IS_INVALID);
                break;
            case ErrorDesc::ERR_LOGIN_IS_TOO_LONG:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_LOGIN_IS_TOO_LONG);
                break;
            case ErrorDesc::ERR_PASSWORD_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_PASSWORD_IS_INVALID);
                break;
            case ErrorDesc::ERR_PASSWORD_IS_TOO_LONG:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_PASSWORD_IS_TOO_LONG);
                break;
            case ErrorDesc::ERR_INVALID_DEMO_KEY:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_INVALID_DEMO_KEY);
                break;
            case ErrorDesc::ERR_CLASSROOM_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_CLASSROOM_IS_INVALID);
                break;
            case ErrorDesc::ERR_INTERFACE_LANGUAGE_NOT_SUPPORT_BY_TMM:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_INTERFACE_LANGUAGE_NOT_SUPPORT_BY_TMM);
                break;
            case ErrorDesc::ERR_LEARNING_LANGUAGE_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_LEARNING_LANGUAGE_IS_INVALID);
                break;
            case ErrorDesc::ERR_INTERFACE_LANGUAGE_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_INTERFACE_LANGUAGE_IS_INVALID);
                break;
            case ErrorDesc::ERR_FIRSTNAE_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_FIRSTNAE_IS_INVALID);
                break;
            case ErrorDesc::ERR_LASTNAME_IS_INVALID:
                $errorCode = 400;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_LASTNAME_IS_INVALID);
                break;

            case ErrorDesc::ERR_API_RESPOND_ERROR:
                $errorCode = 500;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_API_RESPOND_ERROR);
                break;
            case ErrorDesc::ERR_API_RETURN_ERROR:
                $errorCode = 500;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_API_RETURN_ERROR);
                break;
            case ErrorDesc::ERR_API_APPOINTMENT_PLUS_ERROR:
                $errorCode = 500;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_API_APPOINTMENT_PLUS_ERROR);
                break;
            default:
                $errorCode = 500;
                $errorDesc = ErrorDesc::getErrorDesc(ErrorDesc::ERR_INTERNAL_ERROR);
                break;

        }
        switch($errorCode) {
            case 400:
                header("HTTP/1.0 400 Bad Request" , 1, $errorCode);
                break;
            case 406:
                header("HTTP/1.0 406 Not Acceptable", 1, $errorCode);
                break;
            case 409:
                header("HTTP/1.0 409 Conflict", 1, $errorCode);
                break;
            case 500:
            default:
                header("HTTP/1.0 500 Internal Server Error", 1, $errorCode);
                break;
        }
        exit($errorDesc);
        /*
        $return = '<result>'
        . '<errorcode>'.$errorCode.'</errorcode>'
        . '<errortext>'.$errorDesc.'</errortext>'
        . '</result>';
        // */
    }

    return $return;
}