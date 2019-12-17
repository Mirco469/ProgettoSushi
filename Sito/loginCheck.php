<?php

	require_once("php/dbaccess.php");
	
	$oggettoConnessione =  new DBAccess();
	
	$connessioneOK = $oggettoConnessione->openDBConnection();
	
	if($connessioneOK)
	{
	    //Controllo se il bottone che ha fatto submit è accedi o registrati
	    if(isset($_POST["accedi"]))
	    {
            //Login
            if($oggettoConnessione->checkLogin($_POST["username"],$_POST["password"]))
            {
                echo "evvai";
            }
            else
            {
                echo "Mah";
            }
        }
	    else
        {
            //Registrazione
        }
    }
	else
	{
		//STAMPA MESSAGGIO ERRORE
	}

?>