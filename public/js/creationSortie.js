function remplissageDiv(jsonObj){
    let article = document.querySelector('article');
    article.innerHTML='';
    let ville = document.createElement('h4');
    let rue = document.createElement('p');
    let codePostal = document.createElement('p');
    let latitude = document.createElement('p');
    let longitude = document.createElement('p');

    ville.textContent = 'Ville : ' + jsonObj['ville'];
    rue.textContent = 'Rue : ' + jsonObj['Rue'];
    codePostal.textContent = 'Code postal : ' + jsonObj['code'];
    latitude.textContent = 'Latitude : ' + jsonObj['latitude'];
    longitude.textContent = 'Longitude : ' + jsonObj['longitude'];

    article.appendChild(ville);
    article.appendChild(rue);
    article.appendChild(codePostal);
    article.appendChild(latitude);
    article.appendChild(longitude);
}

