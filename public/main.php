<?php

session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

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
    <h1>ようこそ、<?= htmlspecialchars($_SESSION['name']) ?>さん</h1>

    <p><a href="../app/logout.php">ログアウト</a></p>
</body>
</html>