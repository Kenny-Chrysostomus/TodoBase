<?php

session_start();

define('DSN', 'mysql:dbname=todo_base;host=localhost;charset=utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', 'root');

require_once(__DIR__ . "/Utils.php");
require_once(__DIR__ . "/Token.php");
require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/functions.php");