// CHECK INPUT

function checkData(input) {
    var patt = new RegExp('^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkMinLen(input) {
    var testoInput = input.value.trim();
    if(testoInput.length>1){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkTesto(input) {
    var testoInput = input.value.trim();
    if(testoInput.length>1 && testoInput.length<=150){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkAlfanumericoESpazi(input) {
    var patt = new RegExp('^[a-zA-Z0-9 ]{2,}$');
    if(patt.test(input.value.trim())) {
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkCivico(input) {
    var patt = new RegExp('^[0-9]{1,}[/-]{0,1}[a-z0-9]{0,1}$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}

function checkAlfanumerico(input) {
    var patt = new RegExp('^[a-zA-Z0-9]{1,}$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkNomeCognome(input) {
    var patt = new RegExp('^[a-zA-Z]{1,}[ ]{1,}[a-zA-Z]{1,}$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkSoloLettereEDim(input) {
    var patt = new RegExp('^[a-zA-Z]{2,}$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
   
        return false;
    }
}
function checkSoloNumerieDim(input) {
    var patt = new RegExp('^[0-9]{2,}$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkCAP(input) {
    var patt = new RegExp('^35(100|121|122|123|124|125|126|127|128|129|131|132|133|134|135|136|137|138|139|141|142|143)$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkSoloNumeri(input) {
    var patt = new RegExp('^[0-9]+$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkNumeroIntero(input) {
    var patt = new RegExp('^[0-9]+$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkPrezzo(input) {
    var patt = new RegExp('^[0-9]{1,3}((.|,)[0-9]{1,2})?$');
    if(patt.test(input.value.trim())){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}

function checkTextarea(input) {
    var patt = new RegExp('^[^0-9]{10,200}$');
    if (patt.test(input.value.trim())) {
        togliErrore(input);
        return true;
    } else {
        return false;
    }
}

function checkCVV(input) {
    var patt = new RegExp('^[0-9]{3}$');
    if (patt.test(input.value.trim())) {
        togliErrore(input);
        return true;
    } else {
        return false;
    }
}


//FUNZIONI PER MOSTRARE E RIMUOVERE ERRORI

function mostraErrore(input, testoErrore) { //mostra un messaggio di errore per un determinato input
    togliErrore(input);
    var parent = input.parentNode;
    var p = document.createElement("p");
    p.className = "errore";
    p.textContent = testoErrore;
    parent.appendChild(p);
}

function togliErrore(input) {
    var p = input.parentNode;
    if(p.children.length > 2){
        p.removeChild(p.children[2]);
    }
}

// Funzione di validazione form inserimento news home_admin
function validazioneFormNews_homeAdmin() {
    var titolo = document.getElementById("titolo");
    var testo = document.getElementById("notizia")

    var risTitolo = checkTesto(titolo);
    var risTesto = checkTesto(testo);

    if(!risTitolo){
        mostraErrore(titolo, "Il titolo deve contenere almeno due caratteri");
    }
    if(!risTesto){
        mostraErrore(testo, "Il testo deve contenere almeno due caratteri e non più di 150");
    }

    return risTesto && risTitolo;

}


// Funzione di validazione form password gestione_profilo_admin e gestione_profilo_utente
function validazioneFormPassw_gestioneProfilo() {
    var password = document.getElementById("password");
    var c_password = document.getElementById("c_password");


    var risPasswordUguali = password.value == c_password.value;
    var risPassword = checkMinLen(password);

    if(!risPasswordUguali){
        togliErrore(c_password);
        mostraErrore(c_password, "Le password inserite non coincidono");
    }else{
        togliErrore(c_password);
    }

    if(!risPassword){
        mostraErrore(password, "La password è troppo breve, inserisci almeno 2 caratteri");
    }

    return risPassword && risPasswordUguali;
}

//Funzione di validazione form destinazione gestione_profilo_utente
function validazioneFormDest_gestione_profilo_utente() {
    var nome_cognome = document.getElementById("nome_cognome");
    var indirizzo = document.getElementById("indirizzo");
    var civico = document.getElementById("civico");
    var cap = document.getElementById("cap");
    var tel = document.getElementById("tel");

    var risNomeCognome = checkNomeCognome(nome_cognome);
    var risIndirizzo = checkAlfanumericoESpazi(indirizzo);
    var risCivico = checkCivico(civico);
    var risCAP = checkCAP(cap);
    var risTel = checkSoloNumerieDim(tel);

    if(!risNomeCognome){
        mostraErrore(nome_cognome, "Il nome deve contenere solo lettere e non contenere meno di due caratteri");
    }
    if(!risIndirizzo){
        mostraErrore(indirizzo,"L'indirizzo non deve contenere caratteri speciali");
    }
    if(!risCivico){
        mostraErrore(civico,"Il numero civico deve essere del formato corretto (e.g. 4, 4b, 4-b o 4/1)");
    }
    if(!risCAP){
        mostraErrore(cap,"Non hai inserito un CAP del comune di Padova");
    }
    if(!risTel){
        mostraErrore(tel,"Non hai inserito un numero telefonico valido");
    }
    return risNomeCognome && risIndirizzo && risCivico && risCAP && risTel;

}

//Funzione di validazione form pagamento gestione_profilo_utente
function validazioneFormPaga_gestione_profilo_utente() {
    var intestatario_carta = document.getElementById("intestatario_carta");
    var num_carta = document.getElementById("num_carta");
    var mese_scad = document.getElementById("mese_scad");
    var anno_scad = document.getElementById("anno_scad");

    var mese_scad_val = mese_scad.options[mese_scad.selectedIndex].text;
    var anno_scad_value = anno_scad.options[anno_scad.selectedIndex].text;

    var risMeseScad = mese_scad_val == "- Mese -";
    var risAnnoScad = anno_scad_value == "- Anno -";

    var risIntestatario = checkNomeCognome(intestatario_carta);
    var risNumCarta = checkSoloNumerieDim(num_carta);

    if(risMeseScad){
        togliErrore(mese_scad);
        mostraErrore(mese_scad, "Seleziona il mese di scadenza");
    }else{
        togliErrore(mese_scad);
    }
    if(risAnnoScad){
        togliErrore(anno_scad);
        mostraErrore(anno_scad, "Seleziona l'anno di scadenza");
    }else{
        togliErrore(anno_scad);
    }


    if(!risIntestatario){
        mostraErrore(intestatario_carta, "Inserisci nome e cognome (solo lettere ed almeno due caratteri)");
    }
    if(!risNumCarta){
        mostraErrore(num_carta, "Non hai inserito un numero della carta corretto");
    }

    return risNumCarta && risIntestatario && !risMeseScad && !risAnnoScad;

}

//Funzione per la validazione del form di accesso della pagina di login
function validazioneFormAccesso() {
    var username = document.getElementById("nomeUtente");
	var password = document.getElementById("passwordAcc");
	
	var risUsername = checkAlfanumerico(username);
	var risPassword = checkMinLen(password);
	
	if(risUsername)
	{
		togliErrore(username);
	}
	else
	{
		mostraErrore(username, "L'username deve contenere solo caratteri alfanumerici e avere almeno 2 caratteri");
	}
	if(risPassword)
	{
		togliErrore(password);
	}
	else
	{
		mostraErrore(password, "La password deve essere lunga almeno due caratteri");
	}
	
	return risUsername && risPassword;
}

//Funzione per la validazione del form di registrazione della pagina di login
function validazioneFormRegistrazione() {
    var username = document.getElementById("username");
    var nome = document.getElementById("nome");
	var cognome = document.getElementById("cognome");
	var password = document.getElementById("passwordReg");
	var password_r = document.getElementById("passwordRepeat");
	
	var risUsername = checkAlfanumerico(username);
	var risNome = checkSoloLettereEDim(nome);
	var risCognome = checkSoloLettereEDim(cognome);
	var risPassword = checkMinLen(password);
	var risPasswordUguali = password.value == password_r.value;
	
	if(risUsername)
	{
		togliErrore(username);
	}
	else
	{
		mostraErrore(username, "L'username deve contenere solo caratteri alfanumerici e avere almeno 2 caratteri");
	}
	if(risNome)
	{
		togliErrore(nome);
	}
	else
	{
		mostraErrore(nome, "Il nome deve contenere solo lettere  e avere almeno 2 caratteri");
	}
	if(risCognome)
	{
		togliErrore(cognome);
	}
	else
	{
		mostraErrore(cognome, "Il cognome deve contenere solo lettere  e avere almeno 2 caratteri");
	}
	if(risPassword)
	{
		togliErrore(password);
	}
	else
	{
		mostraErrore(password, "La password deve essere lunga almeno due caratteri");
	}
	if(risPasswordUguali)
	{
		togliErrore(password_r);
	}
	else
	{
		mostraErrore(password_r, "Le password non coincidono");
	}
	
	return risUsername && risNome && risCognome && risPassword && risPasswordUguali;
}

// Funzione di validazione form recensioni
function validazioneForm_recensioni() {
    var titolo = document.getElementById("titolo_recensione");
    var testo = document.getElementById("testo_recensione");

    var risTitolo = checkAlfanumericoESpazi(titolo);
    var risTesto = checkTextarea(testo);

    if (!risTitolo) {
        togliErrore(titolo);
        mostraErrore(titolo, "Il titolo non può contenere caratteri speciali e deve essere almeno lungo 2 caratteri");
    } else {
        togliErrore(titolo);
    }

    if (!risTesto) {
        togliErrore(testo);
        mostraErrore(testo, "Il testo deve essere lungo tra i 10 ed i 200 caratteri e non contenere numeri");
    } else {
        togliErrore(testo);
    }

    return risTitolo && risTesto;
}

//Funzione per la validazione del form di aggiunta prodotti
function validazioneFormAggiuntaProdotti() {
    var prodotto = document.getElementById("prodotto");
    var porzione = document.getElementById("porzione");
	var prezzo = document.getElementById("prezzo");
	
	var risProdotto = checkSoloLettereEDim(prodotto);
	var risPorzione = checkNumeroIntero(porzione);
	var risPrezzo = checkPrezzo(prezzo);
	
	if(risProdotto)
	{
		togliErrore(prodotto);
	}
	else
	{
		mostraErrore(prodotto, "Il nome deve contenere solo lettere e essere almeno lungo 2 caratteri");
	}
	if(risPorzione)
	{
		togliErrore(porzione);
	}
	else
	{
		mostraErrore(porzione, "Il numero dei pezzi deve essere un numero intero");
	}
	if(risPrezzo)
	{
		togliErrore(prezzo);
	}
	else
	{
		mostraErrore(prezzo, "Il prezzo deve essere un numero decimale con al massimo 3 cifre prima della virgola e 2 cifre dopo la virgola");
	}
	
	return risProdotto && risPorzione && risPrezzo;
}

//Funzione per la validazione del form di modifica prodotto
function validazioneFormModificaProdotto() {
    var porzione = document.getElementById("porzione");
	var prezzo = document.getElementById("prezzo");
	
	var risPorzione = checkNumeroIntero(porzione);
	var risPrezzo = checkPrezzo(prezzo);
	
	if(risPorzione)
	{
		togliErrore(porzione);
	}
	else
	{
		mostraErrore(porzione, "Il numero dei pezzi deve essere un numero intero");
	}
	if(risPrezzo)
	{
		togliErrore(prezzo);
	}
	else
	{
		mostraErrore(prezzo, "Il prezzo deve essere un numero decimale con al massimo 3 cifre prima della virgola e 2 cifre dopo la virgola");
	}
	
	return risPorzione && risPrezzo;
}

//Funzione per la validazione del form di pagamento
function validazioneForm_pagamento()
{
    // Controllo il primo fieldset
    var risNomeCognome = false;
	var risVia = false;
	var risCivico = false;
	var risCAP = false;
	var risTel = false;

    var sceltaIndirizzo = document.getElementById("destinazione");
    var indirizzo_val = sceltaIndirizzo.options[sceltaIndirizzo.selectedIndex].text;
    if (indirizzo_val == "Indirizzo")
    {
        var nome_cognome = document.getElementById("nome_cognome");
	    var via = document.getElementById("via");
	    var civico = document.getElementById("civico");
	    var cap = document.getElementById("cap");
	    var tel = document.getElementById("tel");

        risNomeCognome = checkNomeCognome(nome_cognome);
	    risVia = checkAlfanumericoESpazi(via);
	    risCivico = checkCivico(civico);
	    risCAP = checkCAP(cap);
	    risTel = checkSoloNumerieDim(tel);

        if (!risNomeCognome) {
	        mostraErrore(nome_cognome, "Il nome deve contenere solo lettere ed essere lungo almeno due caratteri");
	    }
	    if (!risVia) {
	        mostraErrore(via,"La via non deve contenere caratteri speciali ed essere lunga almeno due caratteri");
	    }
	    if (!risCivico) {
	        mostraErrore(civico,"Il numero civico deve essere del formato corretto (e.g. 4, 4b, 4-b o 4/1)");
	    }
	    if (!risCAP) {
	        mostraErrore(cap,"Il CAP deve essere di Padova e contenere solo numeri");
	    }
	    if (!risTel) {
	        mostraErrore(tel,"Non hai inserito un numero telefonico corretto");
	    }
    }
    else 
    {
        togliErrore(nome_cognome);
        togliErrore(via);
        togliErrore(civico);
        togliErrore(cap);
        togliErrore(tel);

        risNomeCognome = true;
	    risVia = true;
	    risCivico = true;
	    risCAP = true;
	    risTel = true;
    }

    // Controllo il secondo fieldset
    var risIntestatario = false;
	var risNum = false;
    var risMeseScad = true;
	var risAnnoScad = true;
	var risCvv = false;

	var sceltaCarta = document.getElementById("carta_credito");
    var carta_val = sceltaCarta.options[sceltaCarta.selectedIndex].text;
    if (carta_val == "Carta di credito")
    {
        var intestatario = document.getElementById("intestatario_carta");
	    var num_carta = document.getElementById("num_carta");
	    var meseScad = document.getElementsByName("mese_scad")[0];
	    var annoScad = document.getElementsByName("anno_scad")[0];
	    var cvv = document.getElementById("cvv_carta");

        var meseScad_val = meseScad.options[meseScad.selectedIndex].text;
	    var annoScad_val = annoScad.options[annoScad.selectedIndex].text;
	    risMeseScad = meseScad_val == "Mese";
	    risAnnoScad = annoScad_val == "Anno";

	    if (risMeseScad) {
		    togliErrore(meseScad);
		    mostraErrore(meseScad, "Seleziona il mese di scadenza");
	    } else {
		    togliErrore(meseScad);
	    }
	    if (risAnnoScad) {
		    togliErrore(annoScad);
		    mostraErrore(annoScad, "Seleziona l'anno di scadenza");
	    } else {
		    togliErrore(annoScad);
	    }

        risIntestatario = checkNomeCognome(intestatario);
	    risNum = checkSoloNumerieDim(num_carta);
	    risCvv = checkCVV(cvv);
    
	    if (!risIntestatario) {
		    mostraErrore(intestatario, "L'intestatario deve contenere solo lettere ed essere lungo almeno due caratteri");
	    }
	    if (!risNum) {
		    mostraErrore(num_carta, "Non hai inserito un numero della carta corretto");
	    }
	    if (!risCvv) {
		    mostraErrore(cvv, "Il CVV deve essere composto da tre cifre");
	    }
    }
    else
    {
        togliErrore(intestatario);
        togliErrore(num_carta);
        togliErrore(meseScad);
        togliErrore(annoScad);
        togliErrore(cvv);

        risIntestatario = true;
	    risNum = true;
        risMeseScad = false;
	    risAnnoScad = false;
        risCvv = true;
    }

    return risNomeCognome && risVia && risCivico && risCAP && risTel && risNumCarta && risIntestatario && !risMeseScad && !risAnnoScad && risCvv;
}