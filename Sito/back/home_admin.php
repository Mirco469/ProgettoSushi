<?php
    require_once("../php/dbaccess.php");
    $db = new DBAccess();

    session_start();

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if($db->openDBConnection()){

            $titolo = 'Inserire un titolo';
            $data = 'AAAA-MM-GG';
            $testo = 'Inserire testo';
            $erroriNews = '';

            if (isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione'] == 'Admin') {



                if (isset($_POST['inserisci'])) {
                    $titolo = htmlentities(trim($_POST['titolo']));
                    $data = date("Y-m-d");
                    $testo =  trim($_POST['notizia']);

                    if(!checkAlfanumerico($titolo)){
                        $erroriNews .= '<li>Il titolo deve contenere almeno due caratteri e non caratteri speciali</li>';
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
                        $testo = 'Inserire testo';
                    }else{
                        $erroriNews = '<ul class="errore">'.$erroriNews.'</ul>';
                    }


                }

                $queryResult = $db->getNews();

                $paginaHTML = file_get_contents('html/home_admin.html');

                $notizie = '';

                while ($row = mysqli_fetch_assoc($queryResult)) {
                    $notizie .= "<dt>" . $row['data'] . " - " . $row['titolo'] . "</dt>
                                        <dd>" . $row['descrizione'] . "</dd>
                                        <dd><input type=\"button\" name=\"elimina\" value=\"Elimina\"/></dd>
                                    ";
                }
                $paginaHTML = str_replace('<messaggio />', $erroriNews, $paginaHTML);
                echo str_replace('<notizie />', $notizie, $paginaHTML);
                exit;

            } else {
                header('location: errore403.html');
            }
        }
        header('location: errore500.html');
    } else {
        header('location: errore404.html');
    }

?>