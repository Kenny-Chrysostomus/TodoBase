'use strict';

{
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //checkboxを押すと自動で送信
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            checkbox.parentNode.submit();
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