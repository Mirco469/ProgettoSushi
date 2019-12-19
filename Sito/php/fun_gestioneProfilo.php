<?php
    require_once("dbConnection.php");
    use DB\BDAccess;
// va fatto:
// session_start();
// if (!isset($_SESSION['count'])) {     //creazione di una nuova variabile di sessione
//    $_SESSION['count'] = 0; } else {  //uso di una variabile di sessione
//    $_SESSION['count']++;
// }
// $_SESSION['nome_utente'] = 'nomeDellUtente';





//Controlla se viene inserito un CAP di Padova
function checkCAP($string) {
    if(!preg_match('/35(100|121|122|123|124|125|126|127|128|129|131|132|133|134|135|136|137|138|139|141|142|143)/', $string)){
        return false;
    } else return true;
}
//Controlla se la carta di credito inserita è valida
function checkCreditCard($cc) {
    if(!preg_match('^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$', $cc)){
        return false;
    } else return true;
}
//Controlla che la stringa sia lunga almeno due caratteri
function checkMinLen($string) {
    if(strlen($string)<2){
        return false;
    }else {
        return true;
    }
}

//Controlla che la stringa non contenga caratteri speciali
function checkAlfanumerico($string) {
    if(!checkMinLen($string)) return false;
    if (!preg_match('/[^A-Za-z0-9]/', $string)) {
        return false;
    } else return true;
}

//Controlla che la stringa non contenga numeri e abbia almeno due caratteri
function checkSoloLettereEDim($string) {
    if(!checkMinLen($string)) return false;
    if (!preg_match('/[^A-Za-z]/', $string)) {
        return false;
    } else return true;
}

//Controlla che la stringa contenga solo numeri e che sia lunga almeno due caratteri
function checkSoloNumerieDim($string){
    if(!checkMinLen($string)) return false;
    if (!preg_match('/[^0-9]/', $string)) {
        return false;
    } else return true;
}

function cambioPassw($nome_utente, $old_psw, $v_password, $n_password, $c_password) {

    $query = $this->connection->prepare('SELECT * from Utente WHERE username = ?');
    $query -> bind_param('s', $nome_utente);
    $query->execute();
    $result = $query->get_result();
    $paginaHTML = file_get_contents('gestione_profilo_utente.html');
    if(mysqli_num_rows($result)==0) {
        str_replace('<messaggio />', '<p class="errore">C\'&egrave; stato un errore nel database, contattare l\'amministratore del sistema</p>', $paginaHTML);
        return false;
    }

    $strErr="";

    if($v_password !== $old_psw) {
        $strErr = '<li> La <span lang="en">password</span> che hai inserito non coincide con quella salvata</li>';
    }

    if(!checkMinLen($n_password)||!checkMinLen($c_password)||$n_password !== $c_password) {
        $strErr .= '<li> Le due nuove <span lang="en">password</span> non coincidono o non sono lunghe almeno 2 caratteri</li>';
    }
    if(strlen($strErr)>0){
        $strErr = '<ul class = "errore">'. $strErr.'</ul>';
        echo str_replace("<messaggio />", $strErr, $paginaHTML);
        return false;
    }else {
        $v_password = "";
        $n_password = "";
        $c_password = "";
        $query = $this->connection->prepare("UPDATE Utente SET password =?  WHERE username = ? ");
        $query -> bind_param('ss', $n_password, $nome_utente);
        $query->execute();
        $resultq = $query->get_result();
        if(!$resultq) {
            str_replace('<messaggio />', '<p class="errore">C\'&egrave; stato un errore nel database, contattare l\'amministratore del sistema</p>', $paginaHTML);
            return false;
        } else{
            str_replace('<messaggio />', '<p class = "successo>">La <span lang="en">password</span> &grave; stata aggiornata!</p>', $paginaHTML);
            return true;
        }

    }

}

