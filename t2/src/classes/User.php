<?php
namespace Auth;

class User{

    private $id;
    private $login;
    private $email;
    private $db;

    private $is_authorized = false;

    public function __construct($db){
        $this->db = $db;
    }

    public static function isAuthorized(){
        if (!empty($_SESSION["ids"])) {
            return (bool) $_SESSION["ids"];
        }
        return false;
    }

    public function passwordHash($password){
        $hashPass =  md5($password);
        return array('password' => $password, 'hash' => $hashPass);
    }

    public function checkUser($username){
        $res = $this->db->prepare("SELECT id FROM users WHERE login = '$username'");
        $res->execute();
        $row = $res->fetch();
        if (!$row) {
            return false;
        }
        return true;
    }

    public function updateStatus($activation){
    	$res = $this->db->prepare("SELECT id FROM users WHERE activation = '$activation'");
        $res->execute();
        $row = $res->fetch();
        if ($row) {
	        $sql = "UPDATE users SET status = 1 WHERE activation=?";
			$stmt= $this->db->prepare($sql);
        	$stmt->execute([$activation]);
        	$_SESSION["ids"] = $row['id'];
			setcookie("ids", $row['id'], time()+3600);
			return true;

        }
	}

    public function create($username, $password, $email){

        $user_exists = $this->checkUser($username);
        if ($user_exists) {
            return false;
        }

        $password = $this->passwordHash($password);
        $activation = md5($email.time());
        $sql = "INSERT INTO users (login, password, email, activation) VALUES (?,?,?,?)";
        $res= $this->db->prepare($sql);
        $res->execute([$username, $password['hash'], $email, $activation]);
        $this->sendMail($email, $activation);
        return true;
    }

    public function logout(){
        if (!empty($_SESSION["ids"])) {
            unset($_SESSION["ids"]);
        }
    }

    public function saveSession($remember = false, $http_only = true, $days = 7){
        if ($remember) {
            $_SESSION["ids"] = $this->id;
            setcookie("ids", $this->id, time()+3600);
        }
    }

    public function authorize($username, $password){

        $user_exists = $this->checkUser($username);
        if (!$user_exists) {
            return false;
        }
        $password = $this->passwordHash($password);
        $hashPassword = $password['hash'];
        $res = $this->db->prepare("SELECT * FROM users WHERE login = '$username' AND status = 1 AND password = '$hashPassword'");
        $res->execute();
        $result = $res->fetch();
        $this->login = $result['login'];

        if (!$this->login) {
            $this->is_authorized = false;
        }else {
            $this->is_authorized = true;
            $this->id = $result['id'];
            $this->saveSession(true);
        }

        return $this->is_authorized;
    }

    public function getById($id){
        $res = $this->db->prepare("SELECT login FROM users WHERE id = '$id'");
        $res->execute();
        $result = $res->fetch();
        return $result['login'];
    }

    public function sendMail($mail, $activation){
        $host = $_SERVER['HTTP_HOST']; 
        $to  = $mail;
        //$url = "<a href='http://$host/src/mail.php?code=$activation'>Подтверждение регистрации</a>";
        $url = "<a href='http://$host/mail.php?code=$activation'>Подтверждение регистрации</a>";
        $subject = "Регистрация на сайте";

        $message = " 
            <html> 
                <head> 
                    <title>Регистрация на сайте</title> 
                </head> 
                <body> 
                    <p>". $url."</p> 
                </body> 
            </html>";

        $headers  = "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "From: <test@gmail.com>\r\n";
        mail($to, $subject, $message, $headers);
    }
}