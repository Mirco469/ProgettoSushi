var open = false;

document.addEventListener('DOMContentLoaded', (event) => {
    console.log('DOM completamente caricato e analizzato');
	
	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	
	if( width <= 900 ) {
		window.addEventListener("click",function(e1) {
			if(open) {
				document.getElementById("menu").classList.remove("open");
				open = false;
				console.log(open);
				document.getElementsByTagName("body")[0].style.overflow = "auto";
			}
		});

		document.getElementById("menu").getElementsByTagName("ul")[0].addEventListener("click",function(e2) {
			e2.stopPropagation();
			if(!open) {
				document.getElementById("menu").classList.add("open");
				open = true;
				console.log(open);
				document.getElementsByTagName("body")[0].style.overflow = "hidden";
			}
			console.log("qui");
		});
	}
});