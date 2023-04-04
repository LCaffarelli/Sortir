function remplissageDiv(jsonObj) {
    let article = document.querySelector('article');
    article.innerHTML = '';
    let ville = document.createElement('h4');
    let rue = document.createElement('p');
    let codePostal = document.createElement('p');
    let latitude = document.createElement('p');
    let longitude = document.createElement('p');

    ville.textContent = jsonObj['ville'];
    rue.textContent = jsonObj['Rue'];
    codePostal.textContent =jsonObj['code'];
    latitude.textContent = 'Latitude : ' + jsonObj['latitude'];
    longitude.textContent = 'Longitude : ' + jsonObj['longitude'];

    const adresse = document.createTextNode("Adresse : ");
    const textNode = document.createTextNode(rue.innerText + ", " + codePostal.innerText + " " + ville.innerText+"  ("+latitude.innerHTML + " " + longitude.innerText+" )");


    article.appendChild(adresse);
    article.appendChild(textNode);

    // article.appendChild(ville);
    // article.appendChild(rue);
    // article.appendChild(codePostal);
    // article.appendChild(latitude);
    // article.appendChild(longitude);
}

const select = document.querySelector("#modif_lieu");
let id = `${select.value}`;
let requestURL = 'http://localhost/Sortir/public/lieu/' + id;
let request = new XMLHttpRequest();
request.open('GET', requestURL);
request.responseType = 'json';
request.send();
request.onload = function () {
    let ville = request.response;
    remplissageDiv(ville);
};
select.addEventListener('change', (event) => {
    let id = `${event.target.value}`;
    let requestURL = 'http://localhost/Sortir/public/lieu/' + id;
    let request = new XMLHttpRequest();
    request.open('GET', requestURL);
    request.responseType = 'json';
    request.send();
    request.onload = function () {
        let ville = request.response;
        remplissageDiv(ville);
    }
})
