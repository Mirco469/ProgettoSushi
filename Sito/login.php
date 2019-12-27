<?php
	require_once("php/dbaccess.php");

    session_start();

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
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
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
                    $erroreR .= "<li>L'<span lang='en'>username</span> deve contenere solo caratteri alfanumerici e avere almeno 2 caratteri</li>";
                }
                if(!checkSoloLettereEDim($nomeR))
                {
                    $erroreR .= "<li>Il nome deve contenere solo lettere  e avere almeno 2 caratteri</li>";
                }
                if(!checkSoloLettereEDim($cognomeR))
                {
                    $erroreR .= "<li>Il cognome deve contenere solo lettere  e avere almeno 2 caratteri</li>";
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
                header("Location: /errore500.php"); /*CONTROLLARE SE LA PAGINA E' GIUSTA*/
            }
        }

        $paginaHTML = file_get_contents('html/login.html');

        //Form di login
        $formLoginContent = "
                    <label for=\"nomeUtente\">Nome Utente:</label>
                    <input type=\"text\" id=\"nomeUtente\" name=\"username\" value=\"$usernameL\"/>
                    <label for=\"passwordAcc\" lang=\"en\">Password:</label>
                    <input type=\"password\" id=\"passwordAcc\" name=\"password\" value=\"$passwordL\"/>
                    <input class=\"defaultButton\" type=\"submit\" name=\"accedi\" value=\"Accedi\"/>";

        //Form di registrazione
        $formRegistrazioneContent = "
                    <label for=\"username\">Nome utente:</label>
                    <input type=\"text\" id=\"username\" name=\"username\" value=\"$usernameR\"/>
                    <label for=\"nome\">Nome:</label>
                    <input type=\"text\" id=\"nome\" name=\"nome\" value=\"$nomeR\"/>
                    <label for=\"cognome\">Cognome:</label>
                    <input type=\"text\" id=\"cognome\" name=\"cognome\" value=\"$cognomeR\"/>
                    <label for=\"passwordReg\" lang=\"en\">Password:</label>
                    <input type=\"password\" id=\"passwordReg\" name=\"password\" value=\"$passwordR\"/>
                    <label for=\"passwordRepeat\">Ripeti la <span lang=\"en\">password:</span></label>
                    <input type=\"password\" id=\"passwordRepeat\" name=\"passwordRepeat\" value=\"$passwordRepeatR\"/>
                    <input class=\"defaultButton\" type=\"submit\" name=\"registrati\" value=\"Registrati\"/>";

        $paginaHTML = str_replace('<erroreLogin />', $erroreL, $paginaHTML);
        $paginaHTML = str_replace('<formLogin />', $formLoginContent, $paginaHTML);
        $paginaHTML = str_replace('<erroreRegistrazione />', $erroreR, $paginaHTML);
        echo str_replace('<formRegistrazione />', $formRegistrazioneContent, $paginaHTML);
    }
?>