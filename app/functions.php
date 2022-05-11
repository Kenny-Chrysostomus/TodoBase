<?php

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//セッションにトークンをセット
function setToken() {
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

function checkToken() {
    if(empty($_SESSION['token']) || ($_SESSION['token']) != $_POST['token']) {
        echo 'Invalid POST!'. PHP_EOL;
        exit;
    }
}

//postされた値のバリデーション
function validation($datas, $confirm = true) {
    
}