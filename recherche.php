<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
	header('Location:login.php');
	exit;
}
$cnx = Connexion::getInstance();
$tabErreur = [];
$tabTrouvaille = Trouvaille::tous();
$plante = new Plante();
?>
<!DOCTYPE html>
<html>
	<head lang="fr">
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
		<div id="container" class="contrecherche">
			<h2>Recherche</h2>
			<div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>

			<div class="item">
				<label>Plante recherchée</label>
				<select>
					<?php
					foreach ($tabTrouvaille as $trouvaille) {
						/* 	if($trouvaille->id_plante && ...) {

						  } */
						?>

						<option value="<?= $trouvaille->id_plante ?>" >
							<?= $plante->nom ?>
						</option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="item">
				<label></label>
				<div>

				</div>
			</div>
		</div>
	</body>
</html>
