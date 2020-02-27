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


$(window).scroll(function() {
    var header = document.getElementsByTagName('header')[0];
    var check = document.getElementById('check');
    if (check.checked === false) {
        if ($(this).scrollTop() > 100) {
            header.style.lineHeight = '60px';
        } else {
            header.style.lineHeight = '80px';
        }
    }
});