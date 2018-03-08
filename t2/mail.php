<?php

require_once(__DIR__.DIRECTORY_SEPARATOR.'src/classes/db.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'src/classes/User.php');

if(!empty($_GET['code']) && isset($_GET['code'])){
	session_start();
    $activization = $_GET['code'];
    $db = \dbconn\Db::getInstance();
    $dbconn = $db->getConnection();
    $user = new \Auth\User($dbconn);
    $user->updateStatus($activization);
    header('Location: /index.php');
}