public function saveInfoSpedizione($nome_utente, $nome_cognome, $indirizzo, $civico, $cap, $tel) {

    $paginaHTML = file_get_contents('gestione_profilo_utente.html');
    $strErrori="";

    if (!checkSoloLettereEDim($nome_cognome)) {
        $strErrori .= '<li>Il nome dell\'intestatario può contenere solo lettere e contenere almeno due caratteri</li>';
    }
    if (!checkSoloLettereEDim($indirizzo)) {
        $strErrori .= '<li>L\'indirizzo può contenere solo lettere e contenere almeno due caratteri</li>';
    }
    if (!checkAlfanumerico($civico)) {
        $strErrori .= '<li>Il numero civico può contenere solo caratteri alfanumerici</li>';
    }
    if (!checkCAP($cap)) {
        $strErrori .= '<li>Inserire un codice di avviamento postale del comune di Padova valido</li>';
    }
    if (!checkSoloNumerieDim($tel)) {
        $strErrori .= '<li>Inserire un numero telefonico valido</li>';
    }

    if(str_len($strErrori)!=0){
        $nome_cognome = "";
        $indirizzo = "";
        $civico = "";
        $cap = "";
        $tel = "";

        $query = $this->connection->prepare("INSERT into Destinazione ('nome_cognome', 'numero_telefonico', 'CAP', 'via', 'numero_civico', 'utente') VALUES ('".$nome_cognome."', '".$tel."', '".$cap."', '".$indirizzo."', '".$civico."', '".$nome_utente."')");
        $query->execute();
        $result = $query->get_result();
        if(!$result){
            str_replace('<messaggio />', '<p class="errore">C\'&egrave; stato un errore nel database, contattare l\'amministratore del sistema</p>', $paginaHTML);
            return false;
        }else{
            str_replace('<messaggio />', '<p class = "successo>">La nuova destinazione &grave; stata inserita!</p>', $paginaHTML);
            return true;
        }
    }else {
        $strErrori = '<ul class = "errore">'.$strErrori.'</ul>';
        str_replace('<messaggio />', $strErrori, $paginaHTML);
        return false;
    }
}

public function saveInfoPagamento($nome_utente, $intestatario, $num_carta, $mese_scad, $anno_scad) {

    $paginaHTML = file_get_contents('gestione_profilo_utente.html');
    $strErrori="";

    if (!checkSoloLettereEDim($intestatario)) {
        $strErrori .= '<li>Il nome dell\'intestatario deve contenere solo lettere ed essere almeno lungo due caratteri</li>';
    }
    if (!checkCreditCard($num_carta)) {
        $strErrori .= '<li>Inserire una carta di credito valida</li>';
    }
    if($mese_scad === '- Mese -') {
        $strErrori .= '<li>Selezionare un mese</li>';
    }
    if($anno_scad === '- Anno -') {
        $strErrori .= '<li>Selezionare un anno</li>';
    }


    if(str_len($strErrori)!=0){
        $num_carta = "";
        $intestatario = "";
        $mese_scad = "- Mese -";
        $anno_scad = "- Anno -";

        $query = $this->connection->prepare("UPDATE Utente SET numero_carta = ?, intestatario = ?, scadenza ='".$anno_scad."-".$mese_scad."-"."00"."' WHERE username = '".$nome_utente."'");
        $query -> bind_param('ss', $num_carta, $intestatario);
        $query->execute();
        $result = $query->get_result();
        if(!$result){
            str_replace('<messaggio />', '<p class="errore">C\'&egrave; stato un errore nel database, contattare l\'amministratore del sistema</p>', $paginaHTML);
            return false;
        }else {
            str_replace('<messaggio />', '<p class = "successo>">Il metodo di pagamento &grave; stato inserito con successo!</p>', $paginaHTML);
            return true;
        }
    }else {
        $strErrori = '<ul class = "messaggio">'.$strErrori.'</ul>';
        str_replace('<messaggio />', $strErrori, $paginaHTML);
        return false;
    }
}
