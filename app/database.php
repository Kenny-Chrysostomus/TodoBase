<?php

class Database
{
    private static $pdo;

    //pdoインスタンスを返すメソッド
    public static function getInstance()
    {
        try {
            //pdoインスタンスを作るのが一回だけになるよう工夫
            if(!isset(self::$pdo)) {
                self::$pdo = new PDO(DSN, DB_USER, DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            }

            return self::$pdo;
        } catch(PDOException $e) {
            echo $e->getMessage();
            exit;
        }

    }
}