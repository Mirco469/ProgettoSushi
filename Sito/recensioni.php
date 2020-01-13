<?php

	require_once("php/dbaccess.php");

    session_start();

    $db =  new DBAccess();

	if ($db->openDBConnection())
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

                if (!checkAlfanumericoESpazi($titolo))
                {
                    $messaggio .= "<li>Il titolo non può contenere caratteri speciali e deve essere almeno lungo 2 caratteri</li>";
                }
                if (!checkTextArea($testo))
                {
                    $messaggio .= "<li>Il testo deve essere lungo tra i 10 ed i 200 caratteri e non contenere numeri</li>";
                }

                if ($messaggio == "")
                {
                    $data = getdate();
                    $data = "$data[year]-$data[mon]-$data[mday]";
                    $db->addRecensione($titolo, $data, $_SESSION["username"], $testo);
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
                        <p>
                        <label for=\"titolo_recensione\">Titolo: </label>
                        <input type=\"text\" id=\"titolo_recensione\" name=\"titolo\" value=\"$titolo\" />
	  		            </p>
			            <p>
                        <label for=\"testo_recensione\">Testo: </label>
                        <textarea id=\"testo_recensione\" name=\"testo\" rows=\"5\" cols=\"85\">$testo</textarea>
                        </p>
                        <input class=\"defaultButton\" type=\"submit\" name=\"invia\" value=\"Invia\" onclick=\"return validazioneForm_recensioni();\"/>
                    </fieldset>
                </form>
                ";
        }
        $paginaHTML = str_replace('<formRecensione />', $form, $paginaHTML);
        $paginaHTML = str_replace('<messaggio />', $messaggio, $paginaHTML);

        $listaRecensioni = "
    	    <div id=\"lista_recensioni\">
    		<dl>";
        foreach($db->getRecensioni() as $recensione)
        {
            $titoloR = $recensione["Titolo"];
            $dataR = $recensione["Data"];
            $utenteR = $recensione["Utente"];
            $testoR = htmlentities($recensione["Testo"]);
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
		header("Location: errore500.php");
	}
?>