<?php
require_once 'class/Cfg.php';
$user = new User();
$tabErreur = []; //definition d'un tabErreur vide

if (filter_input(INPUT_POST, 'submit')) {
	$user->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$user->mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	if (!$user->email) {
		// si le nom du produit est inexistant ou invalide sa remplis le $tabErreur
		$tabErreur[] = I18n::get('FORM_ERR_LOG');
	}
	if (!$user->mdp) {
		//si la ref est invalide ou si elle existe déjà ça remplis le $tabErreur
		$tabErreur[] = I18n::get('FORM_ERR_MDP');
	}

	if (!$tabErreur && $user->login()) {
		header("location:index.php");
		exit;
	}
	$tabErreur[] = I18n::get('FORM_ERR_LOGIN');
}
$user = null;
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
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

	</head>
	<body>
		<div class="titre">
			<p>Bienvenue sur</p>
			<h1>kenopod</h1>
			<p>Recensement de plantes sauvages utiles,</p><p> l'application du chasseur-cueilleur 2.0</p>
		</div>

		<div id="container" class="contlogin">
			<h2>Login</h2>
			<div class="erreur">
				<?= implode('<br/>', $tabErreur) ?> <!-- création dune div erreur et affichage de code erreur eventuel , la methode implode() permet de les afficher sous forme d'une String -->
			</div>
			<form name="form" method="post" action="login.php">
				<div class="item"><label><?= I18n::get('FORM_LABEL_MAIL') ?></label> <!--affichage du label de linput EMAIL en multi langue -->
					<input type="text" name="email" style="width:250px" maxlength="50" class="email" required="required"/>
				</div>
				<div class="item"><label><?= I18n::get('FORM_LABEL_MDP') ?></label><!--affichage du label de linput MDP en multi langue -->
					<input type="password" name="mdp" maxlength="10" size="10" class="ref" required="required"/>
				</div>
				<div class="item">
					<div>
						<div class="bouton">
							<input class="connexion" type="submit"  name="submit" value="<?= I18n::get('FORM_LABEL_CONNECT') ?>"/><!--affichage du bouton valider multi langue -->
							<input class="inscription" type="button" value="Inscription" onclick="location.href = 'inscription.php'"/>
						</div>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>
