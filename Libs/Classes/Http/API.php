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
ob_start();
define('API_FILE_FOLDER', dirname(__FILE__) . '/API');
require_once('../config/config.php');
require_once(APPLICATION_ROOT_PATH . '/functions/function.inc');
require_once(APPLICATION_ROOT_PATH . '/functions/dbconnect.php');
require_once(APPLICATION_ROOT_PATH . '/functions/server_connect.php');
require_once(APPLICATION_ROOT_PATH . '/functions/Student.php');
require_once(APPLICATION_ROOT_PATH . '/functions/Card.php');
require_once(APPLICATION_ROOT_PATH . '/functions/Client.php');
require_once(APPLICATION_ROOT_PATH . '/functions/Workgroup.php');
require_once(APPLICATION_ROOT_PATH . '/functions/Server.php');
require_once(APPLICATION_ROOT_PATH . '/functions/Group.php');
require_once(APPLICATION_ROOT_PATH . '/functions/AppointmentPlus.php');
require_once(APPLICATION_ROOT_PATH . '/functions/HttpRequestHandler.php');
require_once(APPLICATION_ROOT_PATH . '/functions/SyncWithTmm.php');
require_once(APPLICATION_ROOT_PATH . '/functions/LogFile.class.php');
require_once(APPLICATION_ROOT_PATH . '/functions/ErrorDesc.php');
require_once(APPLICATION_ROOT_PATH . '/functions/HttpListener.php');

$httpListener = new HttpListener();
$return = $httpListener->listen();

ob_flush();
/* EOF */