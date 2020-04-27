<?php

class I18n {

	private function __construct() {
// Classe 100% statique , obligatoire de le faire car sinon un constructeur par défaut sera utiliser en cas de newI18n()
	}

	public static function get($message) {
		$langues = filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE', FILTER_SANITIZE_STRING); //permet de recupérer le HTTP_ACCEPT_LANGUAGE et de le nettoyer
		$langue = $langues ? mb_substr($langues, 0, 2) : 'en'; // ternaire qui permet de récupérer la langue favorite et si y'en a pas on le mets en anglais
		if (isset(self::$msg[$langue][$message])) { // on utilise self pour appeller une variable statique privé de la classe
			return self::$msg[$langue][$message]; // on verifie si un message d'erreur est disponible dans la langue favorite si oui on le retourne
		}
		if (isset(self::$msg['en'][$message])) {// si message d'erreur non dispo dans langue favorite on verifie si un message est dispo en anglais
			return self::$msg['en'][$message]; // si il est dispo on le retourne en anlgais
		}
		return null; // si jamais aucun message n'est disponible
	}

	private static $msg = // crétation dun tableau en 2 dimensions pour stocker les messages d'erreurs possible dans chaque langue
					['fr' => [
					'FORM_ERR_NAME' => "Le nom est absent",
					'FORM_LABEL_PLANTE' => "Plante",
					'FORM_LABEL_NAME' => 'Nom',
					'FORM_LABEL_COMMENT' => 'Commentaire',
					'FORM_LABEL_CANCEL' => 'Annuler',
					'FORM_LABEL_SUBMIT' => 'Valider',
					'FORM_LABEL_DELETE' => 'supprimer',
					'UPLOAD_ERR_' . UPLOAD_ERR_INI_SIZE => 'Fichier trop lourd côté serveur',
					'UPLOAD_ERR_' . UPLOAD_ERR_FORM_SIZE => 'Fichier trop lourd côté client',
					'UPLOAD_ERR_' . UPLOAD_ERR_PARTIAL => 'fichier partiellement uploadé',
					'UPLOAD_ERR_' . UPLOAD_ERR_NO_FILE => 'aucun fichier uploadé',
					'UPLOAD_ERR_' . UPLOAD_ERR_NO_TMP_DIR => 'dossier temporaire absent',
					'UPLOAD_ERR_' . UPLOAD_ERR_CANT_WRITE => 'dossier temporaire en lecture seule',
					'UPLOAD_ERR_' . UPLOAD_ERR_EXTENSION => "une extension PHP a bloqué l'upload",
					'UPLOAD_ERR_EMPTY' => "Pas de fichier à uploader",
					'UPLOAD_ERR_EXTENSION' => "l'extension du fichier est invalide",
					'UPLOAD_ERR_MIME' => "Le type MIME du fichier est invalide",
					'UPLOAD_ERR_MOVE' => " sauvegarde  fichier impossible",
					'IMG_ERR_UNAVAILABLE' => "Image inutilisable",
					'IMG_ERR_TYPE' => "Type image invalide",
					'IMG_ERR_CANT_WRITE' => "Copie de l'image impossible",
					'IMG_ERR_OUT_OF_MEMORY' => 'Mémoire insuffisante',
					'DB_ERR_DSN_ALREADY_SET' => "Deja fait",
					'DB_ERR_CONNECTION_FAILED' => "Connexion impossible",
					'DB_ERR_DSN_UNDEFINED' => "DSN non défini",
					'DB_ERR_BAD_REQUEST' => "Requete incorrecte",
					'FORM_ERR_LOGIN' => "Connexion impossible",
					'FORM_ERR_LOG' => "log absent ou invalide",
					'FORM_ERR_MDP' => "Mot de passe absent ou invalide",
					'FORM_ERR_PSEUDO' => "PSEUDO absent ou invalide",
					'FORM_LABEL_MAIL' => "email",
					'FORM_LABEL_MDP' => "Mot de passe",
					'FORM_LABEL_CONNECT' => "Connexion"
			]
//anglais
			, 'en' => ['FORM_ERR_CATEGORY' => "Category empty or invalid",
					'FORM_ERR_NAME' => "Name empty or invalid",
					'FORM_ERR_PLANTE' => "plante",
					'FORM_LABEL_NAME' => 'Name',
					'FORM_LABEL_COMMENT' => 'comment',
					'FORM_LABEL_CANCEL' => 'Cancel',
					'FORM_LABEL_SUBMIT' => 'Submit',
					'UPLOAD_ERR_' . UPLOAD_ERR_INI_SIZE => 'File too big  server side',
					'UPLOAD_ERR_' . UPLOAD_ERR_FORM_SIZE => 'File too big client side',
					'UPLOAD_ERR_' . UPLOAD_ERR_PARTIAL => 'file partially upload',
					'UPLOAD_ERR_' . UPLOAD_ERR_NO_FILE => 'no file uploaded',
					'UPLOAD_ERR_' . UPLOAD_ERR_NO_TMP_DIR => 'temporary folder is missing',
					'UPLOAD_ERR_' . UPLOAD_ERR_CANT_WRITE => "can't write into temporary folder",
					'UPLOAD_ERR_' . UPLOAD_ERR_EXTENSION => "A PHP extension prevents the upload from being completed",
					'UPLOAD_ERR_EMPTY' => "empty file",
					'UPLOAD_ERR_EXTENSION' => "Wrong file extension",
					'UPLOAD_ERR_MIME' => "Wrong MIME type",
					'UPLOAD_ERR_MOVE' => "Can't save file",
					'IMG_ERR_UNAVAILABLE' => "Image unavailable",
					'IMG_ERR_TYPE' => "Wrong image type ",
					'IMG_ERR_CANT_WRITE' => "Can't write the image",
					'IMG_ERR_OUT_OF_MEMORY' => 'Out of memory',
					'DB_ERR_DSN_ALREADY_SET' => "Already set",
					'DB_ERR_CONNECTION_FAILED' => "Connection failed",
					'DB_ERR_DSN_UNDEFINED' => "DSN undefined",
					'DB_ERR_BAD_REQUEST' => "Bad request",
					'FORM_ERR_LOG' => "Login empty ",
					'FORM_ERR_MDP' => "Password empty ",
					'FORM_LABEL_LOGIN' => "Login",
					'FORM_LABEL_MDP' => "Password",
					'FORM_LABEL_CONNECT' => "Connection"],
	];

}
