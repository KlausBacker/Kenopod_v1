function annuler(id_trouvaille = 0) {
	document.querySelector('form').reset();
	document.querySelector('#vignette').style.backgroundImage = `url(imgUser/prod_${id_trouvaille}_v.jpg)`;
	document.querySelector('.erreur').innerHTML = "";
}

function afficherPhoto(files) {
	var vignette = document.querySelector('#vignette');
	vignette.style.backgroundImage = ''; // liquide le backgroun a tout les coups 
	if (!files || !files.length) 
		return;
		var file = files[0]; // recuperation de l'image dans le dossier files a l'indice 0
	if (!file.size)
		return alert("Erreur : empty file.");
	if (file.size > MAX_FILE_SIZE)
		return alert("Error : file too big.");
	var ext = file.name.split('.').pop();
	if (TAB_EXT.length && !TAB_EXT.includes(ext))
		return alert("Error : file extension not allowed.");
	if (TAB_MIME.length && !TAB_MIME.includes(file.type))
		return alert("Error : file MIME not allowed.");
	var reader = new FileReader();
	reader.onload = function () {
		vignette.style.backgroundImage = `url(${this.result})`;
	};
	reader.readAsDataURL(file);
}

function supprimerImage(event, id_trouvaille) {
	event.stopPropagation();
	if (confirm("vraiment supprimer ? ")) {
		let url = 'supprimerImage.php?id_trouvaille=' + id_trouvaille;
		fetch(url)
						.then(response => {
							if (response.ok)
								location.reload();
						})
						.catch(error => console.error(error));
	}
	}



