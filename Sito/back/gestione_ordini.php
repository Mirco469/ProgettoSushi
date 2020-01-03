<?php
	require_once('../php/dbaccess.php');
	
	session_start();
	
	if( isset($_SESSION['autorizzazione']) && $_SESSION['autorizzazione'] == 'Admin' ) {
		$db = new DBAccess();
		$connected = $db->openDBConnection();
		if( $connected ) {
			$ordini = $db->getOrdini();
			
			if( count($ordini) > 0 ) {
				$listaOrdini = '<dl class="defaultLista">';
				
				foreach( $ordini AS $row ) {
					$listaOrdini .= '<dt class="idordine">ID ordine: '.$row->id_ordine.' - '.$row->username.'<a class="buttonSmall" href="dettagli_ordine.php?id_ordine='.$row->id_ordine.'">Dettagli Ordine</a></dt>
					<dd>
						<span>Data ordine: '.$row->data_ordine.'</span>
						<span>Data consegna: '.$row->data_consegna.'</span>
						<span>Totale: '.number_format($row->totale,2,',','.').'€</span>
					</dd>';
				}
				$listaOrdini .= '</dl>';
			} else {
				$listaOrdini = '<h2>La lista è vuota.</h2>';
			}
			
			$paginaHTML = file_get_contents('html/gestione_ordini.html');
			
			$paginaHTML = str_replace('<listaOrdini/>',$listaOrdini,$paginaHTML);
			
			echo $paginaHTML;
		} else {
			header("Location: errore500.php");
		}
	} else {
		header("Location: ../errore403.php");
	}
?>