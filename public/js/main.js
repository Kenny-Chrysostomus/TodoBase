'use strict';

{
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    //checkboxを押すと自動で送信
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            checkbox.parentNode.submit();
        });
    });
}