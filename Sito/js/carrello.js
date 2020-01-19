function addQuantita(nome) {
	var quantita = parseInt(document.getElementById('qt-'+nome).value);
	if( !isNaN(quantita) ) {
		quantita += 1;
		
		if(quantita<=100) {	// limito la quantita massima di un prodotto a 100 porzioni
			setQuantita(nome, quantita);
		}
	} else {
		alert('Quantità inserita per '+nome+' non valida');
	}
}

function rmQuantita(nome) {
	var quantita = parseInt(document.getElementById('qt-'+nome).value);
	if( !isNaN(quantita) ) {
		quantita -= 1;
		
		if(quantita>0) {	// limito la quantita minima a 1 porzione
			setQuantita(nome, quantita);
		}
	} else {
		alert('Quantità inserita per '+nome+' non valida');
	}
}

function setQuantita(nome, quantita) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){ //quando l’operazione è completata
		if(xhr.readyState ==4){
			var data = JSON.parse(xhr.responseText);
			console.log(data);
			
			if( data.success ) {
				document.getElementById('qt-'+nome).value = data.quantity;
				document.getElementById('tot-'+nome).textContent = 'Prezzo: '+parseFloat(data.price).toFixed(2).replace('.',',')+'€';
				
				document.getElementById('totaleValue').textContent = parseFloat(data.total).toFixed(2).replace('.',',');
			} else if(data.error == 'invalid quantita') {	// quantita prodotto inserita non valida
				alert('Quantità inserita per '+nome+' non valida');
			} else if(data.error == 'not found') {	// prodotto non trovato a carrello
				alert('Il prodotto richiesto non è presente a carrello');
				
				var dt = document.getElementById('dt-'+nome);
				var dd = document.getElementById('dd-'+nome);
				
				dt.parentNode.removeChild(dt);
				dd.parentNode.removeChild(dd);
			} else if(data.error == 'The request is not valid') {
				window.location.reload();
			}
		}	
	}
	xhr.open("POST", "carrello.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("action=edit&name="+nome+"&quantity="+quantita);
}

function rmProdotto(nome) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){ //quando l’operazione è completata
		if(xhr.readyState ==4){
			var data = JSON.parse(xhr.responseText);
			console.log(data);
			
			if(data.success) {
				if( document.getElementsByTagName('dt').length > 1 ) {
					var dt = document.getElementById('dt-'+nome);
					var dd = document.getElementById('dd-'+nome);
					
					dt.parentNode.removeChild(dt);
					dd.parentNode.removeChild(dd);
					
					document.getElementById('totaleValue').textContent = parseFloat(data.total).toFixed(2).replace('.',',');
					
					alert('Prodotto rimosso con successo!');
				} else {
					alert('Prodotto rimosso con successo!');
					
					window.location.reload();
				}
			} else if(data.error == 'not found') {
				alert('Il prodotto richiesto non è presente a carrello');
				
				var dt = document.getElementById('dt-'+nome);
				var dd = document.getElementById('dd-'+nome);
				
				if( dt != null ) {
					dt.parentNode.removeChild(dt);
				}
				if( dd != null ) {
					dd.parentNode.removeChild(dd);
				}
				
			} else if(data.error == 'The request is not valid') {
				window.location.reload();
			}
		}	
	}
	xhr.open("POST", "carrello.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("action=remove&name="+nome);
}

