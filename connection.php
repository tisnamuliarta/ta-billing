<?php

class Db {
    private static $instance = null;

    private function __construct(){}

    private function __clone(){}

    public function getInstance() {
        if(!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_MODE] = PDO::ERR_EXCEPTION;
            self::$instance = new PDO('mysql:host=localhost;dbname=diatmika','root','',$pdo_options);
        }
        return self::$instance;
    }
}