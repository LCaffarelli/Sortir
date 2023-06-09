function remplissageDiv(jsonObj) {
    let article = document.querySelector('article');
    article.innerHTML = '';
    let rue = document.createElement('div');
    let codePostal = document.createElement('div');
    let ville = document.createElement('div');
    let latitude = document.createElement('div');
    let longitude = document.createElement('div');

    ville.textContent = jsonObj['ville'];
    rue.textContent = jsonObj['Rue'];
    codePostal.textContent =jsonObj['code'];
    latitude.textContent = '( Latitude : ' + jsonObj['latitude'] +', Longitude : ' + jsonObj['longitude']+')' ;


    article.appendChild(rue);
    article.appendChild(codePostal);
    article.appendChild(ville);
    article.appendChild(latitude);

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
