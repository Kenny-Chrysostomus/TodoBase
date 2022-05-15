<?php

require_once(__DIR__ . "/../app/config.php");

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

//サーバー変数を調べる。postだった時
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkToken();
    addTodo($pdo);

    header('Location: main.php'); //GETであくせすしてる?
    exit;
}

$todos = getTodos($pdo);


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoApp</title>
</head>
<body>
    <p>ようこそ、<?= htmlspecialchars($_SESSION['name']) ?>さん

    <a href="../app/logout.php">ログアウト</a></p>

    <h1>Todo</h1>

    <form action="main.php" method="post">
        <input type="text" name="title" placeholder="ここにTodoを入力">
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        <!-- <button>送信</button> -->
    </form>
    
    <ul>
        <?php foreach($todos as $todo): ?>
            <li>
                <input type="checkbox" <?=  $todo->is_done ? "checked" : '';?>>
                <span>
                    <?= h($todo->title); ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>


</body>
</html>