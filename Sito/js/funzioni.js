// CHECK INPUT

function checkData(input) {
    var patt = new RegExp('^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkMinLen(input) {
    testoInput = input.textContent;
    if(testoInput>1)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkTesto(input) {
  testoInput = input.textContent;
    if(testoInput>1)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkAlfanumericoESpazi(input) {
    var patt = new RegExp('^[a-zA-Z0-9 ]{2,}$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}

function checkAlfanumerico(input) {
    var patt = new RegExp('^[a-zA-Z0-9]{2,}$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkNomeCognome(input) {
    var patt = new RegExp('^[a-zA-Z]{1,}[ ]{1,}[a-zA-Z]{1,}$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkSoloLettereEDim(input) {
    var patt = new RegExp('^[a-zA-Z]{2,}$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
   
        return false;
    }
}
function checkSoloNumerieDim(input) {
    var patt = new RegExp('^[0-9]{2,}$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkCAP(input) {
    var patt = new RegExp('^35(100|121|122|123|124|125|126|127|128|129|131|132|133|134|135|136|137|138|139|141|142|143)$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}
function checkSoloNumeri(input) {
    var patt = new RegExp('^[0-9]+$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkNumeroIntero(input) {
    var patt = new RegExp('^[0-9]+$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
        
        return false;
    }
}
function checkPrezzo(input) {
    var patt = new RegExp('^[0-9]{1,3}((.|,)[0-9]{1,2})?$');
    if(patt.test(nomeInput.value)){
        togliErrore(input);
        return true;
    }else{
       
        return false;
    }
}

//FUNZIONI PER MOSTRARE E RIMUOVERE ERRORI

function mostraErrore(input, testoErrore) { //mostra un messaggio di errore per un determinato input
    togliErrore(input);
    var p = input.parentNode;
    var span = document.createElement("span");
    span.className = "errore";
    span.appendChild(document.createTextElement(testoErrore));
    p.appendChild(span);    
}

function togliErrore(input) {
    var p = input.parentNode;
    if(p.children.length > 2){
        p.removeChild(p.children[2]);
    }
}



