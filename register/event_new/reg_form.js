var college = document.getElementById('college_selection');
var clg_input = document.getElementById('clg');
clg_input.style.display = 'none';
college.addEventListener('change', () => {
    var c = college.options[college.selectedIndex].value;
    if (c === 'oth') {
        clg_input.style.display = '';
    } else {
        clg_input.style.display = 'none';
    }
});