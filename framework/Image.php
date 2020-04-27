<?php

require_once 'I18n.php';

class Image {

    public $tabErreur = [];   //  (renseigné si erreur)
    private $chemin;   // (chemin du fichier)
    private $largeur;  //(largeur en px)
    private $hauteur;  //  (hauteur en px)
    private $type; // (type PHP, ex : IMG_JPG)

    public function __construct($chemin) {
        $this->chemin = $chemin;
        list($this->largeur, $this->hauteur, $this->type) = getimagesize($chemin);

        if ($this->largeur === null) {    // Pas besoin de tester $this->hauteur ou $this->type
            $this->tabErreur[] = I18n::get('IMG_ERR_UNAVAILABLE');
            return;
        }

        if ($this->type != IMAGETYPE_JPEG && $this->type != IMAGETYPE_PNG) {     //IMAGETYPE_JPEG est une constante de PHP, qui permet de comparer le type de l'image
            $this->tabErreur[] = I18n::get('IMG_ERR_TYPE');
            return;
        }
    }

    public function copier($largeurCible, $hauteurCible, $cheminCible, $qualite = 60) {
        $ratioSource = $this->largeur / $this->hauteur;
        $ratioCible = $largeurCible / $hauteurCible;
        if ($this->largeur < $largeurCible && $this->hauteur < $hauteurCible) {
            if (!copy($this->chemin, $cheminCible)) {              // la copie est faite de toute manière, puis php regarde ce qui est retourné, si false, on passe dans le if pour le message d'erreur
                $this->tabErreur[] = I18n::get("IMG_ERR_CAN'T_WRITE");
            }
            return;
        } if ($ratioSource > $ratioCible) {
            $largeurFinale = $largeurCible * $ratioSource;
            $hauteurFinale = $hauteurCible;
        } else {
            $largeurFinale = $largeurCible;
            $hauteurFinale = $hauteurCible / $ratioSource;
        }
        // mtn, largeur et hauteur finale sont calculées
        //  Nous pouvons faire le redimensionnement

        if (!$source = $this->type === IMAGETYPE_JPEG ? imagecreatefromjpeg($this->chemin) : imagecreatefrompng($this->chemin)) { //pointe sur la zone mémoire de l'img source
            $this->tabErreur[] = I18n::get('IMG_ERR_OUT_OF_MEMORY');
            return;
        }
        if (!$finale = imagecreatetruecolor($largeurFinale, $hauteurFinale)) { // crée une zone mémoire avec une largeur et une hauteur
            $this->tabErreur[] = I18n::get('IMG_ERR_OUT_OF_MEMORY');
            return;
        }

        if (!imagecopyresampled($finale, $source, 0, 0, 0, 0, $largeurFinale, $hauteurFinale, $this->largeur, $this->hauteur)) { //chgt de zone mémoire en copiant
            $this->tabErreur[] = I18n::get('IMG_ERR_OUT_OF_MEMORY');
            return;
        }

        imageDestroy($source); // désalloue la zone mémoire créée

        if (!($this->type === IMAGETYPE_JPEG ? imagejpeg($finale, $cheminCible, $qualite) : imagepng($finale, $cheminCible))) {
            $this->tabErreur[] = I18n::get("IMG_ERR_CAN'T_WRITE");
            return;
        }
        imageDestroy($finale);
    }

}
