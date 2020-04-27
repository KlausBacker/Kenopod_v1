<?php

require_once 'class/Cfg.php';
// permet de supprimer une trouvaille sur la page index.php grace au bouton supprimer sur chaque trouvaille
if (!Cfg::$user)
	exit;
$opt = ['options' => ['min_range' => 1]]; // permet de déterminer les int minimal de id_trouvaille , a mettre en dernier parametre de filter_input
$id_trouvaille = filter_input(INPUT_GET, 'id_trouvaille', FILTER_VALIDATE_INT, $opt);
//permet de recupérer un id_trouvaille valide en GET en le securisant grace à filter_input
(new Trouvaille($id_trouvaille))->supprimer(); //verification que l'on a bien un id_trouvaille pour la suppression du trouvaille de la BDD
@unlink("imgUser/prod_{$id_trouvaille}_v.jpg");
@unlink("imgUser/prod_{$id_trouvaille}_p.jpg");






