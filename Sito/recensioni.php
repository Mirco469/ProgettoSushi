<?php

	require_once("php/dbaccess.php");

    session_start();

    $oggettoConnessione =  new DBAccess();

	if ($oggettoConnessione->openDBConnection())
	{
        $paginaHTML = file_get_contents('html/recensioni.html');
        $menu = getmenu();
	    $paginaHTML = str_replace('<menu />', $menu, $paginaHTML);
        
        $form = "";
        $messaggio = "";
        $titolo = "";
        $testo = "";

        if (isset($_SESSION["username"]))
        {
            if(isset($_POST['invia']))
            {
                $titolo = htmlentities(trim($_POST['titolo']));
                $testo = htmlentities(stripslashes(trim($_POST['testo'])));

                if (!checkTestoSpaziDim($titolo, 6))
                {
                    $messaggio .= "<li>Il titolo deve contenere solo lettere e spaziature interne ed essere almeno lungo 6 caratteri</li>";
                }
                if (!checkTextArea($testo))
                {
                    $messaggio .= "<li>Il testo deve essere lungo tra i 10 ed i 200 caratteri</li>";
                }

                if ($messaggio == "")
                {
                    $data = getdate();
                    $data = "$data[year]-$data[mon]-$data[mday]";
                    $oggettoConnessione->addRecensione($titolo, $data, $_SESSION["username"], $testo);
                    $messaggio = "<p class='successo'>Recensione aggiunta con successo!</p>";
                    $titolo = "";
                    $testo = "";
                }
                else
                {
                    $messaggio = "<ul class='errore'>" . $messaggio . "</ul>";
                }
            }
            
            $form = "
                <form action=\"recensioni.php\" method=\"post\">
                    <fieldset>
                        <legend>La tua recensione</legend>
                        <messaggio />
                        <label for=\"titolo_recensione\">Titolo: </label>
                        <input type=\"text\" id=\"titolo_recensione\" name=\"titolo\" value=\"$titolo\" />
	  		            <label for=\"testo_recensione\">Testo: </label>
                        <textarea id=\"testo_recensione\" name=\"testo\" rows=\"5\" cols=\"85\">$testo</textarea>
                        <input class=\"defaultButton\" type=\"submit\" name=\"invia\" value=\"Invia\" />
                    </fieldset>
                </form>
                ";
        }
        $paginaHTML = str_replace('<formRecensione />', $form, $paginaHTML);
        $paginaHTML = str_replace('<messaggio />', $messaggio, $paginaHTML);

        $listaRecensioni = "
    	    <div id=\"lista_recensioni\">
    		<dl>";
        foreach($oggettoConnessione->getRecensioni() as $recensione)
        {
            $titoloR = $recensione["Titolo"];
            $dataR = $recensione["Data"];
            $utenteR = $recensione["Utente"];
            $testoR = $recensione["Testo"];
            $listaRecensioni .= "
            <dt>$titoloR</dt>
            <dd>$dataR</dd>
            <dd>$utenteR</dd>
			<dd>$testoR</dd>";
        }
        $listaRecensioni .= "
            </dl>
		    </div>";
        
        $paginaHTML = str_replace('<listaRecensioni />', $listaRecensioni, $paginaHTML);
        echo $paginaHTML;
    }
    else
	{
		header("Location: /errore500.php");
	}
    
?>