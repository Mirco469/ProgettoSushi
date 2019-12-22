<?php
    require_once("../php/dbaccess.php");
    $db = new DBAccess();
    //********* DA RIMUOVERE *****************
    session_start();
    $_SESSION['autorizzazione'] = 'admin';
    $_SESSION['username'] = 'admin';
    //****************************************
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if($db->openDBConnection()){

            $titolo = 'Inserire un titolo';
            $data = 'AAAA-MM-GG';
            $testo = 'Inserire testo';
            $erroriNews = '';

            if (isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione'] == 'admin') {



                if (isset($_POST['inserisci'])) {
                    $titolo = htmlentities(trim($_POST['titolo']));
                    $data = htmlentities(trim($_POST['data']));
                    $testo =  trim($_POST['notizia']);

                    if(!checkAlfanumerico($titolo)){
                        $erroriNews .= '<li>Il titolo deve contenere almeno due caratteri e non caratteri speciali</li>';
                    }
                    if(!checkData($data)){
                        $erroriNews .= '<li>La data non ha il formato corretto (AAAA-MM-GG)</li>';
                    }
                    if(!checkAlfanumerico($testo)){
                        $erroriNews .= '<li>Il testo deve contenere almeno due caratteri e non caratteri speciali</li>';
                    }
                    if(strlen($testo)>150){
                        $erroriNews .= '<li>Il testo deve contenere meno di 151 caratteri</li>';
                    }

                    if(strlen($erroriNews)==0){
                        $db->inserisciNews($titolo, $data, $testo, $username);
                        $titolo = 'Inserire un titolo';
                        $data = 'AAAA-MM-GG';
                        $testo = 'Inserire testo';
                    }else{
                        $erroriNews = '<ul class="errore">'.$erroriNews.'</ul>';
                    }


                }

                $queryResult = $db->getNews();

                $paginaHTML = file_get_contents('home_admin.html');

                $notizie = '';

                while ($row = mysqli_fetch_assoc($queryResult)) {
                    $notizie .= "<dt>" . $row['data'] . " - " . $row['titolo'] . "</dt>
                                        <dd>" . $row['descrizione'] . "</dd>
                                        <dd><input type=\"button\" name=\"elimina\" value=\"Elimina\"/></dd>
                                    ";
                }
                $paginaHTML = str_replace('<messaggio />', $erroriNews, $paginaHTML);
                echo str_replace('<notizie />', $notizie, $paginaHTML);


            } else {
                header('location: errore403.html');
            }
        }
        header('location: errore500.html');
    } else {
        header('location: errore404.html');
    }

?>