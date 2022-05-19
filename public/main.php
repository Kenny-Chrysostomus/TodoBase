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
                <input
                    type="checkbox"
                    data-id="<?= Utils::h($todo->id); ?>"
                    data-token="<?= Utils::h($_SESSION['token']); ?>"
                    <?=  $todo->is_done ? "checked" : '';?>
                >
                
                <span><?= Utils::h($todo->title); ?></span>

                <span
                    class="delete"
                    data-id="<?= Utils::h($todo->id); ?>"
                    data-token="<?= Utils::h($_SESSION['token']); ?>">
                    削除
                </span>
            </li>
        <?php endforeach; ?>
    </ul>


    <script src="js/main.js"></script>
</body>
</html>