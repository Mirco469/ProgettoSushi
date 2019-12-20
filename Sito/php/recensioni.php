<?php

require_once(dbaccess.php);
use DB\BDAccess;

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
	$titolo = trim($_POST['titolo_recensione']);
	$data = $_SESSION['???'];
	$username = $_SESSION['???'];
  $testo = trim($_POST['testo_recensione']);
	$testo = stripslashes($testo);
	$testo = htmlspecialchars($testo);

	$paginaHTML = file_get_content('recensioni.html');

	#controllo input;
	$strErr = "";

	if (!chechTitolo($titolo)) { #funzione da fare
		$strErr .= '<li>Il titolo deve contenere almeno due caratteri alfanumerici</li>';
	}
	if (!cecktextarea($testo)) { #funzione da fare
		$strErr .= '<li>La recensione deve contenere almeno 10 caratteri</li>';
	}

}

?>
