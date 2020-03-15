check.addEventListener('change', () => {
    var header = document.getElementsByTagName('header')[0];
    var main = document.getElementsByTagName('main')[0];
    if (check.checked == true) {
        header.className = "fix";
        main.style.paddingTop = "50px"
    } else {
        header.className = '';
        main.style.paddingTop = "0px";
    }
});

$(window).scroll(function() {
    var header = document.getElementsByTagName('header')[0];
    var nav = header.getElementsByTagName('nav')[0];
    var check = document.getElementById('check');
    if (check.checked === false) {
        if ($(this).scrollTop() > 50) {
            header.className = 'header_small';
        } else {
            header.className = 'header_big';
        }
    }
});

if (main == true) {
    var line = ""
    var desc = document.getElementById('slogan');
    var line = "COME... BE A STORY";
    var word_list = line.split('');

    var time = .5;
    for (var i = 0; i < word_list.length; i++) {
        var span = document.createElement('span');
        span.style.animationDelay = time + "s";    
        span.style.opacity = "0";
        span.className = 'fade-in';
        span.innerText = word_list[i];
        desc.appendChild(span);
        time += word_list[i].length/10;
    }
    
    var desc = document.getElementById('desc');
    var line = "Borrowing its vibrance from the greatest depths of human imagination, the VIBRANT threatens to captivate anyone who lay their eyes upon it. From the darkest pit of hellish horrors to the dearthless bounties of the high heavens, join us as we unveil it all in our fest.";
    var word_list = line.split(' ');

    for (var i = 0; i < word_list.length; i++) {
        var span = document.createElement('span');
        span.style.animationDelay = time + "s";    
        span.style.opacity = "0";
        span.className = 'fade-in';
        span.innerText = word_list[i] + ' ';
        desc.appendChild(span);
        time += word_list[i].length/30;
    }
}

function change_check(check_id) {
    check_id.checked = !check_id.checked;
    var icon = check_id.nextSibling;
    if (icon.innerText === '+') {
        icon.innerText = '−';
    } else {
        icon.innerText = '+';
    }
    var event = document.createEvent("HTMLEvents");
    event.initEvent("change", true, true);
    event.eventName = "change";
    check_id.dispatchEvent(event);
}

if (vibcon == true) {
    checkA.addEventListener('change', () => {
        var coll = document.getElementById('collapsible1');
        if (checkA.checked) {
            coll.style.display = 'block';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkB.addEventListener('change', () => {
        var coll = document.getElementById('collapsible2');
        if (checkB.checked) {
            coll.style.display = 'block';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkC.addEventListener('change', () => {
        var coll = document.getElementById('collapsible3');
        if (checkC.checked) {
            coll.style.display = 'block';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkD.addEventListener('change', () => {
        var coll = document.getElementById('collapsible4');
        if (checkD.checked) {
            coll.style.display = 'block';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkE.addEventListener('change', () => {
        var coll = document.getElementById('collapsible5');
        if (checkE.checked) {
            coll.style.display = 'block';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkF.addEventListener('change', () => {
        var coll = document.getElementById('collapsible6');
        if (checkF.checked) {
            coll.style.display = 'block';
        } else {
            coll.style.display = 'none';
        }
    });
}

if (event == true) {
    checkA.addEventListener('change', () => {
        var coll = document.getElementById('collapsible1');
        if (checkA.checked) {
            coll.style.display = 'grid';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkB.addEventListener('change', () => {
        var coll = document.getElementById('collapsible2');
        if (checkB.checked) {
            coll.style.display = 'grid';
            
        } else {
            coll.style.display = 'none';
            
        }
    });
    checkC.addEventListener('change', () => {
        var coll = document.getElementById('collapsible3');
        if (checkC.checked) {
            coll.style.display = 'grid';
            
        } else {
            coll.style.display = 'none';
            
        }
    });
    checkD.addEventListener('change', () => {
        var coll = document.getElementById('collapsible4');
        if (checkD.checked) {
            coll.style.display = 'grid';
            
        } else {
            coll.style.display = 'none';
            
        }
    });
    checkE.addEventListener('change', () => {
        var coll = document.getElementById('collapsible5');
        if (checkE.checked) {
            coll.style.display = 'grid';
            
        } else {
            coll.style.display = 'none';
            
        }
    });
    checkF.addEventListener('change', () => {
        var coll = document.getElementById('collapsible6');
        if (checkF.checked) {
            coll.style.display = 'grid';

        } else {
            coll.style.display = 'none';
            
        }
    });
    checkG.addEventListener('change', () => {
        var coll = document.getElementById('collapsible7');
        if (checkG.checked) {
            coll.style.display = 'grid';
        } else {
            coll.style.display = 'none';
        }
    });
}