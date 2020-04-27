function detailTrouvaille(id_trouvaille) {
    location = `detail.php?id_trouvaille=${id_trouvaille}`;
}
function ajouterTrouvaille(id_user) {

    location = `ajouter.php?id_user=${id_user}`;
}
function modifierTrouvaille(evt,id_trouvaille) {
    evt.stopPropagation();
    location = 'modifier.php?id_trouvaille='+id_trouvaille;
}
function supprimerTrouvaille(evt, id_trouvaille) {
    evt.stopPropagation();
    if (confirm("vraiment supprimer ? ")) {
        let url = 'supprimer.php?id_trouvaille='+id_trouvaille;
        fetch(url)
                .then(response => {
                    if (response.ok)
                       location.reload();
        })
 .catch(error => console.error(error));
    }
}
function supprimerImage(evt,id_trouvaille) {
    evt.stopPropagation();
    if (confirm("vraiment supprimer ? ")) {
        let url = 'supprimerImage.php?id_trouvaille='+id_trouvaille;
        fetch(url)
                .then(response => {
                    if (response.ok)
                       location.reload();
        })
 .catch(error => console.error(error));
    }

}


