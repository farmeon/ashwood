<?php
namespace dbconn;

class Db{

    private static $instance;
    private $dsn = 'mysql:host=194.135.91.242;dbname=common_dbdm';
    private $username = 'common_dudm';
    private $password = 'Le8wwp5R8u';

    private function __construct(){}
    private function __clone(){}

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new Db();
        }
        return self::$instance;
    }

    public function getConnection(){

        $conn = null;

        try {
            $conn = new \PDO($this->dsn, $this->username, $this->password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            throw $e;

        }
        catch(Exception $e) {
            throw $e;
        }
    }
}