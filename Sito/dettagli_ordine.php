<?php
	require_once "php/dbaccess.php";
	if( $_GET['id_ordine'] ) {
		$db = new DBAccess();
		
		if( $db->openDBConnection() ) {
			$username = 'user';	// da prendere a sessione
			$dettagliOrdine = $db->getDettagliOrdine($_GET['id_ordine'],$username);
			
			if( is_object( $dettagliOrdine ) ) {
				$content = '<h1>ID ordine: '.$dettagliOrdine->id_ordine.'</h1>
					<h2 class="data">Data ordine: '.$dettagliOrdine->data_ordine.'</h2>
					<h2 class="data">Data consegna: '.$dettagliOrdine->data_consegna.'</h2>

					<p>Destinazione:<br/>'.
					$dettagliOrdine->nome_cognome.'<br/>Via '.
					$dettagliOrdine->via.' '.$dettagliOrdine->numero_civico.' CAP '.$dettagliOrdine->CAP.'<br/>Tel: '.$dettagliOrdine->numero_telefonico.
					'<table class="defaultTable" summary="Lista prodotti ordinati">
						<thead>
							<tr>
								<th scope="col">Nome prodotto</th>
								<th scope="col">Categoria</th>
								<th scope="col">Porzioni</th>
							</tr>
						</thead>
						<tbody>';
				
				foreach( $dettagliOrdine->listaProdotti AS $row ) {
					$content .=	'<tr>
								<th scope="row" lang="ja">'.$row->nome.'</th>
								<td><a href="prodotti.html#'.$row->categoria.'" lang="ja">'.$row->categoria.'</a></td>
								<td>'.$row->numero_porzioni.'</td>
							</tr>';
				}
				
				$content .= '
						</tbody>
					</table>';
				
				$paginaHTML = file_get_contents('dettagli_ordine.html');
				
				echo str_replace('<infoOrdine/>',$content,$paginaHTML);
			} else {
				header('location: errore.html');
				/* errore
					l'id_ordine non esiste
					l'username non ha effettuato l'ordine con quel id_ordine
				*/
			}
		} else {
			// errore connesione DB
		}
	} else {
		header("location: errore.html");
	}
?>