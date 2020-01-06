document.addEventListener('DOMContentLoaded', (event) => {
	document.getElementsByName('minus').forEach(function(item, index) {
		item.onclick = function(e) {
			var quantita = parseInt(document.getElementsByName('quantita')[index].value)-1;
			
			if(quantita>=1) {
				var dt = document.getElementsByTagName('dt')[index].textContent.split(' - ');
				changeQuantity(index, dt[0].trim(), dt[1].trim(), quantita);
			}
		};
	}); 
	
	document.getElementsByName('plus').forEach(function(item, index) {;
		item.onclick = function(e) {
			var quantita = parseInt(document.getElementsByName('quantita')[index].value)+1;
			
			if(quantita<=100) {	// limito la quantita massima di un prodotto a 100 porzioni
				var dt = document.getElementsByTagName('dt')[index].textContent.split(' - ');
				changeQuantity(index, dt[0].trim(), dt[1].trim(), quantita);
			}
		};
	}); 
	
	document.getElementsByName('rimuovi').forEach(function(item, index) {
		item.addEventListener("click",function(e) {
			var dt = document.getElementsByTagName('dt')[index].textContent.split(' - ');
			removeProduct(index, dt[0].trim(), dt[1].trim());
		});
	}); 
});

function changeQuantity(index, name, category, quantity) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){ //quando l’operazione è completata
		if(xhr.readyState ==4){
			var data = JSON.parse(xhr.responseText);
			console.log(data);
			
			if( data.success ) {
				document.getElementsByName('quantita')[index].value = data.quantity;
				document.getElementsByTagName('dd')[index].getElementsByTagName('span')[0].textContent = 'Prezzo: '+parseFloat(data.price).toFixed(2).replace('.',',')+'€';
				document.getElementsByClassName('totaleText')[0].getElementsByTagName('span')[0].textContent = parseFloat(data.total).toFixed(2).replace('.',',')+'€';
			} else {
				// errore modifica quantita
			}
		}	
	}
	xhr.open("POST", "carrello.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("action=edit&name="+name+"&category="+category+"&quantity="+quantity);
}

function removeProduct(index, name, category) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){ //quando l’operazione è completata
		if(xhr.readyState ==4){
			var data = JSON.parse(xhr.responseText);
			console.log(data);
			
			if(data.success) {
				if( document.getElementsByTagName('dt').length > 1 ) {
					document.getElementsByTagName('dt')[index].remove();
					document.getElementsByTagName('dd')[index].remove();
					
					document.getElementsByClassName('totaleText')[0].getElementsByTagName('span')[0].textContent = parseFloat(data.total).toFixed(2).replace('.',',')+'€';
				} else {
					window.location.reload();
				}
			}
		}	
	}
	xhr.open("POST", "carrello.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("action=remove&name="+name+"&category="+category);
}

