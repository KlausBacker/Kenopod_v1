<?php

require_once 'class/Cfg.php';
if (!Cfg::$user)
	exit;
$opt = ['options' => ['min_range' => 1]]; // permet de déterminer les int minimal de id_trouvaille , a mettre en dernier parametre de filter_input
$id_trouvaille = filter_input(INPUT_GET, 'id_trouvaille', FILTER_VALIDATE_INT, $opt);
//permet de recupérer un id_trouvaille valide en GET en le securisant grace à filter_input
if ($id_trouvaille) {//verification que l'on a bien un id_trouvaille pour la suppression du produit de la BDD
	@unlink("imgUser/prod_{$id_trouvaille}_v.jpg");
	@unlink("imgUser/prod_{$id_trouvaille}_p.jpg");
}






