<?php
	require_once("php/dbaccess.php");

	session_start();

	//Tolgo tutte le variabili
	session_unset();
	//Cancello la sessione
    session_destroy();
    //Reindirizzo alla home utente
    redirectHome("Utente");
?>