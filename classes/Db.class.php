<?php

    class Db
    {
        private static $conn;

        public static function getInstance(){
            if( is_null( self::$conn ) ){
                self::$conn = new PDO("mysql:host=localhost; dbname=imdstagram", "root", "");
            }
            return self::$conn;
        }
    }

?>
