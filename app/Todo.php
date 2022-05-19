<?php

class Todo
{
    private $pdo;


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function checkPost()
    {
        //サーバー変数を調べる。postだった時、postで送信されたデータの処理
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            Token::check();
            //getで送られてきたactionの種類を格納
            $action = filter_input(INPUT_GET, 'action');

            switch($action) {
                case 'add':
                    $this->add();
                    break;
                case 'toggle':
                    $this->toggle();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                default:
                    exit;
            }

            exit;
        }
    }


    private function add()
    {
        //filter_inputでデータを取得
        $title = trim(filter_input(INPUT_POST, 'title'));
        if($title === '') {
            return;
        }

        $stmt = $this->pdo->prepare("INSERT INTO todos(title, user) VALUES (:title, :user)");
        $stmt->bindValue('title', $title, PDO::PARAM_STR);
        $stmt->bindValue('user', $_SESSION['name'], PDO::PARAM_STR);
        $stmt->execute();
    }


    private function toggle()
    {
        $id = filter_input(INPUT_POST, 'id');
        if(empty($id)) {
            return;
        }

        $stmt = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }


    private function delete()
    {
        $id = filter_input(INPUT_POST, 'id');
        if(empty($id)) {
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function getAll()
    {
        // $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC");
        // return $stmt->fetchAll();
        
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE user = :user ORDER BY id DESC");
        $stmt->bindValue('user', $_SESSION['name'], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}