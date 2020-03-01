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
        if ($(this).scrollTop() > 50) {
            header.style.lineHeight = '60px';
        } else {
            header.style.lineHeight = '80px';
        }
    }
});

var desc = document.getElementById('desc');
var line = "Borrowing its vibrance from the greatest depths of human imagination, the VIBRANT threatens to captivate anyone lay eyes upon it. From the darkest pit of hellish horrors to the dearthless bounties of the high heavens, join us as we unveil it all in our fest.";
var word_list = line.split(' ');

var time = .5;
for (var i = 0; i < word_list.length; i++) {
    var span = document.createElement('span');
    span.style.animationDelay = time + "s";    
    span.style.opacity = "0";
    span.className = 'fade-in';
    span.innerText = word_list[i] + ' ';
    desc.appendChild(span);
    time += word_list[i].length/30;
}