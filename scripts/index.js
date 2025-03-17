let Key = "AIzaSyCKIVI7kSiqYTrz9Bdo00SdW1mYeSevVR4";

// je recup mes éléments HTML
let userInput = document.querySelector(".search");
let searchBtn = document.querySelector(".searchBtn");
let resultsZone = document.querySelector(".results");
let favorites = document.querySelector(".favBtn");

//J'écoute le bouton de recherche
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



// favorites.addEventListener('click', () => {
//     // Je vais vider ma zone de resultats 
//     resultsZone.innerHTML = "";
//     //Je recup mon tableau de favoris
//     let favs = JSON.parse(localStorage.getItem("favs"));
//     // Si le tableau n'est pas vide j'affiche les favoris ...
//     if (favs.length > 0) {
//         displayBooks(favs);
//         // ... sinon un message qui dit qu'il n'y en a pas pour le moment
//     } else {
//         resultsZone.textContent = "Vous n'avez pour le moment pas de favoris ...";
//     }
// });

favorites.addEventListener('click', () => {
    // Je vais vider ma zone de resultats 
    resultsZone.innerHTML = "";
    fetch(`/api/getUserLibrary?userId=${currentUserId}`)
        .then(res => res.json())
        .then(data => {
            // Si le tableau n'est pas vide j'affiche les favoris
            if (data.length > 0) {
                displayBooks(data);
            } else {
                resultsZone.textContent = "Votre bibliothèque personnelle est vide.";
            }
        });
});

// Fonction d'affichage des livres depuis un tableau 
function displayBooks(results) {
    results.forEach(book => {
        console.log(book)
        let card = document.createElement("div");
        let image = document.createElement("img");
        let title = document.createElement("h2");
        let author = document.createElement("p");
        let date = document.createElement("p");
        let favBtn = document.createElement("button");

        title.textContent = book.volumeInfo.title;
        let imgSrc = book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : 'default-image-url.jpg';
        image.src = imgSrc;
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
    fetch('/api/addToLibrary', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ bookId: book.id, userId: currentUserId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Livre ajouté à votre bibliothèque');
        } else {
            alert('Erreur lors de l\'ajout du livre');
        }
    });
}

function deleteFromFav(book) {
    fetch('/api/removeFromLibrary', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ bookId: book.id, userId: currentUserId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Livre supprimé de votre bibliothèque');
        } else {
            alert('Erreur lors de la suppression du livre');
        }
    });
}