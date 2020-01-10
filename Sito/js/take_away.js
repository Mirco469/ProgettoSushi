function ajax() {
    var xhr = new XMLHttpRequest(); //istanziazione dell'oggetto richiesta
    xhr.onreadystatechange = function() { //funzione richiamata quando la richiesta ritorna un risultato
        if (xhr.readyState == 4) { // se è andata a buon fine
            xhr.responseText // Questa variabile contiene il risultato del server
        }
    }
    xhr.open("POST", "take_away.php", true); // impostazioni tipo di richiesta POST alla pagina take_away.php
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // impostazione contenuto che verrà inviato al server
    xhr.send("action=aggiungi&name="+name); // azione che invia la richiesta al server con i parametri in formato nome=valore separati da &
}

document.addEventListener('DOMContentLoaded', (event) => {
	document.getElementsByName('Aggiungi').forEach(function(item,index) { // cerco gli elementi per input perchè so che gli unici input presenti sono per l'aggiungi
		item.onclick = function(e) {
			var name = item.parentNode.textContent; // prendo il nome del prodotto
			aggiungi(name);
		};
	});
});

function aggiungi(name) {
	var xhr = new XMLHttpRequest();	//istanziazione dell'oggetto richiesta
	xhr.onreadystatechange = function(){ //funzione richiamata quando la richiesta ritorna un risultato
		if(xhr.readyState ==4){	// se è andata a buon fine fa
			console.log(xhr.responseText); // Stampo a console il contenuto della risposta da parte del server
		}	
	}
	xhr.open("POST", "take_away.php", true);	// impostazioni tipo di richiesta POST alla pagina take_away.php
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	// impostazione contenuto che verrà inviato al server
	xhr.send("action=aggiungi&name="+name);	// azione che invia la richiesta al server con i parametri in formato nome=valore separati da &
}