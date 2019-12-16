<?php
include "DBAccess.php";
// va fatto:
// session_start();
// if (!isset($_SESSION['count'])) {     //creazione di una nuova variabile di sessione
//    $_SESSION['count'] = 0; } else {  //uso di una variabile di sessione
//    $_SESSION['count']++;
// }
// $_SESSION['nome_utente'] = 'nomeDellUtente';



public $connection = null;
//openDBConnection();   //DA FARE GESTIONE DELL'ERRORE

public function cambioPassw() {
    $nome_utente = $_SESSION['nome_utente'];
    $query = "SELECT from Utente WHERE username = $nome_utente";
    $result = mysqli_query($this->connection, $query);
    $old_psw = $result['password'];

    $v_password = $_POST['v_password'];
    if($v_password !== $old_psw) {
        return -1;                     //SE LA PASSWORD VECCHIA NON COINCIDE RITORNO -1
    }
    $n_password = $_POST['password'];
    //DA FARE VARI CONTROLLI SULLA PASSWORD
    $result1 = mysqli_query($this->connection, "INSERT into Utente ('password') WHERE (username = $nome_utente) VALUES ($n_password) ");
    if($result1){
        //POPUP O ALTRO PER SEGNALARE L'AVVENUTO INSERIMENTO
    } else{
        //POPUP O ALTRO PER SEGNALARE L'ERRORE
    }
}

public function saveInfoSpedizione() {

    $nome_utente = $_SESSION['nome_utente'];
    $nome_cognome = $_POST['nome_cognome'];
    $indirizzo = $_POST['indirizzo'];
    $civico = $_POST['civico'];
    $cap = $_POST['cap'];
    $tel = $_POST['tel'];

    //COME PRENDERE ID DELLA DESTINAZIONE????
    $query = "INSERT into Destinazione VALUES ('666', '$nome_cognome', '$tel', '$cap', '$indirizzo', '$civico', '$nome_utente')";
    $result = mysqli_query($this->connection,$query);
        if($result){
            //POPUP O ALTRO PER SEGNALARE L'AVVENUTO INSERIMENTO
        } else{
            //POPUP O ALTRO PER SEGNALARE L'ERRORE
        }

}

public function saveInfoPagamento() {
    $nome_utente = $_SESSION['nome_utente'];
    $intestatario = $_POST['intestatario_carta'];
    $num_carta = $_POST['num_carta'];
    //VA CREATA UNA TABELLA PER LE CARTE DI CREDITO OPPURE NON CREDO SI POSSA AVERE PIU DI UNA CARTA

}
