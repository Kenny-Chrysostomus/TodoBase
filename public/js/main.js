'use strict';

{
    //tokenをそれぞれの要素から取得するのはだるいから先に取得
    const token = document.querySelector('main').dataset.token;

    /**チェックボックスに関する処理 */
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //checkboxを押すと自動で送信
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            //fetch()...ページを遷移させずにデータをサーバーに送信(これでformを使わなくて良くなった)
            const url = 'main.php?action=toggle';
            const options = {
                method: 'POST',
                //inputに直接設定したidとtokenをセット
                body: new URLSearchParams({
                    id: checkbox.parentNode.dataset.id, //親要素のdataset.id
                    token: token,
                }),
            };
            fetch(url, options);
        });
    });

    /**削除機能 */
    const deletes = document.querySelectorAll('.delete');
    deletes.forEach(deleteList => {
        deleteList.addEventListener('click', () => {
            if(!confirm("削除してもよろしいですか？")) {
                return;
            }
            
            fetch('main.php?action=delete', {
                method: 'POST',
                body: new URLSearchParams({
                    id: deleteList.parentNode.dataset.id,
                    token: token,
                }),
            });

            //DOM操作により削除後のページの表示を更新
            deleteList.parentNode.remove();
        });
    });
}