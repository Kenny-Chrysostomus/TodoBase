<?php

require_once "../app/database.php";
require_once "../app/functions.php";

session_start();

//既にログインしていたらリダイレクト
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.html");
    exit();
}

//postされてきたデータを格納
$datas = [
    'name' => '',
    'password' => '',
    'confirm_password' => ''
];
$login_err = "";

//get通信だったらセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    setToken();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    checkToken();

    //postされたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    //バリデーション
    $errors = validation($datas, false);

    if(empty($errors)) {
        $sql = "SELECT id,name,password FROM users WHERE name = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$datas['name']]);

        //ユーザー情報があるか否か
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //パスワードが一致するか検証
            if(password_verify($datas['password'], $row['password'])) {
                //セッションIDを振り直す
                session_regenerate_id(true);
                //セッションにログイン情報を格納
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                header("Location: main.php");
                exit();
            }else {
                $login_err = "ユーザー名またはパスワードが違います";
            }
        }else {
            $login_err = "ユーザー名またはパスワードが違います";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ログイン</h1>

    <?php

    if(!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }

    ?>

    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">

        <div class="form-group">
            <label>
                ユーザーネーム
                <input type="text" name="name" value="<?php echo h($datas['name']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['name']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <label>
                パスワード
                <input type="password" name="password" value="<?php echo h($datas['password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['password']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
            <input type="submit" value="Submit">
        </div>

        <p>初めての方はこちら。<a href="register.php">新規登録</a></p>
    </form>
</body>
</html>