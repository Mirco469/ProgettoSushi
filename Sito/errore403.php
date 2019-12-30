<?php
	require_once("php/dbaccess.php");

    session_start();

    //Stampo il menu in base se è loggato o no
    $menu = getMenu();
    $paginaHTML = file_get_contents('html/errore403.html');
	
    $paginaHTML = str_replace('<menu />', $menu, $paginaHTML);
	
	if( !isset($_SESSION['username']) ) {
		$paginaHTML = str_replace('<messaggioLogin />', 'Utente non loggato.<br />', $paginaHTML);
	} else {
		$paginaHTML = str_replace('<messaggioLogin />', '', $paginaHTML);
	}
	
	echo $paginaHTML;

?>