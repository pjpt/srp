var price = document.getElementById('price');
var value = 400;

price.innerText = value + '₹';

var wa_selected = false;
var wb_selected = false;
var case_selected = false;
var paper_selected = false;
var quiz_selected = false;
var symposium_selected = false;
var vib_del_selected = false;

workshop_a.addEventListener('change', () => {
    var e = workshop_a.options[workshop_a.selectedIndex].value;

    if (e !== "") {
        wa_selected = true;
    } else {
        wa_selected = false;
    }
    update();
});

workshop_b.addEventListener('change', () => {
    var e = workshop_b.options[workshop_b.selectedIndex].value;

    if (e !== "") {
        wb_selected = true;
    } else {
        wb_selected = false;
    }
    update();
});

case_p.addEventListener('change', () => {
    case_selected = case_p.checked;
    update();
});

paper.addEventListener('change', () => {
    paper_selected = paper.checked;
    update();
});

quiz.addEventListener('change', () => {
    quiz_selected = quiz.checked;
    update();
});

symposium.addEventListener('change', () => {
    symposium_selected = symposium.checked;
    update();
});

vib_del.addEventListener('change', () => {
    vib_del_selected = vib_del.checked;
    update();
});

function update() {
    value = 400;
    if (wa_selected) {
        value += 300;
    }

    if (wb_selected) {
        value += 300;
    }

    if (wa_selected == true && wb_selected == true) {
        value -= 100;
    }

    if (case_selected) {
        value += 100;
    }

    if (paper_selected) {
        value += 100;
    }

    if (quiz_selected) {
        value += 300;
    }

    if (symposium_selected) {
        value += 800;
    }

    if (vib_del_selected) {
        value += 400;
    }
    
    console.log(value);
    price.innerText = value+'₹';
}