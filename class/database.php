<?php

class Database {

    private static $_conection = false;

    private function __construct(){
        try {
            $conectionStr = "mysql:host=".DB_SERVIDOR.";port=".DB_PUERTO.";dbname=".DB_BASEDATOS.";charset=utf8";
            self::$_conection  = new PDO($conectionStr, DB_USUARIO, DB_PASSWORD);
            self::$_conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_conection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error conecting to the database server: ".$e->getMessage());
        }
    }


    public static function getConection() {
        if (!self::$_conection) {
            new self;
        }
        return self::$_conection;
    }
}
