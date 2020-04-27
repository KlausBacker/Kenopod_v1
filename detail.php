<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
	header('Location:login.php');
	exit;
}
$opt = ['options' => [
				'min_range' => 1]]; // création d'une option qui fixe le INT de id_trouvaille à une valeur minimale de 1, à utiliser dans filter_input
$id_trouvaille = filter_input(INPUT_GET, 'id_trouvaille', FILTER_VALIDATE_INT, $opt); // permet de securiser la recuperation de lid trouvaille  a la pace de isset $_GET
$trouvaille = new Trouvaille($id_trouvaille);
$lat = $trouvaille->lat;
$lng = $trouvaille->lng;
$trouvaille->charger();
if (!$trouvaille->charger()) { // verification que nous avons bien un $trouvaille
	header("Location:indispo.php"); // si on a de $trouvaille pas on redirige vers indispo.php
	exit; // pensez a mettre exit apres les header sinon faille de securité
}
$plante = $trouvaille->getPlante(); // appelle de la methode getPlante sur $trouvaille afin de recuperer sa plante
$idImg = file_exists("imgUser/prod_{$trouvaille->id_trouvaille}_p.jpg") ? $trouvaille->id_trouvaille : 0;
// verification de lexistence du fichier $idImg et si y'en a pas on charge un fichier par défaut
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Kenopod</title>
		<script src="js/main.js" type="text/javascript"></script>
		<link rel="apple-touch-icon" sizes="180x180" href="metaData/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="metaData/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="metaData/favicon-16x16.png">
		<link rel="manifest" href="metaData/site.webmanifest">
		<link rel="mask-icon" href="metaData/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#00aba9">
		<meta name="theme-color" content="#ffffff">
		<link href="https://fonts.googleapis.com/css?family=Acme|Dancing+Script|Merienda|Amatic+SC:400,700" rel="stylesheet">
		<link href="css/kenopod.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript">
			var marker;
			var map;
			function setMarker(pos) {
				map.setCenter(pos);
				marker.setPosition(pos);
			}
			function detailMap() {
				map = new google.maps.Map(document.getElementById('map'), {
					zoom: 15

				});
				var pos = new google.maps.LatLng(<?= $trouvaille->lat ?>, <?= $trouvaille->lng ?>);
				marker = new google.maps.Marker();
				marker.setMap(map);
				setMarker(pos);

				console.log(pos);
			}
		</script>

	</head>
	<body onload="detailMap()">

		<header>
			<div class="titre">
				<h1>kenopod</h1>
			</div>
			<div class="menu">
				<img src="img/ico_home.svg" title="Accueil" class="ico"  onclick="location.href = 'index.php'"/>
				<img src="img/ico_exit.svg" title="Logout" class="ico"  onclick="location.href = 'logout.php'"/> </div>
		</header>
		<div id="container" class="contdetail">
			<div id="detailTrouvaille">
				<img src="imgUser/prod_<?= $idImg ?>_p.jpg"/>
				<!-- création de l'image avec id dynamique avec $idImg -->
				<div>
					<!-- affichage de la date de la trouvaille  -->
					<div class="date"><label>Date :</label>
						<?= $trouvaille->date_trouvaille ?></div>
					<!-- affichage du genre de la plante  -->
					<div class="genre"><label>Genre :</label>
						<?= $plante->genre ?><?= $plante->espece ?></div>
					<!-- affichage du nom de la plante  -->
					<div class="nom"><label>Nom :</label>
						<?= $plante->nom ?></div>
					<!-- affichage du commentaire de la trouvaille  -->
					<div class="commentaire"><label>Mon commentaire :</label>
						<?= $trouvaille->commentaire ?></div>
					<!-- affichage du commentaire de la trouvaille  -->
				</div>
				<div id="map"></div>
				<div class="description"><label>Description : </label><br/>
					<?= $plante->description ?></div>
			</div>

		</div>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAc7LMC9Y6DoOWdL7VJEAKL26h6YDrOlc&callback=detailMap"
		type="text/javascript"></script>
	</body>
</html>
