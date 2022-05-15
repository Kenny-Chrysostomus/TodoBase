<?php

require_once(__DIR__ . "/../app/config.php");

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

//サーバー変数を調べる。postだった時
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkToken();
    $action = filter_input(INPUT_GET, 'action');

    switch($action) {
        case 'add':
            addTodo($pdo);
            break;
        case 'toggle':
            toggleTodo($pdo);
            break;
        case 'delete':
            deleteTodo($pdo);
            break;
        default:
            exit;
    }


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
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <p>ようこそ、<?= htmlspecialchars($_SESSION['name']) ?>さん

    <a href="../app/logout.php">ログアウト</a></p>

    <h1>Todo</h1>

    <form action="main.php?action=add" method="post">
        <input type="text" name="title" placeholder="ここにTodoを入力">
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        <!-- <button>送信</button> -->
    </form>
    
    <ul>
        <?php foreach($todos as $todo): ?>
            <li>
                <form action="main.php?action=toggle" method="post">
                    <input type="checkbox" <?=  $todo->is_done ? "checked" : '';?>>
                    <input type="hidden" name="id" value="<?= h($todo->id); ?>">
                    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
                </form>
                
                <span>
                    <?= h($todo->title); ?>
                </span>

                <form action="main.php?action=delete" method="post">
                    <span class="delete">削除</span>
                    <input type="hidden" name="id" value="<?= h($todo->id); ?>">
                    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>


    <script src="js/main.js"></script>
</body>
</html>