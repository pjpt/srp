var price = document.getElementById('price');
var event = document.getElementById('event');

event.addEventListener('change', function() {
	var e = event.options[event.selectedIndex].value;
	
	var url = 'http://localhost/register/event_new/price.php?event=' + e;

	fetch(url).then(
		function (response) {
			response.text().then(function(text) {
				price.innerText = text+'₹';			
			})
		}
	)
});