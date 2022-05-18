<?php

require_once(__DIR__ . "/../app/config.php");
require_once(__DIR__ . "/../verification/Verification.php");

//既にログインしていたらリダイレクト
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: main.php");
    exit();
}

$pdo = Database::getInstance();

//postされてきたデータを格納
$datas = [
    'name' => '',
    'password' => '',
    'confirm_password' => ''
];
$login_err = "";

//get通信だったらセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    Token::set();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    Token::check();

    //postされたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    //バリデーション
    $errors = Verification::validation($datas, false);

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
    <title>Login</title>
    <link rel="stylesheet" href="./css/log.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
</head>
<body>
    <h1>ログイン</h1>

    <?php

    if(!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }

    ?>

    <form class="form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">

        <div class="form-group">
            <label>
                ユーザーネーム<br>
                <input type="text" name="name" value="<?php echo Utils::h($datas['name']); ?>">
                <span class="invalid-feedback"><?php echo Utils::h($errors['name']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <label>
                パスワード<br>
                <input type="password" name="password" value="<?php echo Utils::h($datas['password']); ?>">
                <span class="invalid-feedback"><?php echo Utils::h($errors['password']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <input type="hidden" name="token" value="<?php echo Utils::h($_SESSION['token']); ?>">
            <input type="submit" value="Submit">
        </div>

        <p>初めての方はこちら。<a href="register.php">新規登録</a></p>
    </form>
</body>
</html>