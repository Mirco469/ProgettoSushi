document.addEventListener('readystatechange', function() {
	if(document.readyState === "complete") {
		for( var i = 0 ; i < document.getElementsByName('Aggiungi').length ; i++ ) {
			document.getElementsByName('Aggiungi')[i].onclick = function(e) {
				var name = this.parentNode.textContent; // prendo il nome del prodotto
				var dt = this.parentNode; // seleziono il dt
				aggiungi(name, dt);
			};
		}/*
		document.getElementsByName('Aggiungi').forEach(function(item,index) { // cerco gli elementi per input perchè so che gli unici input presenti sono per l'aggiungi
			item.onclick = function(e) {
				var name = item.parentNode.textContent; // prendo il nome del prodotto
				var dt = item.parentNode; // seleziono il dt
				aggiungi(name, dt);
			};
		});*/
	}
});

function aggiungi(name, dt) {
	var xhr = new XMLHttpRequest();	//istanziazione dell'oggetto richiesta
	xhr.onreadystatechange = function() { //funzione richiamata quando la richiesta ritorna un risultato
		if(xhr.readyState ==4) { // se è andata a buon fine
			var data = JSON.parse(xhr.responseText);
			console.log(data); // Stampo a console il contenuto della risposta da parte del server

			var dd = document.createElement("dd"); // creo il dd
			if (data.success)
			{
				dd.textContent = "Prodotto aggiunto al carrello!"; // scrivo il testo del messaggio
				dd.classList.add("successo"); // assegno la classe al dd
			}
			else if (data.error == 'database error')
			{
				window.location.href='errore500.php';
			}
			else if (data.error == 'login error')
			{
				window.location.href='login.php';
			}
			else if (data.error == 'already present')
			{
				dd.textContent = "Prodotto già nel carrello!"; // scrivo il testo del messaggio
				dd.classList.add("successo"); // assegno la classe al dd
			}
			else if (data.error == 'unknown')
			{
				dd.textContent = "Errore nell'aggiunta al carrello!"; // scrivo il testo del messaggio
				dd.classList.add("errore"); // assegno la classe al dd
			}
			// cancello il messaggio esistente se c'è
			var mess = dt.nextSibling;
			if (mess.className == "errore" || mess.className == "successo")
			{
				mess.parentNode.removeChild(mess);
			}
			dt.parentNode.insertBefore(dd, dt.nextSibling); // inserisco il dd
		}
	}
	xhr.open("POST", "take_away.php", true); // impostazioni tipo di richiesta POST alla pagina take_away.php
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	// impostazione contenuto che verrà inviato al server
	xhr.send("action=aggiungi&name="+name);	// azione che invia la richiesta al server con i parametri in formato nome=valore separati da &
}