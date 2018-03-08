<?php
namespace Otv;

class Review{

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getList(){
        $res = $this->db->prepare("SELECT reviews.content, users.login FROM reviews JOIN users ON reviews.user_id = users.id");
        $res->execute();
        $row = $res->fetchAll();
        if (!$row) {
            return false;
        }
        return  $row;
    }

    public function isAuthorized(){
        if (!empty($_SESSION["ids"])) {
            return (bool) $_SESSION["ids"];
        }
        return false;
    }

    public function addPost($ids, $message){
        if($this->isAuthorized()){
            $sql = "INSERT INTO reviews (content, user_id) VALUES (?,?)";
            $res= $this->db->prepare($sql);
            $res->execute([$message, $ids]);
            return array('user_id' => $ids, 'message' => $message);
        }else{
            return false;
        }

    }

}