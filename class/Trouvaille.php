<?php

require_once 'framework/Connexion.php';

class trouvaille implements Databasable {

	public $id_trouvaille;
	public $id_user;
	public $id_plante;
	public $date_trouvaille;
	public $lat;
	public $lng;
	public $commentaire;

	public function __construct($id_trouvaille = null, $id_user = null, $id_plante = null, $date_trouvaille = null, $lat = null, $lng = null, $commentaire = null) {
		//definition du constructeur de la trouvaille, chaque parametre à une valeur par défaut afin quon puisse créer un new Produit sans lui passer de parametre
		$this->id_trouvaille = $id_trouvaille; // definition de id_trouvaille
		$this->id_user = $id_user;
		$this->id_plante = $id_plante; // definition de id_plante
		$this->date_trouvaille = $date_trouvaille;
		$this->lat = $lat;
		$this->lng = $lng;
		$this->commentaire = $commentaire;
	}

	public function getPlante() {// permet de récuperer la plante d'une trouvaille avec id_plante
		return (new Plante($this->id_plante))->charger();
		// requete SELECT permettant de recupérer la plante d'un trouvaille grace à son id_plante
		// execution de la requete avec la methode query de PDO car cest une requete SELECT
		//  setFetchMode en FETCH_CLASS afin de remplir une instance de la classe Plante en chargeant les propriété plus tard car
		// on utilise FETCH_PROPS_LATE
		// retourne un jeu d'enregistrement à la fois , et retourne false si y'en a pas
	}

	public function charger() {
		if (!$this->id_trouvaille)
			return false;
		$req = "SELECT * FROM trouvaille WHERE id_trouvaille={$this->id_trouvaille}";
		return Connexion::getInstance()->xeq($req)->ins($this);
	}

	public function sauver() {
		$cnx = Connexion::getInstance();
		if ($this->id_trouvaille) {
			//si la trouvaille a un id_trouvaille alors on fait un UPDATE du trouvaille en BDD
			$req = "UPDATE  trouvaille SET id_user={$this->id_user},id_plante={$this->id_plante},date_trouvaille={$cnx->esc($this->date_trouvaille)},lat={$cnx->esc($this->lat)},lng={$cnx->esc($this->lng)},commentaire={$cnx->esc($this->commentaire)} WHERE id_trouvaille={$this->id_trouvaille}";
			$cnx->xeq($req);
		} else { // si le trouvaille n'a pas d id_trouvaille alors on fait un INSERT du nouveau trouvaille en BDD en passant tout les parametres
			$req = "INSERT INTO trouvaille VALUES (DEFAULT,{$this->id_user},{$this->id_plante},{$cnx->esc($this->date_trouvaille)},{$cnx->esc($this->lat)},{$cnx->esc($this->lng)},{$cnx->esc($this->commentaire)})";
			$this->id_trouvaille = $cnx->xeq($req)->pk();
		}
		return $this;
	}

	public function supprimer() {
		if (!$this->id_trouvaille)
			return false;
		$req = "DELETE FROM trouvaille WHERE id_trouvaille={$this->id_trouvaille}";
		return (bool) Connexion::getInstance()->xeq($req)->nb();
	}

	public static function tous() { // pêrmet de recupérer tout les trouvailles de la BDD
		return Trouvaille::tab(1, 'date_trouvaille');
	}

	public static function tab($where = 1, $orderBy = 1, $limit = null) {
		$req = "SELECT * FROM trouvaille WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
		return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
	}

}
