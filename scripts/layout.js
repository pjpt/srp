check.addEventListener('change', () => {
    var header = document.getElementsByTagName('header')[0];
    var main = document.getElementsByTagName('main')[0];
    if (check.checked == true) {
        header.className = "fix";
        main.style.paddingTop = "80px"
    } else {
        header.className = '';
        main.style.paddingTop = "0px";
    }
});