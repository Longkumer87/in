<?php
class Database
{
    public function getDb()
    {

        $dbhost = "localhost";
        $dbname = "inventories";
        $dbusername = "admin";
        $dbpassword = "ghckb2024";

        try {
            $dsn = 'mysql:host=' . $dbhost .
                ';dbname=' . $dbname .
                ';charset=utf8';

            $conn= new PDO($dsn, $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
