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

function studentCreationTest($apiUrl, $studentInfo)
{
    $httpHandler = new HttpRequestHandler();
    $url = $apiUrl;
    $requestString = '<DemoAccountCreation xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.datacontract.org/2004/07/Auralog.MobileGateway.Services.V1">
                <Email>'.$studentInfo['email'].'</Email>
                <FirstName>'.$studentInfo['firstname'].'</FirstName>
                <InterfaceLanguage>'.$studentInfo['interfaceLanguage'].'</InterfaceLanguage>
                <LastName>'.$studentInfo['lastname'].'</LastName>
                <Login>'.$studentInfo['login'].'</Login>
                <Password>'.$studentInfo['password'].'</Password>
                <ClassroomId>'.$studentInfo['classroomId'].'</ClassroomId>
                <DisciplineId>'.$studentInfo['learnLanguage'].'</DisciplineId>
                <UserId></UserId>
                <WorkgroupId>MyWorkgroupId</WorkgroupId>
                <WorkgroupDemoKey>'.$studentInfo['demoKey'].'</WorkgroupDemoKey>
                </DemoAccountCreation>
    ';
    //$response = $httpHandler->curlRequest($url, $requestString);
    //$httpCode = $httpHandler->getHttpResponseCode();

    //*
    $urlInfo = parse_url($apiUrl);
    $fp = @fsockopen($urlInfo['host'], 80, $errno, $errstr, 30);
    $out = "POST ".$urlInfo['path']." HTTP/1.1\r\n";
    $out .= "Host: ".$urlInfo['host']."\r\n";
    $out .= "Content-Type: text/xml\r\n";
    $out .= "Content-length: ".strlen($requestString)."\r\n";
    $out .= "Connection: Close\r\n\r\n";
    if($fp) {
        fwrite($fp, $out);
        fwrite($fp, $requestString);
        $return = '';
        while (!feof($fp)) {
            $return .= fgets($fp, 1024);
        }
        preg_match('/^http\/\d+\.\d+\s(\d+)/is', $return, $match);
        $return = explode("\r\n\r\n", $return);
        $response = isset($return[1]) ? $return[1] : '';
        $httpCode = $match[1];
        fclose($fp);
        $return = '<b>Response Code</b>:' . $httpCode . "\n<br/><b>Response Content</b>: \n<br/>" . htmlspecialchars($response);
    } else {
        $return = 'Failed to open the URL: <i>' . $apiUrl . '</i>';
    }
    //*/


    return $return;
}

$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : ('http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/API.php');
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : '';
$firstname = isset($_REQUEST['firstname']) ? $_REQUEST['firstname'] : '';
$lastname = isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : '';
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
$learnLanguage = isset($_REQUEST['learnLanguage']) ? $_REQUEST['learnLanguage'] : '';
$interfaceLanguage = isset($_REQUEST['interfaceLanguage']) ? $_REQUEST['interfaceLanguage'] : '';
$classroomId = isset($_REQUEST['classroomId']) ? $_REQUEST['classroomId'] : '';
$demoKey = isset($_REQUEST['demoKey']) ? $_REQUEST['demoKey'] : '';


?>
<SCRIPT language="javascript">
function submit_form()
{
        document.form1.submit();
}
</SCRIPT>
<?php
if(isset($_POST['login'], $_POST['email'])) {
    echo "<pre>Test Result:\n\n" . studentCreationTest($_POST['url'], $_POST) . '</pre>';
}
?>
<form name="form1" id="form1" method="post" action="">
 <br><input type="hidden" name="action" value="<?php echo $defaultAction;?>"/>
 <table>
 <tr><td>Service URL:</td><td>         <input name="url"   type="text" size="100" value="<?PHP echo $url; ?>"></input></td></tr>
 <tr><td>Login:</td><td>         <input name="login"   type="text" size="100" value="<?PHP echo $login; ?>"></input></td></tr>
 <tr><td>Firstname:</td><td>       <input name="firstname" type="text"  size="100" value="<?PHP echo $firstname; ?>"></input></td></tr>
 <tr><td>Lastname:</td><td>        <input name="lastname" type="text" size="100" value="<?PHP echo $lastname; ?>"></input></td></tr>
 <tr><td>Email:</td><td>         <input name="email" type="text" size="100" value="<?PHP echo $email;?>"></input></td></tr>
 <tr><td>Password:</td><td>         <input name="password" type="text" size="100" value="<?PHP echo $password;?>"></input></td></tr>
 <tr><td>Learning Language:</td><td>    <input name="learnLanguage" type="text" size="100" value="<?PHP echo $learnLanguage; ?>"></td></tr>
 <tr><td>Interface Language:</td><td>       <input name="interfaceLanguage" type="text" size="100" value="<?PHP echo $interfaceLanguage; ?>"></td></tr>
 <tr><td>Classroom ID:</td><td>      <input name="classroomId" type="text" size="100" value="<?PHP echo $classroomId; ?>"></td></tr>
 <tr><td>Demo Key:</td><td>        <input name="demoKey" type="text" size="100" value="<?PHP echo $demoKey; ?>"></td></tr>
 <tr>
    <td colspan=2>
      <input type="button" value="send" name="doTest" onclick="javascript:submit_form()">
    </td>
 </tr>
 </table>
</form>