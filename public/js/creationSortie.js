
const select = document.querySelector("#creation_sortie_lieu");
select.addEventListener('change', (event) => {
    let id = `${event.target.value}` ;
    let requestURL ='http://localhost/Sortir/public/lieu/'+id;
    let request = new XMLHttpRequest();
    request.open('GET', requestURL);
    request.responseType ='json';
    request.send();
    request.onload = function(){
        let ville = request.response;
        remplissageDiv(ville);
    }
})

function remplissageDiv(jsonObj){
    let article = document.querySelector('article');
    article.innerHTML='';
    let ville = document.createElement('p');
    let rue = document.createElement('p');
    let codePostal = document.createElement('p');
    let latitude = document.createElement('p');
    let longitude = document.createElement('p');

    ville.textContent ='Ville : ' + jsonObj['ville'];
    rue.textContent = 'Rue : ' + jsonObj['Rue'];
    codePostal.textContent = 'Code postal : ' + jsonObj['code'];
    latitude.textContent = 'Latitude : ' + jsonObj['latitude'];
    longitude.textContent = 'Longitude : ' + jsonObj['longitude'];


    article.appendChild(rue);
    article.appendChild(codePostal);
    article.appendChild(ville);
    article.appendChild(latitude);
    article.appendChild(longitude);
}

