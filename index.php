<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
	header('Location:login.php');
	exit;
}
$tabTrouvaille = Cfg::$user->touteTrouvaille();
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
		<script src="js/editer.js" type="text/javascript"></script>
		<script src="js/index.js" type="text/javascript"></script>
	</head>
	<body>
		<header>
			<div class="titre">
				<h1>kenopod</h1>
			</div>
			<div class="menu">
				<img src="img/ico_add2.svg" title="Ajouter" class="ico" onclick="ajouterTrouvaille(<?= Cfg::$user->id_user ?>)"/>
				<img src="img/ico_exit.svg" title="Logout" class="ico"  onclick="location.href = 'logout.php'"/>
			</div>
		</header>
		<div id="container" class="contindex">
			<?php
			if (!$tabTrouvaille) {
				?>
				<p class="noTrouvaille">Ici vos futures trouvailles.</p>
			<?php } ?>
			<?php
			foreach ($tabTrouvaille as $trouvaille) {
				$idImgUser = file_exists("imgUser/prod_{$trouvaille->id_trouvaille}_v.jpg") ? $trouvaille->id_trouvaille : 0;
				?>

				<div class="blocTrouvaille">
					<img src="imgUser/prod_<?= $idImgUser ?>_v.jpg?alea=<?= rand() ?>" alt="Photo de la trouvaille"/>
					<div class="date"><?= $trouvaille->date_trouvaille ?></div>
					<div class="genre"><?= $trouvaille->getPlante()->genre ?><?= $trouvaille->getPlante()->espece ?></div>
					<div class="nom"><?= $trouvaille->getPlante()->nom ?></div>

					<?php if (Cfg::$user) { ?>
						<div class="icon">
							<img src="img/ico_detail.svg" title="Détails" class="ico detail" alt="detail trouvaille" onclick="detailTrouvaille(<?= $trouvaille->id_trouvaille ?>)"/>
							<img src="img/ico_modif.svg" title="Modifier" class="ico modif" alt="modifier une trouvaille" onclick="modifierTrouvaille(event,<?= $trouvaille->id_trouvaille ?>)"/>
							<img src="img/ico_del.svg" title="Supprimer" class="ico del" alt="supprimer une trouvaille" onclick="supprimerTrouvaille(event,<?= $trouvaille->id_trouvaille ?>)"/>
						</div>
					<?php } ?>
				</div>
				<?php
			}
			?>
		</div>

		<footer>
		</footer>
	</body>
</html>
