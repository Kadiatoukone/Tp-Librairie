let apiKey = "AIzaSyAemSLgwK0v1oThvjgTNTl5746uB4L1fnI";

// je recup mes éléments HTML
let userInput = document.querySelector(".search");
let searchBtn = document.querySelector(".searchBtn");
let resultsZone = document.querySelector(".results");
let favorites = document.querySelector(".favBtn");

// J'écoute le bouton de recherche
searchBtn.addEventListener("click", () => {
     // On recup ce que le user tape dans l'input
    let userSearch = userInput.value;
    // On vide la zone de résultats
    resultsZone.innerHTML = "";

    fetch(`https://www.googleapis.com/books/v1/volumes?q=${userSearch}&key=${apiKey}`)
        .then(res => res.json())
        .then(data => {
            // Ici on va recup les infos des livres et les afficher adequatement
            if (data.items && data.items.length > 0) {
                displayBooks(data.items);
            } else {
                resultsZone.textContent = "Aucun résultat trouvé.";
            }
        })
        .catch(err => {
            console.log(err);
        });

});


favorites.addEventListener('click', () => {
    // Je vais vider ma zone de resultats 
    resultsZone.innerHTML = "";
    //Je recup mon tableau de favoris
    let favs = JSON.parse(localStorage.getItem("favs"));
    // Si le tableau n'est pas vide j'affiche les favoris ...
    if (favs.length > 0) {
        displayBooks(favs);
        // ... sinon un message qui dit qu'il n'y en a pas pour le moment
    } else {
        resultsZone.textContent = "Vous n'avez pour le moment pas de favoris ...";
    }
});

// Fonction d'affichage des livres depuis un tableau 
function displayBooks(results) {
    let favs = JSON.parse(localStorage.getItem("favs")) || [];

    results.forEach(book => {
        console.log(book)
        let card = document.createElement("div");
        let image = document.createElement("img");
        let title = document.createElement("h2");
        let author = document.createElement("p");
        let date = document.createElement("p");
        let favBtn = document.createElement("button");

        title.textContent = book.volumeInfo.title;
        img.src = book.volumeInfo.imageLinks.thumbnail;
        author.textContent = book.volumeInfo.authors;
        date.textContent = book.volumeInfo.publishedDate;

        let isFav = favs.some(favBook => favBook.id === book.id);
        if (isFav) {
            favBtn.textContent = "Supprimer des favoris";
            favBtn.addEventListener("click", () => {
                deleteFromFav(book);
            });
        } else {
            favBtn.textContent = "Ajouter aux favoris";
            favBtn.addEventListener("click", () => {
                addToFav(book);
            });
        }

        card.append(img, title, author, date, favBtn);
        resultsZone.appendChild(card);
    });
}

function addToFav(book) {
     // On veut rajouter le livre au LS
    // Dans un premier temps on vient recup le tableau des favs, si il existe, depuis le LS

    // Ici favs est mon tableau de favoris recup depuis le LS 
    // Si il n'y en a aucun de trouvé dans le LS alors on initialise un tableau vide
    let favs = JSON.parse(localStorage.getItem("favs")) || [];

    // Dans mon objet de livre je rajoute la clé fav et lui donne la valeur true 
    // Adfin de signifier que mon livre est dans les favoris
    book.fav = true;

    // J'ajoute mon livre au LS 
    favs.push(book);

    // Je sauvegarde le nouveau tableau mis à jour en LS
    localStorage.setItem("favs", JSON.stringify(favs));
}

function deleteFromFav(book) {
    // On recup le tableau des favs
    let favs = JSON.parse(localStorage.getItem("favs")) || [];

    // On filtre le book du tableau via l'ID
    let newFavs = favs.filter(elem => elem.id !== book.id);
    // On réenregistre la nouvelle version du tableau en LS 
    localStorage.setItem("favs", JSON.stringify(newFavs));
    // Je modifie la clé fav afin d'afficher le bon bouton
    book.fav = false;

    // On vide la zone de résultats à nouveau 
    // Avanty d'afficher la liste des favoris mis à jour 
    resultsZone.innerHTML = "";

     // Si le tableau n'est pas vide j'affiche les favoris ...
    if (newFavs.length > 0) {
        displayBooks(newFavs);
    } else {
        resultsZone.textContent = "Vous n'avez pour le moment pas de favoris ...";
    }
}