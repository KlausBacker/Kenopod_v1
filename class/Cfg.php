<?php

Cfg::init();

class Cfg {

	private static $initDone = false;
	public static $user = null;
	public static $plante = null;

//UPLOAD
	const TAB_EXT = [];
	const TAB_MIME = ['image/jpeg'];
//IMAGE
	const IMG_V_LARGEUR = 300;
	const IMG_V_HAUTEUR = 300;
	const IMG_P_LARGEUR = 450;
	const IMG_P_HAUTEUR = 450;
// SESSION
	const SESSION_TIMEOUT = 600; //10 minutes

	private function __construct() {
		// classe 100% static
	}

	public static function init() {
		if (self::$initDone)
			return false;
		spl_autoload_register(function ($classe) {
			@include "class/{$classe}.php";
		});
		// auto chargement des classes contenus dans le framework
		spl_autoload_register(function ($classe) {
			@include "framework/{$classe}.php";
		});
		Connexion::setDSN('nicolas3131_kenopod', '161830', 'liquidambar', 'mysql-nicolas3131.alwaysdata.net');
		// SESSION
		session_set_save_handler(new Session(self::SESSION_TIMEOUT));
		session_start();
		self::$user = User::getUserSession();
		return self::$initDone = true;
	}

}
