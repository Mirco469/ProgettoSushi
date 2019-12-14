
var slideIndex = 0;

document.addEventListener('DOMContentLoaded', (event) => {
    //console.log('DOM completamente caricato e analizzato');
  
  showSlides(slideIndex);

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

  // Next/previous controls
  function plusSlides(selected) {
    showSlides(slideIndex += selected);
  }

  // Thumbnail image controls
  function currentSlide(selected) {
    showSlides(slideIndex = selected);
  }

  function showSlides(selected) {
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
	
    if (selected >= slides.length) {slideIndex = 0} 
    if (selected < 0) {slideIndex = slides.length - 1}
    
	// display: none a tutte le immagini dello slideshow e toglie la classe active da tutti i punti sotto le slideshow
	var i = 0;
	for (i = 0; i < slides.length; i++) {
        slides[i].classList.add("nascondi");
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].classList.remove("active");
    }
	
	// si toglie display: none dall'immagine da mostrare corrente e si setta il punto attivo
    slides[slideIndex].classList.remove("nascondi");
    dots[slideIndex].classList.add("active");
  }


