<?php

class User implements Databasable {

	public $id_user;
	public $email;
	public $mdp;
	public $pseudo;

	public function __construct($id_user = null, $email = null, $mdp = null, $pseudo = null) {
		$this->id_user = $id_user;
		$this->email = $email;
		$this->mdp = $mdp;
		$this->pseudo = $pseudo;
	}

	public function charger() {
		$req = "SELECT * FROM user WHERE id_user={$this->id_user}";
		return Connexion::getInstance()->xeq($req)->ins($this);
	}

	public function sauver() {
		$cnx = Connexion::getInstance();

		$req = "INSERT INTO user VALUES (DEFAULT,{$cnx->esc($this->email)},{$cnx->esc($this->mdp)},{$cnx->esc($this->pseudo)})";
		$this->id_user = $cnx->xeq($req)->pk();
		return $this;
	}

	public function supprimer() {
		if (!$this->id_user)
			return false;
		$req = "DELETE FROM user WHERE id_user={$this->id_user}";
		return (bool) Connexion::getInstance()->xeq($req)->nb();
	}

	public static function tab($where = 1, $orderBy = 1, $limit = null) {
		$req = "SELECT * FROM user WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
		return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
	}

	public function touteTrouvaille() {
		return Trouvaille::tab("id_user={$this->id_user}", "id_trouvaille DESC");
	}

	public function login() {
		$_SESSION['id_user'] = null;
		if (!($this->email || $this->mdp))
			return false;
		$mdp = $this->mdp; // mot de passe en clair de lutilisateur
		$cnx = Connexion::getInstance();
		$req = "SELECT * FROM user WHERE email={$cnx->esc($this->email)}";
		if (!$cnx->xeq($req)->ins($this)) // permet de charger le user de la bdd dans le $this
			return false;
		if (!password_verify($mdp, $this->mdp))
			return false;
		$_SESSION['id_user'] = $this->id_user;
		return true;
	}

	public static function getUserSession() {
		if (empty($_SESSION['id_user'])) // empty retourne true si et seulement si cest definis et que ca nest pas vide
			return null;
		$user = new User($_SESSION['id_user']);
		return $user->charger() ? $user : null;
	}

}
