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

    <h1>TodoApp</h1>
    
    <p class="logout">ようこそ、<?= Utils::h($_SESSION['name']) ?>さん
    <a href="../Verification/logout.php">ログアウト</a></p>


    <form action="main.php?action=add" method="post">
        <input type="text" name="title" placeholder="ここにTodoを入力">
        <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        <!-- <button>送信</button> -->
    </form>
    
    <ul>
        <?php foreach($todos as $todo): ?>
            <li>
                <form action="main.php?action=toggle" method="post">
                    <input type="checkbox" <?=  $todo->is_done ? "checked" : '';?>>
                    <input type="hidden" name="id" value="<?= Utils::h($todo->id); ?>">
                    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                </form>
                
                <span class="<?= $todo->is_done ? 'done' : ''?>">
                    <?= Utils::h($todo->title); ?>
                </span>

                <form action="main.php?action=delete" method="post" class="delete-form">
                    <span class="delete">削除</span>
                    <input type="hidden" name="id" value="<?= Utils::h($todo->id); ?>">
                    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>


    <script src="js/main.js"></script>
</body>
</html>