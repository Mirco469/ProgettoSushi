<?php


//Stampa il menu a seconda che l'utente sia autenticato o meno
//Va passato il contenuto della pagina come parametro
function printMenu($paginaHTML) {
    if($_SESSION['logged'==true]){
        $menu = '<div id="menu">
			<ul>
				<li lang="en"><a href="home_utente.html" tabindex="2">Home</a></li>
				<li lang="en"><a href="take_away.html" tabindex="3">Take Away</a></li>
				<li><a href="chi_siamo.html" tabindex="4">Chi siamo</a></li>
				<li><a href="recensioni.html" tabindex="5">Recensioni</a></li>
				<li><a href="prodotti.html" tabindex="6">Prodotti</a></li>
				<li><a href="contatti.html" tabindex="7">Contatti</a></li>
				<li class="impostazioni">
					<span id="dropbtn">Area Riservata</span>
					<ul id="dropdown_content">
						<li><a href="carrello.html" tabindex="8">Carrello</a></li>
						<li id="active">Storico ordini</li>
						<li><a href="gestione_profilo_utente.html" tabindex="9">Gestione profilo</a></li>
						<li><a lang="en" href="#" tabindex="10">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>';
        str_replace('<menu />', $menu, $paginaHTML);
    }else {
        $menu = '<div id="menu">
		<ul>
			<li lang="en"><a href="home_utente.html" tabindex="2">Home</a></li>
			<li lang="en"><a href="take_away.html" tabindex="3">Take Away</a></li>
			<li id="active">Chi siamo</li>
			<li><a href="recensioni.html" tabindex="4">Recensioni</a></li>
			<li><a href="prodotti.html" tabindex="5">Prodotti</a></li>
			<li><a href="contatti.html" tabindex="6">Contatti</a></li>
			<li class="login"><a href="login.html" tabindex="7"><span lang="en">Login</span>/Registrazione</a></li>
		</ul>
	</div>';
        str_replace('<menu />', $menu, $paginaHTML);
    }
}



?>