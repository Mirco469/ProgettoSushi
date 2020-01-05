<?php
	require_once("php/dbaccess.php");

    session_start();

    //Stampo il menu in base se è loggato o no
    $menu = getMenu();
    $paginaHTML = file_get_contents('html/errore500.html');
    echo str_replace('<menu />', $menu, $paginaHTML);

?>