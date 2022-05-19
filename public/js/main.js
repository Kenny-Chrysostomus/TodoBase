'use strict';

{
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //checkboxを押すと自動で送信
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            //fetch()...ページを遷移させずにデータをサーバーに送信
            const url = 'main.php?action=toggle';
            const options = {
                method: 'POST',
                body: new URLSearchParams({
                    id: checkbox.dataset.id,
                    token: checkbox.dataset.token,
                }),
            };
            fetch(url, options);
        });
    });

    const deletes = document.querySelectorAll('.delete');
    deletes.forEach(deleteList => {
        deleteList.addEventListener('click', () => {
            if(!confirm("削除してもよろしいですか？")) {
                return;
            }
            deleteList.parentNode.submit();
        });
    });
}