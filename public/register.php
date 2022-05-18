<?php

require_once(__DIR__ . "/../app/config.php");
require_once(__DIR__ . "/../Verification/Verification.php");

$pdo = Database::getInstance();

$datas = [
    'name' => '',
    'password' => '',
    'confirm_password' => ''
];

//get通信だったらセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    Token::set();
}

//post通信だったらdbへの新規登録処理
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //csrf対策
    Token::check();

    //postされたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    //バリデーション
    $errors = Verification::validation($datas);

    //dbに同一ユーザーが存在していないか確認
    if(empty($errors['name'])) {
        $sql = "SELECT id FROM users WHERE name = :name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('name', $datas['name'], PDO::PARAM_INT);
        $stmt->execute();
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors['name'] = 'このユーザー名は既に使われています';
        }
    }

    if(empty($errors)) {
        $datas['password'] = password_hash($datas['password'], PASSWORD_DEFAULT);
        //トランザクション処理
        $pdo->beginTransaction();
        try {
            $sql = "INSERT INTO users (name,password) VALUES (?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$datas['name'], $datas['password']]);
            $pdo->commit();
            
            //そのままログインしたい
            $sql = "SELECT id FROM users WHERE name = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$datas['name']]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //セッションIDを振り直す
            session_regenerate_id(true);
            //セッションにログイン情報を格納
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $datas['name'];
            header("Location: main.php");
            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $pdo->rollBack();
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
    <title>Sign up</title>
</head>

<body>
    <h1>新規登録</h1>

    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">

        <div class="form-group">
            <label>
                ユーザーネーム
                <input type="text" name="name" value="<?php echo Utils::h($datas['name']); ?>">
                <span class="invalid-feedback"><?php echo Utils::h($errors['name']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <label>
                パスワード
                <input type="password" name="password" value="<?php echo Utils::h($datas['password']); ?>">
                <span class="invalid-feedback"><?php echo Utils::h($errors['password']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <label>
                パスワード確認用
                <input type="password" name="confirm_password" value="<?php echo Utils::h($datas['confirm_password']); ?>">
                <span class="invalid-feedback"><?php echo Utils::h($errors['confirm_password']); ?></span>
            </label>
        </div>

        <div class="form-group">
            <input type="hidden" name="token" value="<?php echo Utils::h($_SESSION['token']); ?>">
            <input type="submit" value="Submit">
        </div>

        <p>すでにアカウントをお持ちですか？ <a href="login.php">ログイン</a></p>

    </form>
</body>

</html>