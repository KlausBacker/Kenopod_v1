<?php
require_once 'I18n.php';
class Upload {
    public $nomChamp;   // nom du champ input type=file qui prends la value de name=""
    public $tabExt=[];   // extensions autorisées
    public $tabMIME=[];     // types MIMES autorisées   ex['image/jpeg']
    public $nomClient;          // nom du fichier coté client 
    public $extension;         // extension du fichier sans le point 
    public $cheminServeur;             // chemin du fichier temporaire coté serveur 
    public $codeErreur;                   //    eventuel code d'erreur 
    public $octets;                           // nombres d'octets téléchargés 
    public $typeMIME;                     // type MIME du fichier 
    public $tabErreur=[];                  // complété si probleme 
    
    public function __construct($nomChamp,$tabExt=[],$tabMIME=[]) {        // création du cponstructeur de la classe Upload
        $this->nomChamp=$nomChamp;    // définition de $nomChamp
        $this->tabExt=$tabExt;          // définition de $tabExt
        $this->tabMIME=$tabMIME;        //définition de $tabMIME
        if(!isset($_FILES[$nomChamp]))  // vérification de l'existence de $nomChamp    
            return;         // si il existe pas on retourne rien
         $this->cheminServeur = $_FILES[$nomChamp]['tmp_name'];      //définition de $cheminServeur gràce au tableau $_FILES 
        $this->nomClient = $_FILES[$nomChamp]['name'];      //définition de $nomClient gràce au tableau $_FILES 
        $this->extension = (new SplFileInfo($this->nomClient))->getExtension() ;
        //définition de $extension à l'aide de la Classe SplFileInfo et sa méthode getExtension 
        $this->codeErreur = $_FILES[$nomChamp]['error'] ;   //définition de $codeErreur grace au tableau $_FILES
        $this->octets =  $_FILES[$nomChamp]['size'];        //définition de $octets grace au tableau $_FILES
        $this->typeMIME = $_FILES[$nomChamp]['type'];       //définition de $typeMIME grace au tableau $_FILES
        if($this->codeErreur) // elimine le code erreur 0 qui est vu comme faux 
            $this->tabErreur[]= I18n::get ('UPLOAD_ERR_'.$this->codeErreur);            // definition de $tabErreur en le remplissant avec $codeErreur qui est un int
        if(!$this->octets)   // verification de la taille en octets du fichier uploader 
            $this->tabErreur[]= I18n::get ('UPLOAD_ERR_EMPTY');
        if($tabExt && !in_array($this->extension, $tabExt))//Verification de lexistense de $tabExt && de la non présence de lextension dans le tabExt
            $this->tabErreur[]= I18n::get ('UPLOAD_ERR_EXTENSION');
        if($tabMIME && !in_array($this->typeMIME, $tabMIME))//Verification de lexistense de $tabMIME && de la non présence du typeMIME dans le tabExt
            $this->tabErreur[]= I18n::get ('UPLOAD_ERR_MIME');
        
    }
    public function sauver($chemin){ // deplace le fichier recu  et le sauve dans $chemin
        if(!move_uploaded_file($this->cheminServeur,$chemin)) // $chemin est la destination de move_uploaded_file
            $this->tabErreur[]= I18n::get ('UPLOAD_ERR_MOVE'); 
    }
    public static function maxFileSize($enOctets=true){
        $kmg = ini_get('upload_max_filesize'); // recuperation de la valeur max dupload dans le php.ini
        if(!$enOctets){   
            return $kmg;
        }
       $maxfile = str_ireplace('G','*1024*1024*1024+',str_ireplace('M','*1024*1024+',str_ireplace('K','*1024+',$kmg))).'0';   // str_ireplace retourne la chaine quil vient de remplacer
       eval("\$poids = ${maxfile};"); //evaluation de l'expression grace a eval() qui evalue des chaines de caracteres 
       return $poids;
    }
}
