<?php





//postされた値のバリデーション
function validation($datas, $confirm = true) {
    $errors = [];

    //ユーザー名のチェック
    if(empty($datas['name'])) {
        $errors['name'] = 'ユーザー名を入力してください';
    }else if(mb_strlen($datas['name']) > 20) {
        $errors['name'] = '20文字以内で入力してください';
    }

    //パスワードのチェック（正規表現）
    if(empty($datas["password"])){
        $errors['password']  = "パスワードを入力してください";
    }else if(!preg_match('/\A[a-z\d]{8,100}+\z/i',$datas["password"])){
        $errors['password'] = "パスワードは8文字以上で入力してください";
    }
    //パスワード入力確認チェック（ユーザー新規登録時のみ使用）
    if($confirm){
        if(empty($datas["confirm_password"])){
            $errors['confirm_password']  = "パスワードを入力してください";
        }else if(empty($errors['password']) && ($datas["password"] != $datas["confirm_password"])){
            $errors['confirm_password'] = "パスワードが一致しません";
        }
    }

    return $errors;
}
