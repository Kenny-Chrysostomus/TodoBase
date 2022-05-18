<?php

class Token
{
    //セッションにトークンをセット
    //インスタンスを作って操作する処理ではないのでクラスから直接呼び出せるクラスメソッドにする
    public static function set()
    {
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['token'] = $token;
    }

    public static function check()
    {
        if(empty($_SESSION['token']) || ($_SESSION['token']) != $_POST['token']) {
            echo 'Invalid POST!'. PHP_EOL;
            exit;
        }
    }
}