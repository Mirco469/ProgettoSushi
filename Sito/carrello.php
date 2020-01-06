<?php
	require_once "php/dbaccess.php";
		session_start();
		$_SESSION['username'] = 'user';
		$_SESSION['carrello'] = array(
			0 => array(
				'nome' => 'maguro',
				'categoria' => 'uramaki',
				'quantita' => 2,
				'prezzo' => 4
			)
		);
	
	if( session_status() != PHP_SESSION_NONE && $_SESSION['username'] != null ) {
		$db = new DBAccess();
		
		$carrello = $_SESSION['carrello'];
		if( $_SESSION['carrello'] != null ) {
			
			$totale = 0;
			$content = '<dl class="defaultLista">';
			
			foreach( $carrello AS $row ) {
				$content .= '<dt>'.$row['nome'].' - <a href="prodotti.html#'.$row['categoria'].'">'.$row['categoria'].'</a> <input class="rimuovi" type="button" name="rimuovi" value="Rimuovi" /></dt>
				<dd>
					<input type="button" name="minus" value="-" /><!-- togli_prodotto -->
					<input type="text" name="quantita" value="'.$row['quantita'].'" /><!-- numero prodotto -->
					<input type="button" name="plus" value="+" /><!-- aggiungi_prodotto -->
					<span>Prezzo: '.number_format($row['prezzo']*$row['quantita'], 2, ',', '.').'</span>
				</dd>';
				
				$totale += $row['prezzo']*$row['quantita'];
			}
			
			$content .= '</dl>
			<p class="totaleText">Totale: <span>'.number_format($totale, 2, ',', '.').'€</span></p>
			<a id="paga" href="pagamento.html">Vai a pagamento</a>';
		
		} else {
			$content = '<p>Il carrello attualmente è vuoto. Se vuoi ordinare qualcosa recati alla pagina <a href="takeaway.php" lang="en">Take Away</a></p>';
		}
		$paginaHTML = file_get_contents('html/carrello.html');
		
		$paginaHTML = str_replace('<menu />',getMenu(),$paginaHTML);
		$paginaHTML = str_replace('<carrello/>',$content,$paginaHTML);
		
		echo $paginaHTML;
	} else {
		header("location: errore403.html");
	}
?>