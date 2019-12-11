document.addEventListener('DOMContentLoaded', (event) => {
    //console.log('DOM completamente caricato e analizzato');
  
  var slideIndex = 1;
  showSlides(slideIndex);

  // funzione per salvare la larghezza dello schermo
  var deviceWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
  
  if( deviceWidth <= 900 ) {
    window.addEventListener("click",function(e) {
      toggleMenu();
    });

    document.getElementById("menu").getElementsByTagName("ul")[0].addEventListener("click",function(e) {
      e.stopPropagation();  // fa in modo che venga eseguito solo questo ascoltatore
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

  var slideIndex = 1;

  // Next/previous controls
  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  // Thumbnail image controls
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    var n = 1;
    if (n > slides.length) {slideIndex = 1} 
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].classList.add("nascondi");
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].classList.remove("nascondi"); 
    dots[slideIndex-1].className += " active";
  }

