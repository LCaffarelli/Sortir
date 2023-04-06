
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
    let adresse = document.createElement('h4')
    let rue = document.createElement('p');
    let codePostal = document.createElement('p');
    let latitude = document.createElement('p');

    adresse.textContent = 'Adresse : '
    rue.textContent = jsonObj['Rue'];
    codePostal.textContent =  jsonObj['code'] + ' ' + jsonObj['ville'];
    latitude.textContent = '( Latitude : ' + jsonObj['latitude'] +', Longitude : ' + jsonObj['longitude']+' )' ;

    article.appendChild(adresse);
    article.appendChild(rue);
    article.appendChild(codePostal);
    article.appendChild(latitude);
}

