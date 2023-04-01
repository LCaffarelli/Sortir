function annuler() {
    let ok = confirm("Etes-vous sûr ?");
    if (ok) {
        alert("Votre sortie a été annulée");

        return true;
    } else {
        return false;
    }
}

