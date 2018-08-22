 <?php
header('Content-Type: application/json');
define('IN_APP', 1);
define('Debug', true);
Debug and error_reporting(0);
//include '../../vendor/autoload.php';
include 'Utils/Utils.php';
include 'ActiveRecord.php';
include 'config.php';
Utils::_load('Controller');
Utils::_load('Common');
MVC::_init();
