<?php

class Session implements SessionHandlerInterface, Databasable {

    public $sid;  // PHPSESSID
    public $data;  //données
    public $date_maj;   // date update
    private $timeout;  //Timeout (secondes), aucun si null

    public function __construct($timeout = null) {
        $this->timeout = $timeout;
    }

    public function charger() {
        $cnx = Connexion::getInstance();
        $req = "SELECT * FROM session WHERE sid={$cnx->esc($this->sid)}" . ($this->timeout ?
                " AND TIMESTAMPDIFF(SECOND,date_maj,NOW())<{$this->timeout}" : '');
        return $cnx->xeq($req)->ins($this);
    }

    public function sauver() {// le sid est systematiquement connu
        $cnx = Connexion::getInstance();
        $req = "INSERT INTO session VALUES ({$cnx->esc($this->sid)},{$cnx->esc($this->data)},DEFAULT) ON DUPLICATE KEY UPDATE data = {$cnx->esc($this->data)},date_maj=DEFAULT";
        $cnx->xeq($req);
        return $this;
    }

    public function supprimer() {// le sid est systematiquement connu
        $cnx = Connexion::getInstance();
        $req = "DELETE FROM session WHERE sid={$cnx->esc($this->sid)}";
        return (bool) $cnx->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {

        return []; //cest cadeau , function inutile dans Session
    }

    public function close() { // ferme un fichier
        return true;
    }

    public function destroy($session_id) { //detruit une session
        $this->sid = $session_id;
        return $this->supprimer();
    }

    public function gc($maxlifetime) {//nettoie les vieilles sessions de la BDD (aussi appellé garbage collector)
        if (!$this->timeout)
            return true;
        $req = "DELETE FROM session WHERE TIMESTAMPDIFF(SECOND,date_maj,NOW())>{$this->timeout}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public function open($save_path, $name) {//ouvre un fichier
        return true;
    }

    public function read($session_id) {//lit les donnees dune session
        $this->sid = $session_id;
        return $this->charger() ? $this->data : '';
    }

    public function write($session_id, $session_data) {//ecrit les donnees dune session
        $this->sid = $session_id;
        $this->data = $session_data;
        $this->sauver();
        return true;
    }

}
