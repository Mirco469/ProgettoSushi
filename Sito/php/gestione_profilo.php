<?php

// va fatto:
// session_start();
// if (!isset($_SESSION['count'])) {     //creazione di una nuova variabile di sessione
//    $_SESSION['count'] = 0; } else {  //uso di una variabile di sessione
//    $_SESSION['count']++;
// }
// $_SESSION['nome_utente'] = 'nomeDellUtente';




/*  Funzione cambio password
    Ritorno:
    1 se la password viene aggiornata correttamente
    0 se la nuova password non contiene almeno due caratteri dopo aver rimosso gli spazi all'inizio e alla fine
    -1 se la vecchia password inserita non coincide con quella salvata nel server
    -2 se la nuova password inserita non coincide con la conferma
    -3 se avviene un errore nell'inserimento nel database
 */
public function cambioPassw() {
    $nome_utente = $_SESSION['nome_utente'];
    $query = "SELECT from Utente WHERE username = $nome_utente";
    $result = mysqli_query($this->connection, $query);
    $old_psw = $result['password'];

    $v_password = $_POST['v_password'];
    if($v_password !== $old_psw) {
        return -1;                     //Se la password vecchia non coincide ritorno -1
    }

    $n_password = trim($_POST['password']);
    $c_password = trim($_POST['c_password']);
    if($n_password !== $c_password) {
        return -2
    }
    if(strlen($n_password)<2){
        return 0;                      //Se la password non è lunga almeno due caratteri ritorno 0
    }
    $result1 = mysqli_query($this->connection, "UPDATE Utente SET password =''".$n_password."'  WHERE username = '".$nome_utente."' ");
    if($result1) {
        return 1;
    } else {
        return -3;
    }
}
/*  Funzione salva informazioni spedizione
    Ritorna un array che contiene 0 nelle posizioni associate ad un campo contenente un errore
    Le chiavi sono:
    - nome_cognome
    - indirizzo
    - civico
    - cap
    - tel
    - ErrDB (indica il caso in cui avviene un errore durante l'esecuzione della query di inserimento)
 * */

public function saveInfoSpedizione() {

    $nome_utente = $_SESSION['nome_utente'];
    $nome_cognome = trim($_POST['nome_cognome']);
    $indirizzo = trim($_POST['indirizzo']);
    $civico = trim($_POST['civico']);
    $cap = trim($_POST['cap']);
    $tel = trim($_POST['tel']);
    $arrayErrori = array(
        'nome_cognome' => 1,
        'indirizzo' => 1,
        'civico' => 1,
        'cap' => 1,
        'tel' => 1,
        'errDB' =>1);

    if (strlen($nome_cognome)<1) {
        $arrayErrori['nome_cognome'] = 0;
    }elseif (strlen($indirizzo)<1) {
        $arrayErrori['indirizzo'] = 0;
    }elseif (strlen($civico)<1) {
        $arrayErrori['civico'] = 0;
    }elseif (strlen($cap)<1) {
        $arrayErrori['cap'] = 0;
    }elseif (strlen($tel)<1) {
        $arrayErrori['tel'] = 0;
    }

    if(in_array(0, $arrayErrori)){
        return $arrayErrori;
    }



    //VEDERE AUTO-INCREMENT

    $query = "INSERT into Destinazione VALUES ('???', '$nome_cognome', '$tel', '$cap', '$indirizzo', '$civico', '$nome_utente')";
        $result = mysqli_query($this->connection, $query);
            if($result === false){
                $arrayErrori['errDB'] = 0;
            }
    return $arrayErrori;

}
/*Funzione salva informazioni spedizione
    Ritorna un array che contiene 0 nelle posizioni associate ad un campo contenente un errore
    Le chiavi sono:
    - intestatario
    - num_carta
    - mese_scad
    - anno_scad
    - ErrDB (indica il caso in cui avviene un errore durante l'esecuzione della query di inserimento)
*/
public function saveInfoPagamento() {
    $nome_utente = $_SESSION['nome_utente'];
    $intestatario = trim($_POST['intestatario_carta']);
    $num_carta = trim($_POST['num_carta']);
    $mese_scad = trim($_POST['mese_scad']);
    $anno_scad = trim($_POST['anno_scad']);

    $arrayErrori = array(
        'intestatario' => 1,
        'num_carta' => 1,
        'mese_scad' => 1,
        'anno_scad' => 1,
        'errDB' =>1);

    if (strlen($intestatario)<1) {
        $arrayErrori['intestatario'] = 0;
    }elseif (strlen($num_carta)<1) {
        $arrayErrori['num_carta'] = 0;
    }elseif (strlen($mese_scad)<1) {
        $arrayErrori['mese_scad'] = 0;
    }elseif (strlen($anno_scad)<1) {
        $arrayErrori['anno_scad'] = 0;
    }

    if(in_array(0, $arrayErrori)){
        return $arrayErrori;
    }

    $query = "UPDATE Utente SET numero_carta ='".$num_carta."' , intestatario = '".$intestatario."', scadenza='".$anno_scad."-".$mese_scad."-"."00"."'";
    $result = mysqli_query($this->connection, $query);
    if($result === false){
        $arrayErrori['errDB'] = 0;
    }
    return $arrayErrori;
}
