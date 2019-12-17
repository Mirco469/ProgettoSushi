<?php

	require_once("php/dbaccess.php");
	
	$oggettoConnessione =  new DBAccess();
	
	$connessioneOK = $oggettoConnessione->openDBConnection();
	
	if($connessioneOK)
	{
		echo "Ci siamo";
	}
	else
	{
		//STAMPA MESSAGGIO ERRORE
	}

?>