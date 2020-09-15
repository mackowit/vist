const itemNames = document.querySelectorAll('.item h2');
const itemDescs = document.querySelectorAll('.desc');
const itemIngredients = document.querySelectorAll('.ingr');
const itemImages = document.querySelectorAll('.item_image img');
const counters = Array.from(document.querySelectorAll('.counter'));
const frontShifter = document.querySelector('.front_shifter');
const rearShifter = document.querySelector('.rear_shifter');
const frontArrow = document.querySelector('.front_arrow');
const rearArrow = document.querySelector('.rear_arrow');
const favoriteIcons = document.querySelectorAll('.item i');
const favoritesCounter = document.querySelector('.favorites_counter span');

let pageNmbr = 1;
let counterState = pageNmbr;
const pages = 16;
let favorites = new Array();

let response = renderPage(pageNmbr);
renderCounter(counterState);
favoritesCounter.textContent = favorites.length;

function renderCounter(counterState) {
    if (counterState >= 3 && counterState < (pages - 1)) {
        let counterShift = 1;
        counters.forEach(counter => {
            counter.textContent = counterState - counterShift;
            counterShift--;
        });
        frontShifter.textContent = "...";
        rearShifter.textContent = "...";
    } else if (counterState >= (pages - 1)) {
        let counterShift = 2;
        counters.forEach(counter => {
            counter.textContent = pages - counterShift;
            counterShift--;
            frontShifter.textContent = "...";
            rearShifter.textContent = "";
        })
    } else {
        counters.forEach((counter, index) => {
            counter.textContent = index + 1;
        });
        frontShifter.textContent = "";
        rearShifter.textContent = "...";
    }
    frontArrow.textContent = "<";
    rearArrow.textContent = ">";
    if (pageNmbr == 1) frontArrow.textContent = "";
    else if (pageNmbr == pages) rearArrow.textContent = "";

    counters.forEach(counter => {
        counter.style.fontWeight = 'normal';
        if (parseInt(counter.textContent) == pageNmbr) counter.style.fontWeight = 'bold';
    });
}

function renderPage(pageNmbr) {
    const url = `https://api.punkapi.com/v2/beers?page=${pageNmbr}&per_page=5`;
    const promise = fetch(url)
        .then((response) => {
            if (response.ok) {
                return response.json();
            }
            throw Error("Błąd połączenia")
        })
        .catch(err => console.warn(err));

    promise.then((json) => {
        json.forEach((item, index) => {
            //nazwa elementu
            itemNames[index].textContent = item.name;
            //opis elementu
            itemDescs[index].textContent = item.description;
            //skaldniki
            let ingredients = "Malt: ";
            item.ingredients.malt.forEach(item => ingredients += item.name + ", ");
            ingredients += "Hops: ";
            item.ingredients.hops.forEach(item => ingredients += item.name + ", ");
            ingredients += "Yeast: " + item.ingredients.yeast;
            itemIngredients[index].textContent = `Ingredients: ${ingredients}`;
            //obrazek
            itemImages[index].src = item.image_url;
            favoriteIcons[index].className = 'icon-star-empty';
            favorites.forEach(favorite => {
                if (item.id === favorite) {
                    console.log('jest przed ' + item.id, favorite, favoriteIcons[index]);
                    favoriteIcons[index].className = 'icon-star';
                    console.log('jest po' + item.id, favorite, favoriteIcons[index]);
                }
            });
        });
    });
    return promise;
}

frontArrow.addEventListener('click', () => {
    if (pageNmbr > 1) {
        pageNmbr--;
        counterState = pageNmbr;
    }
    response = renderPage(pageNmbr);
    renderCounter(counterState);
});

rearArrow.addEventListener('click', () => {
    pageNmbr++;
    counterState = pageNmbr;
    response = renderPage(pageNmbr);
    renderCounter(counterState);
});

counters.forEach(counter => {
    counter.addEventListener('click', () => {
        pageNmbr = parseInt(counter.textContent);
        counterState = pageNmbr;
        response = renderPage(pageNmbr);
        renderCounter(counterState);
    });
});

rearShifter.addEventListener('click', () => {
    counterState += 2;
    renderCounter(counterState);
});

frontShifter.addEventListener('click', () => {
    counterState -= 2;
    renderCounter(counterState);
});

favoriteIcons.forEach((icon, index) => {
    icon.addEventListener('click', () => {
        if (icon.className == 'icon-star-empty') {
            icon.className = 'icon-star';
            response.then(json => {
                favorites.push(json[index].id);
                favoritesCounter.textContent = favorites.length;
            });
            console.log(favorites);
        } else {
            icon.className = 'icon-star-empty';
            response.then(json => {
                favorites.filter((favorite, i) => {
                    if (favorite == json[index].id) {
                        favorites.splice(i, 1);
                        favoritesCounter.textContent = favorites.length;
                    }
                    console.log(favorites);
                });
            });
        }
    });
});

/*function sendFavorites() {
    const request = new XMLHttpRequest();
    request.open("POST", "shop.php", true);
    request.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            console.log('success');
        }
    };

    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send("favs=" + favorites);
}*/