<?php
	require_once("../php/dbaccess.php");

    session_start();

    // Se non si ha già una sessione attiva si viene reindirizzati verso il login
    if(isset($_SESSION["username"]) && isset($_SESSION["autorizzazione"]))
    {
        //Se non ho l'autorizzazione di admin mando alla pagina 403
        if(strcmp($_SESSION["autorizzazione"],"Admin") == 0)
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
            if(isset($_POST['aggiungi']))
            {
                $nomeProdotto = htmlentities(trim($_POST['prodotto']));;
                $categoria = htmlentities(trim($_POST['categoria']));;
                $numeroPezzi = htmlentities(trim($_POST['porzione']));;
                $prezzo = htmlentities(trim($_POST['prezzo']));;
                $descrizione = htmlentities(trim($_POST['descrizione']));;
            }
            else
            {

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
                        <option value=\"Nigiri e Onigiri\" lang=\"ja\">Nigiri e Onigiri</option>
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

            $paginaHTML = str_replace('<messaggioAggiunta />', $messaggioAggiunta, $paginaHTML);
            $paginaHTML = str_replace('<formAggiunta />', $formAggiunta, $paginaHTML);
            echo $paginaHTML;
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
    /*
        //Messaggi di errore rispettivamente per login e registrazione
        $erroreL = "";
        $erroreR = "";
        //Campi per il form di login
        $usernameL = "";
        $passwordL = "";
        //Campi per il form di registrazione
        $usernameR = "";
        $nomeR = "";
        $cognomeR = "";
        $passwordR = "";
        $passwordRepeatR = "";

        //Controllo se ho ricevuto la post dalla form di login o di registrazione o è la prima volta
        if(isset($_POST['accedi']))
        {
            $usernameL = htmlentities(trim($_POST['username']));
            $passwordL = htmlentities(trim($_POST['password']));

            //Mi connetto al database
            $oggettoConnessione =  new DBAccess();

            if($oggettoConnessione->openDBConnection())
            {
                //Controllo se l'username e password ci sono del DB
                $autorizzazione = $oggettoConnessione->checkLogin($usernameL,$passwordL);
                if(isset($autorizzazione))
                {
                    $_SESSION["username"] = $usernameL;
                    $_SESSION["autorizzazione"] = $autorizzazione;
                    redirectHome($_SESSION["autorizzazione"]);
                }
                else
                {
                    $erroreL = "<p class='errore'>L'<span lang='en'>username</span> o la <span lang='en'>password</span> non sono corretti!</p>";
                }
            }
            else
            {
                header("Location: /errore500.php"); CONTROLLARE SE LA PAGINA E' GIUSTA
            }
        }
        elseif(isset($_POST['registrati']))
        {
            $usernameR = htmlentities(trim($_POST['username']));
            $nomeR = htmlentities(trim($_POST['nome']));
            $cognomeR = htmlentities(trim($_POST['cognome']));
            $passwordR = htmlentities(trim($_POST['password']));
            $passwordRepeatR = htmlentities(trim($_POST['passwordRepeat']));

            // Controllo gli input
            $oggettoConnessione =  new DBAccess();
            if($oggettoConnessione->openDBConnection())
            {
                if($oggettoConnessione->alreadyExistsUsername($usernameR))
                {
                    $erroreR .= "<li><span lang='en'>Username</span> gi&agrave; utilizzato</li>";
                }
                if(!checkAlfanumerico($usernameR))
                {
                    $erroreR .= "<li>L'<span lang='en'>username</span> deve contenere solo caratteri alfanumerici</li>";
                }
                if(!checkSoloLettereEDim($nomeR))
                {
                    $erroreR .= "<li>Il nome deve contenere solo lettere</li>";
                }
                if(!checkSoloLettereEDim($cognomeR))
                {
                    $erroreR .= "<li>Il cognome deve contenere solo lettere</li>";
                }

                if(!checkMinLen($passwordR))
                {
                    $erroreR .= "<li>La <span lang='en'>password</span> deve essere lunga almeno due caratteri</li>";
                }

                if(strcmp($passwordR,$passwordRepeatR)!=0)
                {
                    $erroreR .= "<li>Le <span lang='en'>password</span> non coincidono</li>";
                }

                //Controllo se non ho riscontrato errori
                if($erroreR == "")
                {
                    $oggettoConnessione->addAccount($usernameR,$nomeR,$cognomeR,$passwordR);
                }
                else
                {
                    $erroreR = "<ul class='errore'>".$erroreR."</ul>";
                }
            }
            else
            {
                header("Location: /errore500.php"); CONTROLLARE SE LA PAGINA E' GIUSTA
            }
        }

        $paginaHTML = file_get_contents('html/login.html');

        //Form di login
        $formLoginContent = "
                    <label for=\"nomeUtente\">Nome Utente:</label>
                    <input type=\"text\" id=\"nomeUtente\" name=\"username\" placeholder=\"Utente\" required=\"required\" value=\"$usernameL\"/>
                    <label for=\"passwordAcc\" lang=\"en\">Password:</label>
                    <input type=\"password\" id=\"passwordAcc\" name=\"password\" placeholder=\"Password\" required=\"required\" value=\"$passwordL\"/>
                    <input class=\"defaultButton\" type=\"submit\" name=\"accedi\" value=\"Accedi\"/>";

        //Form di registrazione
        $formRegistrazioneContent = "
                    <label for=\"username\">Nome utente:</label>
                    <input type=\"text\" id=\"username\" name=\"username\" placeholder=\"Utente\" required=\"required\" value=\"$usernameR\"/>
                    <label for=\"nome\">Nome:</label>
                    <input type=\"text\" id=\"nome\" name=\"nome\" placeholder=\"Nome\" required=\"required\" value=\"$nomeR\"/>
                    <label for=\"cognome\">Cognome:</label>
                    <input type=\"text\" id=\"cognome\" name=\"cognome\" placeholder=\"Cognome\" required=\"required\" value=\"$cognomeR\"/>
                    <label for=\"passwordReg\" lang=\"en\">Password:</label>
                    <input type=\"password\" id=\"passwordReg\" name=\"password\" required=\"required\" value=\"$passwordR\"/>
                    <label for=\"passwordRepeat\">Ripeti la <span lang=\"en\">password:</span></label>
                    <input type=\"password\" id=\"passwordRepeat\" name=\"passwordRepeat\" required=\"required\" value=\"$passwordRepeatR\"/>
                    <input class=\"defaultButton\" type=\"submit\" name=\"registrati\" value=\"Registrati\"/>";

        $paginaHTML = str_replace('<erroreLogin />', $erroreL, $paginaHTML);
        $paginaHTML = str_replace('<formLogin />', $formLoginContent, $paginaHTML);
        $paginaHTML = str_replace('<erroreRegistrazione />', $erroreR, $paginaHTML);
        echo str_replace('<formRegistrazione />', $formRegistrazioneContent, $paginaHTML);
    */
?>