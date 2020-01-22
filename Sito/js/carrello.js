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
				prodottoNonTrovato(dt, dd);
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
			
			var dt = document.getElementById('dt-'+nome);
			var dd = document.getElementById('dd-'+nome);
			
			if(data.success) {
				
				// rimuovo tutti i possibili messaggi precedenti
				var messaggiDaRimuovere = document.getElementsByClassName('messaggio');
				for( var i = 0; i < messaggiDaRimuovere.length ; i++  ) {
					messaggiDaRimuovere[i].parentNode.removeChild(messaggiDaRimuovere[i]);
				}
				
				if( document.getElementsByTagName('dt').length > 1 ) {
					
					dt.outerHTML = '<p class="messaggio successo">Prodotto rimosso con successo!</p>';
					dd.parentNode.removeChild(dd);
					
					document.getElementById('totaleValue').textContent = parseFloat(data.total).toFixed(2).replace('.',',');
				} else {
					
					dt.outerHTML = '<p class="messaggio successo">Prodotto rimosso con successo!</p>';
					dd.parentNode.removeChild(dd);
					
					window.location.reload();
				}
			} else if(data.error == 'not found') {
				prodottoNonTrovato(dt, dd);
			} else if(data.error == 'The request is not valid') {
				window.location.reload();
			}
		}	
	}
	xhr.open("POST", "carrello.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("action=remove&name="+nome);
}

function prodottoNonTrovato(dt, dd) {
	if( dt != null ) {
		
		// rimuovo tutti i possibili messaggi precedenti
		var messaggiDaRimuovere = document.getElementsByClassName('messaggio');
		for( var i = 0; i < messaggiDaRimuovere.length ; i++  ) {
			messaggiDaRimuovere[i].parentNode.removeChild(messaggiDaRimuovere[i]);
		}
		
		dt.outerHTML = '<p class="messaggio errore">Il prodotto richiesto non è presente a carrello</p>';
	}
	if( dd != null ) {
		if( dt == null ) {
			
			// rimuovo tutti i possibili messaggi precedenti
			var messaggiDaRimuovere = document.getElementsByClassName('messaggio');
			for( var i = 0; i < messaggiDaRimuovere.length ; i++  ) {
				messaggiDaRimuovere[i].parentNode.removeChild(messaggiDaRimuovere[i]);
			}
			
			dd = '<p>Il prodotto richiesto non è presente a carrello</p>' + dd;
		}
		dd.parentNode.removeChild(dd);
	}
}
