<?php
    require_once("php/dbaccess.php");
    $db = new DBAccess();
    session_start();

    if($db->openDBConnection()){
        $paginaHTML = file_get_contents('html/home_utente.html');
        $menu = getMenu();

        $queryNews = $db->getNewsUtente();

        $notizie = '';
        $maxNews = 2;
        $index = 0;

        while ($row = mysqli_fetch_assoc($queryNews)) {
            $index  = $index+1;

            $notizie .= "<dt>" . $row['data'] . " - " . $row['titolo'] . "</dt>
                                        <dd>" . $row['descrizione'] . "</dd>";

            if($index == $maxNews) 
			{
				break;
			}
        }


        $paginaHTML = str_replace('<menu />', $menu, $paginaHTML);
        echo str_replace('<notizie />',$notizie, $paginaHTML);


    }else {
        header('location: errore500.html');
    }


?>