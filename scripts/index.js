let apiKey = "AIzaSyA9VCg5zq6kEWCdxDZl0L1J7ZAM4PqvFgQ"

// je recup mes éléments HTML
let userInput = document.querySelector(".search")
let searchBtn = document.querySelector(".searchBtn")
let resultsZone = document.querySelector(".results")
let favorites = document.querySelector(".favBtn")

// J'écoute le bouton de recherche
searchBtn.addEventListener("click", () => {
    // On recup ce que le user tape dans l'input
    let userSearch = userInput.value

    // On vide la zone de résultats
    resultsZone.innerHTML = ""

    fetch(`https://www.googleapis.com/books/v1/volumes?q=${userSearch}&langRestrict=fr&key=${apiKey}`)
    .then(res => res.json())
    .then(data => {
        console.log(data)
        // Ici on va recup les infos des livres et les afficher adequatement
        displayBooks(data.Search)

    })
    .catch(err => console.log(err)) 
})


favorites.addEventListener("click", () => {
    // Je vais vider ma zone de resultats 
    resultsZone.innerHTML = ""

    // Je recup mon tableau de favoris
    let favs = JSON.parse(localStorage.getItem("favs")) || []

    // Si le tableau n'est pas vide j'affiche les favoris ...
    if (favs.length > 0) {
        displayBooks(favs)
    // ... sinon un message qui dit qu'il n'y en a pas pour le moment
    } else {
        resultsZone.textContent = "Vous n'avez pour le moment pas de favoris ..."
    }
})


function displayBooks(results) {
    results.forEach(book => {
        let container = document.createElement("div")
        let image = document.createElement("img")
        let title = document.createElement("h2")
        let author = document.createElement("p")
        let date = document.createElement("p")
        let favBtn = document.createElement("button")

        image.src = book.Poster
        title.textContent = book.Title
        author.textContent = book.author
        date.textContent = book.year

        if (book.fav === true) {
            favBtn.textContent = "Supprimer des favoris"

            favBtn.addEventListener("click", () => {
                // Ajout des favs dans le LS 
                deleteFromFav(book)
            })
        } else {
            favBtn.textContent = "Ajouter aux favoris"

            favBtn.addEventListener("click", () => {
                // Ajout des favs dans le LS 
                addToFav(book)
            })
        }

        container.append(image, title, author, date, favBtn)
        resultsZone.appendChild(container)
    })
}

function addToFav(book) {
    // On veut rajouter le livre au LS
    // Dans un premier temps on vient recup le tableau des favs, si il existe, depuis le LS

    // Ici favs est mon tableau de favoris recup depuis le LS 
    // Si il n'y en a aucun de trouvé dans le LS alors on initialise un tableau vide
    let favs = JSON.parse(localStorage.getItem("favs")) || []

    // Dans mon objet de livre je rajoute la clé fav et lui donne la valeur true 
    // Adfin de signifier que mon livre est dans les favoris
    book.fav = true

    // J'ajoute mon livre au LS 
    favs.push(book)

    // Je sauvegarde le nouveau tableau mis à jour en LS
    localStorage.setItem("favs", JSON.stringify(favs))
}


function deleteFromFav(book) {
    // On recup le tableau des favs
    let favs = JSON.parse(localStorage.getItem("favs")) || []

    // On filtre le book du tableau via l'ID
    let newFavs = favs.filter(elem => elem.imdbID != book.imdbID)

    // On réenregistre la nouvelle version du tableau en LS 
    localStorage.setItem("favs", JSON.stringify(newFavs))

    // Je modifie la clé fav afin d'afficher le bon bouton
    book.fav = false

    // On vide la zone de résultats à nouveau 
    // Avanty d'afficher la liste des favoris mis à jour 
    resultsZone.innerHTML = ""

    // Si le tableau n'est pas vide j'affiche les favoris ...
    if (newFavs.length > 0) {
        displayBooks(newFavs)
    // ... sinon un message qui dit qu'il n'y en a pas pour le moment
    } else {
        resultsZone.textContent = "Vous n'avez pour le moment pas de favoris ..."
    }

}