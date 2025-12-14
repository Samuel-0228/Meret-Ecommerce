// lightweight interactions for prototype
document.addEventListener('DOMContentLoaded', function () {
    // role selection on login page if present
    var el = document.querySelector('select[name=role]');
    if (el) {
        el.addEventListener('change', function () { /* placeholder if needed */ });
    }
});

function changeLang(lang) {
    fetch('set_lang.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'lang=' + lang
    }).then(() => location.reload());
}