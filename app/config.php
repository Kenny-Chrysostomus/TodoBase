<?php

session_start();

define('DSN', 'mysql:dbname=todo_base;host=localhost;charset=utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// require_once(__DIR__ . "/Utils.php");
// require_once(__DIR__ . "/Token.php");
// require_once(__DIR__ . "/Database.php");
// require_once(__DIR__ . "/Todo.php");
require_once(__DIR__ . "/functions.php");

//require_onceでちまちま呼び出していたクラスを自動で読み込む
//読み込まれていないクラスが使われると、クラス名が無名関数の引数に渡されるので$classで受ける
spl_autoload_register(function ($class) {
    $fileName = sprintf(__DIR__ . '/%s.php', $class);

    if(file_exists($fileName)) {
        require($fileName);
    }else {
        echo 'File not found: ' . $fileName;
        exit;
    }
});