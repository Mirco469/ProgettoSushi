# ProgettoSushi

**IMPORTANTE:** Ricordatevi quando scegliete un lavoro da fare di mettermi tra gli "assignee" (incaricato) così non ci mettiamo in due a fare la stessa cosa

**IMPORTANTE2:** Ricordatevi anche che quando create un "feature branch" prima dovete posizionarvi in develop e poi creare un branch nuovo.

**Link utili:**

Validatore di W3C: https://validator.w3.org/

**Come gestire i vari branch:**

Puntavamo ad usare il seguente tipo di workflow
![Workflow](https://user-images.githubusercontent.com/56229661/66667012-50e26b00-ec52-11e9-8c43-69d8d352f4cd.PNG)

Ci sarà quindi un branch "master" in cui per fare il merge sarà necesarria l'approvazione di tutti.
Ci sarà un branch "develop" dove basterà l'approvazione di una persona. Dal branch develop si farà il clone e si creeranno i branch 
"feature" ovvero la nuova funzionalità che si vuole sviluppare (ad esempio lo sviluppo della pagina home). Ogni funzionalità una volta 
completata dovrà fare il merge con il branch "develop". Quando il branch "develop" è in una fase abbastanza completa per le
conoscenze che abbiamo in quel momento allora faremo il merge con il branch "master".
