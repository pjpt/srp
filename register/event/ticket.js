var price = document.getElementById('price');
var event = document.getElementById('event');

event.addEventListener('change', function() {
	var e = event.options[event.selectedIndex].value;
	var group = document.getElementById('group_detail');

	if (e == '1') {
		price.innerHTML = "100₹";
		group.style.display = 'none';
	}
	if (e == '2') {
		price.innerHTML = "100₹";
		group.style.display = 'none';
	}
	if (e == '3') {
		group.style.display = 'block';
		price.innerHTML = "200₹";
	}
});