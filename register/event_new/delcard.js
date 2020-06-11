var price = document.getElementById('price');
price.innerText = 500;

num_card.addEventListener('change', () => {
    if (num_card.value <= 9) {
        price.innerText = 500*num_card.value;
    } else {
        price.innerText = 450*num_card.value;
    }
});