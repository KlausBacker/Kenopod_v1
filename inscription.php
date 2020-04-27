<?php
require_once 'class/Cfg.php';

$cnx = Connexion::getInstance();
$tabErreur = [];
$user = new User();
$tabPlante = Plante::tous();
$opt = ['min_range' => 1];

if (filter_input(INPUT_POST, 'submit')) {
	$user->id_user = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT, $opt);
	$user->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$user->mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$user->pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if (!$user->email) {
		// si le nom du produit est inexistant ou invalide sa remplis le $tabErreur
		$tabErreur[] = I18n::get('FORM_ERR_LOG');
	}
	if (!$user->mdp) {
		//si la ref est invalide ou si elle existe déjà ça remplis le $tabErreur
		$tabErreur[] = I18n::get('FORM_ERR_MDP');
	}
	if (!$user->pseudo) {
		//si la ref est invalide ou si elle existe déjà ça remplis le $tabErreur
		$tabErreur[] = I18n::get('FORM_ERR_PSEUDO');
	}
	if (!$tabErreur) {
		$user->mdp = password_hash($user->mdp, PASSWORD_DEFAULT);
		$user->sauver();
		header("Location:index.php");
		exit;
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
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<link rel="apple-touch-icon" sizes="180x180" href="metaData/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="metaData/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="metaData/favicon-16x16.png">
		<link rel="manifest" href="metaData/site.webmanifest">
		<link rel="mask-icon" href="metaData/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#00aba9">
		<meta name="theme-color" content="#ffffff">
		<link href="https://fonts.googleapis.com/css?family=Acme|Dancing+Script|Merienda|Amatic+SC:400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/kenopod.css"/>

	</head>
	<body>
		<div id="container" class="continscription">
			<h2>Inscription</h2>
			<div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
			<form name="form" action="inscription.php" method="post">
				<input type="hidden" name="id_user" value="<?= $user->id_user ?>"/>

				<div class="item">
					<label>Adresse e-mail</label>
					<input type="email" name="email" style="width:250px" value="<?= $user->email ?>"/>
				</div>
				<div class="item">
					<label>Mot de passe </label>
					<input type="password" name="mdp"  value="<?= $user->mdp ?>"/>
				</div>
				<div class="item">
					<label>Pseudo </label>
					<input type="text" name="pseudo"  value="<?= $user->pseudo ?>"/>
				</div>
				<div class="g-recaptcha" data-sitekey="6LeD9XIUAAAAAMjh5Sxge39ETwhJ-f5RmQ-4dbYk"></div>

				<div class="item">
					<div>
						<div class="bouton">
							<input class="annuler" type="button" value="Annuler" onclick="location.href = 'login.php'"/>
							<input class="valider" type="submit" name="submit" value="<?= I18n::get('FORM_LABEL_SUBMIT') ?>"/>
						</div>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>
