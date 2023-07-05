<?php

class BaseDao
{

    public $db;

    public function __construct()
    {
        try {

            $host = 'sql7.freesqldatabase.com';
            $user = 'sql7630734';
            $pass = 'YD8ZFneYG9';
            $schema = 'sql7630734';
            $port = '3306';

            $this->db = new PDO("mysql:host=$host;port=$port;dbname=$schema", $user, $pass);

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
?>