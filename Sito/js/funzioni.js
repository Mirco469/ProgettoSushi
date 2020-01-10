// CHECK INPUT

function checkData(input) {
    var patt = new RegExp('^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkMinLen(input) {
    var testoInput = input.value;
    if(testoInput.length>1){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkTesto(input) {
    var testoInput = input.value;
    if(testoInput.length>1 && testoInput.length<=150){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkAlfanumericoESpazi(input) {
    var patt = new RegExp('^[a-zA-Z0-9 ]{2,}$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkCivico(input) {
    var patt = new RegExp('^[0-9]{1,}[/-]{0,1}[a-z0-9]{0,1}$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}

function checkAlfanumerico(input) {
    var patt = new RegExp('^[a-zA-Z0-9]{2,}$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkNomeCognome(input) {
    var patt = new RegExp('^[a-zA-Z]{1,}[ ]{1,}[a-zA-Z]{1,}$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkSoloLettereEDim(input) {
    var patt = new RegExp('^[a-zA-Z]{2,}$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
   
        return false;
    }
}
function checkSoloNumerieDim(input) {
    var patt = new RegExp('^[0-9]{2,}$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkCAP(input) {
    var patt = new RegExp('^35(100|121|122|123|124|125|126|127|128|129|131|132|133|134|135|136|137|138|139|141|142|143)$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkSoloNumeri(input) {
    var patt = new RegExp('^[0-9]+$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkNumeroIntero(input) {
    var patt = new RegExp('^[0-9]+$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkPrezzo(input) {
    var patt = new RegExp('^[0-9]{1,3}((.|,)[0-9]{1,2})?$');
    if(patt.test(input.value)){
        togliErrore(input);
        return true;
    }else{
       
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
        mostraErrore(cap,"Non hai inserito un CAP corretto");
    }
    if(!risTel){
        mostraErrore(tel,"Non hai inserito un numero telefonico corretto");
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

