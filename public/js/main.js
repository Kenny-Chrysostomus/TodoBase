'use strict';

{
    //tokenをそれぞれの要素から取得するのはだるいから先に取得
    const token = document.querySelector('main').dataset.token;
    //入力されたTodoを捕獲
    const input = document.querySelector('[name="title"]');

    //ページ読み込み時にすぐ入力できるようにフォーカス
    input.focus();

    const ul = document.querySelector('ul');
    ul.addEventListener('click', e => {
        //チェックボックスに関する処理
        if(e.target.type === 'checkbox') {
            fetch('main.php?action=toggle', {
                method: 'POST',
                //inputに直接設定したidとtokenをセット
                body: new URLSearchParams({
                    id: e.target.parentNode.dataset.id, //親要素のdataset.id
                    token: token,
                }),
            });
        }

        //削除機能に関する処理
        if(e.target.classList.contains('delete')) {
            if(!confirm("削除してもよろしいですか？")) {
                return;
            }
            
            fetch('main.php?action=delete', {
                method: 'POST',
                body: new URLSearchParams({
                    id: e.target.parentNode.dataset.id,
                    token: token,
                }),
            });

            //DOM操作により削除後のページの表示を更新
            e.target.parentNode.remove();
        }
    });


    /**追加機能 */
    document.querySelector('form').addEventListener('submit', e => {
        e.preventDefault();

        const titleValue = input.value;

        //fetch()...ページを遷移させずにデータをサーバーに送信(これでformを使わなくて良くなった)
        fetch('main.php?action=add', {
            method: 'POST',
            body: new URLSearchParams({
                title: titleValue,
                token: token,
            }),
        })
        .then(response => {
            return response.json();
        })
        .then(json => {
            //受け取ったidを元にページにtodoを表示
            addTodo(json.id, titleValue);
        });

        // fetch(url, options)
        // .then(response => {   処理の結果が渡される
        //     return result     正しい形式で返されたかチェック
        // })
        // .then(result => {})   値を受け取る    

        input.value = '';
        input.focus();
    });

    function addTodo(id, titleValue){
        const li = document.createElement('li');
        li.dataset.id = id;

        const checkbox = document.createElement('input');
        checkbox.type= 'checkbox';

        const title = document.createElement('span');
        title.textContent = titleValue;

        const deleteSpan = document.createElement('span');
        deleteSpan.textContent = '削除';
        deleteSpan.classList.add('delete');

        li.appendChild(checkbox);
        li.appendChild(title);
        li.appendChild(deleteSpan);

        //作ったliをulのfirstChildの前に挿入
        ul.insertBefore(li, ul.firstChild);

    }
    

}