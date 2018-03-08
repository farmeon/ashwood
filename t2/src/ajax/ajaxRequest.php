<?php
require_once('../classes/db.php');
require_once('../classes/User.php');
require_once('../classes/Review.php');

if (!empty($_COOKIE['login'])) {
    session_id($_COOKIE['login']);
}
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])){

    $db = \dbconn\Db::getInstance();
    $dbconn = $db->getConnection();
    $user = new \Auth\User($dbconn);

    switch($_POST['action']){
        case 'registration':
            $username = $_POST['usrname'];
            $password = $_POST['psw'];
            $email = $_POST['email'];
            $result = $user->create($username, $password, $email);
            echo json_encode($result);
            break;
        case 'authorization':
            $username = $_POST['usrname'];
            $password = $_POST['psw'];
            $result = $user->authorize($username, $password);
            echo json_encode($result);
            break;
        case 'logout':
            $result = $user->logout();
            break;
        case 'add_post':
            $user_id = $_POST['ids'];
            $message = $_POST['message'];
            $post = new \Otv\Review($dbconn);
            $result = $post->addPost($user_id, $message);
            $username = $user->getById($user_id);
            $result['username'] = $username;
            echo json_encode($result);
            break;
    }
}
