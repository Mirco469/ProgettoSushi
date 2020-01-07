<?php
	require_once("../php/dbaccess.php");

    session_start();

    // Se non si ha già una sessione attiva si viene reindirizzati verso il login
    if(isset($_SESSION["username"]) && isset($_SESSION["autorizzazione"]))
    {
        //Se non ho l'autorizzazione di admin mando alla pagina 403
        if(strcmp($_SESSION["autorizzazione"],"Admin") == 0)
        {
            $oggettoConnessione =  new DBAccess();
            if($oggettoConnessione->openDBConnection())
            {
                //Messaggio di errore/successo del form aggiutna
                $messaggioAggiunta = "";
                //Campi per il form di aggiunta
                $nomeProdotto = "";
                $categoria = "";
                $numeroPezzi = "";
                $prezzo = "";
                $descrizione = "";

                //Controlla se è stata fatta una post per aggiungere un nuovo prodotto
                if (isset($_POST['aggiungi'])) {
                    $nomeProdotto = htmlentities(trim($_POST['prodotto']));
                    $categoria = htmlentities(trim($_POST['categoria']));
                    $numeroPezzi = htmlentities(trim($_POST['porzione']));
                    $prezzo = htmlentities(trim($_POST['prezzo']));
                    $descrizione = htmlentities(trim($_POST['descrizione']));

                    // Controllo gli input
                    if ($oggettoConnessione->alreadyExistsProdotto($nomeProdotto)) {
                        $messaggioAggiunta .= "<li>Prodotto gi&agrave; esistente</li>";
                    }
                    if (!checkSoloLettereEDim($nomeProdotto))
                    {
                        $messaggioAggiunta .= "<li>Il nome deve contenere solo lettere e essere almeno lungo 2 caratteri</li>";
                    }
                    if (!checkNumeroIntero($numeroPezzi))
                    {
                        $messaggioAggiunta .= "<li>Il numero dei pezzi deve essere un numero intero</li>";
                    }
                    if (!checkPrezzo($prezzo))
                    {
                        $messaggioAggiunta .= "<li>Il prezzo deve essere un numero decimale con al massimo 3 cifre prima della virgola e 2 cifre dopo la virgola</li>";
                    }


                    //Controllo se non ho riscontrato errori
                    if ($messaggioAggiunta == "")
                    {
                        $oggettoConnessione->addProdotto($nomeProdotto, $categoria, $numeroPezzi, $prezzo, $descrizione);
                        $messaggioAggiunta = "<p class='successo'>Prodotto aggiunto con successo!</p>";
                        $nomeProdotto = "";
                        $categoria = "";
                        $numeroPezzi = "";
                        $prezzo = "";
                        $descrizione = "";
                    } else {
                        $messaggioAggiunta = "<ul class='errore'>" . $messaggioAggiunta . "</ul>";
                    }
                }

                $paginaHTML = file_get_contents('html/aggiunta_prodotti.html');
                $formAggiunta = "
                    <div class=\"formGroup\">
                        <label for=\"prodotto\">Nome Prodotto:</label>
                        <input type=\"text\" id=\"prodotto\" name=\"prodotto\" value=\"$nomeProdotto\"/>
                    </div>
                    <div class=\"formGroup\">
                        <label for=\"categoria\">Categoria:</label>
                        <select id=\"categoria\" name=\"categoria\">
                            <option value=\"Antipasti\">Antipasti</option>
                            <option value=\"Primi Piatti\">Primi Piatti</option>
                            <option value=\"Teppanyako e tempure\" lang=\"ja\">Teppanyako e tempure</option>
                            <option value=\"Uramaki\" lang=\"ja\">Uramaki</option>
                            <option value=\"Nigiri ed Onigiri\" lang=\"ja\">Nigiri ed Onigiri</option>
                            <option value=\"Gunkan\" lang=\"ja\">Gunkan</option>
                            <option value=\"Temaki\" lang=\"ja\">Temaki</option>
                            <option value=\"Hosomaki\" lang=\"ja\">Hosomaki</option>
                            <option value=\"Sashimi\" lang=\"ja\">Sashimi</option>
                            <option value=\"Dessert\" lang=\"fr\">Dessert</option>
                        </select>
                    </div>
                    <div class=\"formGroup\">
                        <label for=\"porzione\">Numero pezzi:</label>
                        <input type=\"text\" id=\"porzione\" name=\"porzione\" value=\"$numeroPezzi\"/>
                    </div>
                    <div class=\"formGroup\">
                        <label for=\"prezzo\">Prezzo:</label>
                        <input type=\"text\" id=\"prezzo\" name=\"prezzo\" value=\"$prezzo\"/>
                    </div>
                    <div class=\"formGroup\">
                        <label for=\"descrizione\">Descrizione:</label>
                        <textarea id=\"descrizione\" name=\"descrizione\" rows=\"4\" cols=\"35\">$descrizione</textarea>
                    </div>";


                //Prendo tutti i prodotti e li stampo per categoria
                $listaProdotti = "";
                //Per ogni categoria
                foreach(getCategorie() as $categoriaSingola)
                {
                    $listaProdotti .= "
                    <div class=\"lista_piatti\">
                    <h1>$categoriaSingola</h1>
                    <dl>";
                    //Per ogni prodotto di questa categoria
                    foreach($oggettoConnessione->getProdotti($categoriaSingola) as $singoloProdotto)
                    {
                        $nomeP = $singoloProdotto["Nome"];
                        $pezziP = $singoloProdotto["Pezzi"];
                        $prezzoP = $singoloProdotto["Prezzo"];
                        $descrizioneP = $singoloProdotto["Descrizione"];
                        $listaProdotti .= "
                            <dt>$nomeP<a class=\"buttonSmall\" href=\"modifica_prodotto.php?nome=$nomeP\">Modifica</a></dt>
                            <dd>$prezzoP &euro;</dd>
                            <dd><span>[$pezziP<abbr title=\"Pezzi\">pz</abbr>]</span> $descrizioneP</dd>";
                    }
                    $listaProdotti .= "
                    </dl>
		            </div>";
                }


                $paginaHTML = str_replace('<messaggioAggiunta />', $messaggioAggiunta, $paginaHTML);
                $paginaHTML = str_replace('<formAggiunta />', $formAggiunta, $paginaHTML);
                $paginaHTML = str_replace('<listaProdotti />', $listaProdotti, $paginaHTML);
                echo $paginaHTML;
            }
            else
            {
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
            }
        }
        else
        {
            header('location: ../errore403.php'); /* CONTROLLARE SE LA PAGINA E' GIUSTA */
        }
    }
    else
    {
        header('location: ../login.php');
    }
?>