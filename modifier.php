<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
	header('Location:login.php');
	exit;
}
$cnx = Connexion::getInstance();
$tabErreur = [];
$trouvaille = new Trouvaille();

$tabPlante = Plante::tous();
$opt = ['min_range' => 1];
$trouvaille->id_trouvaille = filter_input(INPUT_GET, 'id_trouvaille', FILTER_VALIDATE_INT, $opt);
$trouvaille->id_user = filter_input(INPUT_GET, 'id_user', FILTER_VALIDATE_INT, $opt);
$trouvaille->id_plante = filter_input(INPUT_GET, 'id_plante', FILTER_VALIDATE_INT, $opt);
$trouvaille->charger();

// Arrivée en POST après validation du formulaire.
if (filter_input(INPUT_POST, 'submit')) {

	$trouvaille->id_trouvaille = filter_input(INPUT_POST, 'id_trouvaille', FILTER_VALIDATE_INT, $opt);
	$trouvaille->id_user = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT, $opt);
	$trouvaille->id_plante = filter_input(INPUT_POST, 'id_plante', FILTER_VALIDATE_INT, $opt);
	$trouvaille->date_trouvaille = filter_input(INPUT_POST, 'date_trouvaille', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$trouvaille->lat = filter_input(INPUT_POST, 'lat', FILTER_VALIDATE_FLOAT);
	$trouvaille->lng = filter_input(INPUT_POST, 'lng', FILTER_VALIDATE_FLOAT);
	$trouvaille->commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


//if (!$trouvaille->id_plante || !$trouvaille->getPlante()) {
//		$tabErreur[] = I18n::get('FORM_LABEL_PLANTE');
//}


	if (!$tabErreur) {
		$cnx->start();
		$trouvaille->sauver();
		$idImgUpload = $trouvaille->id_trouvaille;

//traitement upload
		$upload = new Upload('photo', Cfg::TAB_EXT, Cfg::TAB_MIME);
// Upload facultatif
		if ($upload->codeErreur === 4) {

			$cnx->commit();
			header("Location:index.php");
			exit;
		}
// Un upload a bien eu lieu.
		$tabErreur = array_merge($tabErreur, $upload->tabErreur);
		if (!$upload->tabErreur) {
//Traitement de l'image
			$image = new Image($upload->cheminServeur);
			$tabErreur = array_merge($tabErreur, $image->tabErreur);
			if (!$image->tabErreur) {
				$image->copier(Cfg::IMG_P_LARGEUR, Cfg::IMG_P_HAUTEUR, "imgUser/prod_{$idImgUpload}_p.jpg");
				$image->copier(Cfg::IMG_V_LARGEUR, Cfg::IMG_V_HAUTEUR, "imgUser/prod_{$idImgUpload}_v.jpg");
				$tabErreur = array_merge($tabErreur, $image->tabErreur);
				if (!$image->tabErreur) {
					$cnx->commit();
					header("Location:index.php");
					exit;
				}
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" lang="fr">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>kenopod</title>
		<script src="js/main.js" type="text/javascript"></script>
		<link rel="apple-touch-icon" sizes="180x180" href="metaData/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="metaData/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="metaData/favicon-16x16.png">
		<link rel="manifest" href="metaData/site.webmanifest">
		<link rel="mask-icon" href="metaData/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#00aba9">
		<meta name="theme-color" content="#ffffff">
		<link href="https://fonts.googleapis.com/css?family=Acme|Dancing+Script|Merienda|Amatic+SC:400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/kenopod.css"/>
		<script src="js/editer.js" type="text/javascript"></script>
		<script>
			const TAB_EXT = JSON.parse(`<?= json_encode(Cfg:: TAB_EXT) ?>  `);
			const TAB_MIME = JSON.parse(`  <?= json_encode(Cfg::TAB_MIME) ?>`);
			const MAX_FILE_SIZE = <?= Upload::maxfilesize() ?>;
		</script>
	</head>
	<body>
		<header>
			<div class="titre">
				<h1>kenopod</h1>
			</div>
			<div class="menu">
				<img src="img/ico_home.svg" title="Accueil" class="ico"  onclick="location.href = 'index.php'"/>
				<img src="img/ico_exit.svg" title="Logout" class="ico"  onclick="location.href = 'logout.php'"/>
			</div>
		</header>
		<div id="container" class="contmodifier">
			<h2>modifier une trouvaille</h2>
			<div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
			<form name="form" action="modifier.php" enctype="multipart/form-data" method="post">
				<input type="hidden" name="id_trouvaille" value="<?= $trouvaille->id_trouvaille ?>"/>
				<input type="hidden" name="id_user" value="<?= $trouvaille->id_user ?>"/>
				<input id="lat" type="hidden" name="lat" value="<?= $trouvaille->lat ?>"/>
				<input id="lng" type="hidden" name="lng" value="<?= $trouvaille->lng ?>"/>
				<div class="item">
					<label>Date</label>
					<input  name="date_trouvaille" type="date" size="10" value="<?= $trouvaille->date_trouvaille ?: date('Y-m-d') ?>"/>

				</div>

				<div class="item">
					<label>Nom commun</label>
					<select name="id_plante">
						<?php
						foreach ($tabPlante as $plante) {
							$selected = $trouvaille->id_plante == $plante->id_plante ? 'selected' : '';
							?>
							<option value="<?= $plante->id_plante ?>"<?= $selected ?>>
								<?= $plante->nom ?>
							</option>
							<?php
						}
						?>
					</select>

				</div>

				<div class="item">
					<label>commentaire</label>
					<input type="text" name="commentaire" style="width:200px; height:50px;" maxlength="500" value="<?= $trouvaille->commentaire ?>" size="50"/>
				</div>
				<div class="item">
					<label>Photo(JPEG)</label>
				</div>
				<div class="bouton">
					<input class="parcourir" type="button" value="Choisir..." onclick="this.form.photo.click()"/>
				</div>
				<input type="file" id="photo" name="photo" onchange="afficherPhoto(this.files)" />
				<div class="item">
					<div>
						<div class="vignette">
							<?php
							$idImgUser = file_exists("imgUser/prod_{$trouvaille->id_trouvaille}_v.jpg") ? $trouvaille->id_trouvaille : 0;
							if ($idImgUser) {
								?>
								<img src='img/ico_supImg.svg' class="ico" alt="Supprimer image" onclick="supprimerImage(event,<?= $trouvaille->id_trouvaille ?>)" />
								<?php
							}
							?>
							<div id="vignette" style="background-image: url(imgUser/prod_<?= $idImgUser ?>_v.jpg?alea=<?= rand() ?>)">
							</div>
						</div>
						<div class="bouton">
							<input class="annuler" type="button" value="<?= I18n::get('FORM_LABEL_CANCEL') ?>" onclick="annuler(<?= $trouvaille->id_trouvaille ?>)"/>
							<input class="valider" type="submit" name="submit" value="<?= I18n::get('FORM_LABEL_SUBMIT') ?>"/>
						</div>
					</div>
				</div>

			</form>
		</div>
	</body>
</html>
