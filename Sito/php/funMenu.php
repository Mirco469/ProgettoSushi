<?php


//Stampa il menu a seconda che l'utente sia autenticato o meno
//Va passato il contenuto della pagina come parametro
function printMenu($paginaHTML) {
$menu = '';
    if(isset($_SESSION['username'])) {

        $menu = '<li class="impostazioni">
					<span id="dropbtn">Area Riservata</span>
					<ul id="dropdown_content">
						<li><a href="carrello.html" tabindex="8">Carrello</a></li>
						<li><a href="storico_ordini.html" tabindex="9">Storico ordini</a></li>
						<li><a href="gestione_profilo_utente.html" tabindex="10">Gestione profilo</a></li>
						<li><a lang="en" href="#" tabindex="11">Logout</a></li>
					</ul>
				</li>';
        echo str_replace('<menu />', $menu, $paginaHTML);
    }else {
        $menu = '<li class="login"><a href="login.html" tabindex="7"><span lang="en">Login</span>/Registrazione</a></li>';
       echo str_replace('<menu />', $menu, $paginaHTML);
    }
}



?>