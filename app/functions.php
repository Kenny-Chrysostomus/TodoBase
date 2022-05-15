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
    $errors = [];

    //ユーザー名のチェック
    if(empty($datas['name'])) {
        $errors['name'] = 'ユーザー名を入力してください';
    }else if(mb_strlen($datas['name']) > 20) {
        $errors['name'] = '20文字以内で入力してください';
    }

    //パスワードのチェック（正規表現）
    if(empty($datas["password"])){
        $errors['password']  = "パスワードを入力してください";
    }else if(!preg_match('/\A[a-z\d]{8,100}+\z/i',$datas["password"])){
        $errors['password'] = "パスワードは8文字以上で入力してください";
    }
    //パスワード入力確認チェック（ユーザー新規登録時のみ使用）
    if($confirm){
        if(empty($datas["confirm_password"])){
            $errors['confirm_password']  = "パスワードを入力してください";
        }else if(empty($errors['password']) && ($datas["password"] != $datas["confirm_password"])){
            $errors['confirm_password'] = "パスワードが一致しません";
        }
    }

    return $errors;
}

//----------------------------------------------------------------------


function getTodos($pdo)
{
    $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC");
    return $stmt->fetchAll();
}

function addTodo($pdo)
{
    //filter_inputでデータを取得
    $title = trim(filter_input(INPUT_POST, 'title'));
    if($title === '') {
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO todos(title, user) VALUES (:title, :user)");
    $stmt->bindValue('title', $title, PDO::PARAM_STR);
    $stmt->bindValue('user', $_SESSION['name'], PDO::PARAM_STR);
    $stmt->execute();
}

function toggleTodo($pdo)
{
    $id = filter_input(INPUT_POST, 'id');
    if(empty($id)) {
        return;
    }

    $stmt = $pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
}