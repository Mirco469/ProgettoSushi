document.addEventListener('DOMContentLoaded', (event) => {
    //console.log('DOM completamente caricato e analizzato');
  


  // funzione per salvare la larghezza dello schermo
  var deviceWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
  
  if( deviceWidth <= 900 ) {
    window.addEventListener("click",function(e) {
		if( open ) {
			toggleMenu();
		}
    });

    document.getElementById("menu").getElementsByTagName("ul")[0].addEventListener("click",function(e) {
		// fa in modo che venga eseguito solo questo ascoltatore
		e.stopPropagation();  
		toggleMenu();
    });
  }

});

var open = false;
// funzione per aprire e chiudere il menu
function toggleMenu() {

  if(open) {
    document.getElementById("menu").classList.remove("open");
    document.getElementsByTagName("body")[0].classList.remove("noScroll");
    open = false;
    //console.log("Menu aperto: "+open);
  } else {
    document.getElementById("menu").classList.add("open");
    document.getElementsByTagName("body")[0].classList.add("noScroll");
    open = true;
    //console.log("Menu aperto: "+open);
  }
}

// funzione per abilitare l'input di pagamento
function enableInput()
{
    document.getElementById("nome_cognome").disabled = false;
    document.getElementById("via").disabled = false;
    document.getElementById("civico").disabled = false;
    document.getElementById("cap").disabled = false;
    document.getElementById("tel").disabled = false;
}

// funzione per disabilitare l'input di pagamento
function disableInput()
{
    document.getElementById("nome_cognome").disabled = true;
    document.getElementById("via").disabled = true;
    document.getElementById("civico").disabled = true;
    document.getElementById("cap").disabled = true;
    document.getElementById("tel").disabled = true;
}



