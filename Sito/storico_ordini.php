<?php
	require_once('php/dbaccess.php');
	$db = new DBAccess();
	$connected = $db->openDBConnection();
	
	if( $connected ) {
		$ordini = $db->getOrdini('utente');
		
		$listaOrdini = '<dl class="defaultLista">';
		
		foreach( $ordini AS $row ) {
			$listaOrdini .= '<dt class="idordine">ID ordine: '.$row->id_ordine.' <a class="buttonSmall" href="dettagli_ordine.php?id_ordine='.$row->id_ordine.'">Dettagli Ordine</a></dt>
			<dd>
				<span>Data ordine: '.$row->data_ordine.'</span>
				<span>Data consegna: '.$row->data_consegna.'</span>
				<span>Totale: '.number_format($row->totale,2,',','.').'€</span>
			</dd>';
		}
		$listaOrdini .= '</dl>';
		
		$paginaHTML = file_get_contents('storico_ordini.html');
		echo str_replace('<listaOrdini/>',$listaOrdini,$paginaHTML);
	}
?>