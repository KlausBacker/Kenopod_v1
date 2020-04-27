<?php

require_once 'framework/Connexion.php';

class Plante implements Databasable { //création d'une Classe Plante

	public $id_plante; // definition d'une id_plante
	public $genre;
	public $espece; // definition d'un genre
	public $nom;	// definition d'un nom

	public function __construct($id_plante = null, $genre = null, $espece = null, $nom = null) { //Permet de faire  new Plante sans passer de parametre car parametre par defaut = null
		$this->id_plante = $id_plante; // definition de $id_plante
		$this->genre = $genre;
		$this->espece = $espece;
		$this->nom = $nom;
	}

	public static function tous() { // création d'une methode static pour récupérer toutes les Plante de la BDD
		return Plante::tab(1, 'nom');
		//requete SELECT afin de recuperer tout les produits dispos en BDD par ordre alphabetique
		// execution de la requete avec la methode query de PDO car cest une requete SELECT
		//  setFetchMode en FETCH_CLASS afin de remplir une instance de la classe Plante en chargeant les propriété plus tard car
		// on utilise FETCH_PROPS_LATE
		return $cnx->xeq($req)->tab(__CLASS__); // retourne tout les enregistrement dispos sous forme d'un tableau contenu dans $jeu
	}

	public function charger() {
		if (!$this->id_plante)
			return false;
		$req = "SELECT * FROM plante WHERE id_plante={$this->id_plante}";
		return Connexion::getInstance()->xeq($req)->ins($this);
	}

	public function sauver() {
		$cnx = Connexion::getInstance();
		if ($this->id_plante) {
			//si le produit a un id_produit alors on fait un UPDATE du produit en BDD
			$req = "UPDATE  plante SET genre={$cnx->esc($this->genre)}, nom={$cnx->esc($this->nom)} WHERE id_plante={$this->id_plante}";
			$cnx->xeq($req);
		} else { // si le produit n'a pas d id_produit alors on fait un INSERT du nouveau produit en BDD en passant tout les parametres
			$req = "INSERT INTO plante VALUES (DEFAULT,{$cnx->esc($this->genre)},{$cnx->esc($this->nom)})";
			$this->id_plante = $cnx->xeq($req)->pk();
		}
		return $this;
	}

	public function supprimer() {
		if (!$this->id_plante)
			return false;
		$req = "DELETE FROM plante WHERE id_plante={$this->id_plante}";
		return (bool) Connexion::getInstance()->xeq($req)->nb();
	}

	public static function tab($where = 1, $orderBy = 1, $limit = null) {
		$req = "SELECT * FROM plante WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
		return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
	}

}
