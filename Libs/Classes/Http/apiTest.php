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
require_once('../config/config.php');
require_once(APPLICATION_ROOT_PATH . '/functions/HttpRequestHandler.php');

// test page association list.  'TestCase'=>'filePath'
$testItems = array(
                'StudentCreation' => './apiTest/studentCreationTest.php',
             );

if(isset($_REQUEST['action'], $testItems[$_REQUEST['action']])) {
    $defaultAction = $_REQUEST['action'];
} else if(1==count($testItems)) {
    list($defaultAction, ) = each($testItems);
} else {
    $defaultAction = null;
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $defaultAction ? ('Test '.$defaultAction) : 'Test Guide';?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<p><b><?php echo $defaultAction ? ('Test '.$defaultAction) : 'Test Guide';?></b></p>
<?php
if($defaultAction) {
    require_once($testItems[$defaultAction]);
} else {
    require_once('./apiTest/testGuide.php');
}
?>
</body>
</html>