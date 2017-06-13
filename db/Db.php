<?php

class Db {

    private static $connection;

    static $config = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    static function connect($host, $user, $password, $db) {
        if (!isset(self::$connection)) {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$db",
                $user,
                $password,
                self::$config
            );
        }
    }

    static function queryOne($statement, $params=array()) {
        $stmt = self::$connection->prepare($statement);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    static function queryAll($statement, $params=array()) {
        $stmt = self::$connection->prepare($statement);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    static function updateDelete($statement, $params=array()) {
        $stmt = self::$connection->prepare($statement);
        $stmt->execute($params);
    }



}