<?php

require_once(__DIR__ . "/../app/config.php");

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

$pdo = Database::getInstance();

$todoClass = new Todo($pdo);
$todoClass->checkPost();
$todos = $todoClass->getAll();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoApp</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New&display=swap" rel="stylesheet">
</head>
<body>

    <!-- それぞれの要素に必要なtokenを一箇所にまとめた。今回はmain要素にカスタムデータ属性でつける -->
    <main data-token="<?= Utils::h($_SESSION['token']); ?>">

        <h1>TodoApp</h1>
        
        <p class="logout">ようこそ、<?= Utils::h($_SESSION['name']) ?>さん
        <a href="../verification/logout.php">ログアウト</a></p>


        <form>
            <input type="text" name="title" placeholder="ここにTodoを入力">
        </form>
        
        <ul>
            <?php foreach($todos as $todo): ?>

                <li data-id="<?= Utils::h($todo->id); ?>">
                    <input type="checkbox" <?=  $todo->is_done ? "checked" : '';?>>
                    
                    <span><?= Utils::h($todo->title); ?></span>

                    <span class="delete">削除</span>
                </li>
                
            <?php endforeach; ?>
        </ul>

    </main>

    <script src="js/main.js"></script>
</body>
</html>