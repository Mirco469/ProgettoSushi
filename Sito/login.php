<?php
	require_once("php/dbaccess.php");

    session_start();

    //session_unset();
    //session_destroy();

    // Se si ha già una sessione attiva si viene reindirizzati verso la home
    if(isset($_SESSION["username"]) && isset($_SESSION["autorizzazione"]))
    {
        //Mando alla home giusta in base all'autorizzazione
        redirectHome($_SESSION["autorizzazione"]);
    }
    else
    {
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
            $usernameL = trim($_POST['username']);
            $passwordL = trim($_POST['password']);

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
                    $erroreL = "<p class='errore'>L'username o la password non sono corretti!</p>"; /*CLASSE errore DA CREARE*/
                }
            }
            else
            {
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
            }
        }
        elseif(isset($_POST['registrati']))
        {
            $usernameR = trim($_POST['username']);
            $nomeR = trim($_POST['nome']);
            $cognomeR = trim($_POST['cognome']);
            $passwordR = trim($_POST['password']);
            $passwordRepeatR = trim($_POST['passwordRepeat']);

            // Controllo gli input
            $oggettoConnessione =  new DBAccess();
            if($oggettoConnessione->openDBConnection())
            {
                if($oggettoConnessione->alreadyExistsUsername($usernameR))
                {
                    $erroreR .= "<li>Username gi&agrave; utilizzato</li>";
                }
                if(!checkAlfanumerico($usernameR))
                {
                    $erroreR .= "<li>L'username deve contenere solo caratteri alfanumerici</li>";
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
                    $erroreR .= "<li>La password deve essere lunga almeno due caratteri</li>";
                }

                if(strcmp($passwordR,$passwordRepeatR)!=0)
                {
                    $erroreR .= "<li>Le password non coincidono</li>";
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
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
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
    }
?>