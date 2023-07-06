<?php

class BaseDao
{

    public $db;

    public function __construct()
    {
        try {

            $host = getenv('host');
            $user = getenv('user');
            $pass = getenv('pass');
            $schema = getenv('schema');
            $port = getenv('port');

            $this->db = new PDO("mysql:host=$host;port=$port;dbname=$schema", $user, $pass);

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
?>