<?php

session_start();
//-------------------
use \App\Autoloader;

// Appel des modèles
use \App\Model\Article;
use \App\Model\User;
use \App\Model\Categorie;

// Appel de la base de donnes
use \App\Repository\Database\Database;
use \App\Repository\DbRequest;

// Appel des contrôleurs 
use \App\Controller\ArticleController;


// AUTOLOADER PERSO
require 'App/Autoloader.php';
Autoloader::register();
$instanceDb = Database::getInstance();
$dbRequest = new DbRequest($instanceDb);

// VENDOR AUTOLOADER
require 'vendor/autoload.php';

//TWIG TEMPLATE
$loader = new Twig_Loader_Filesystem('Views');
$twig = new Twig_Environment($loader, array(
    //'cache' => '/path/to/compilation_cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());



//ROUTING
if (isset($_GET['page'])) {
	$page = htmlentities($_GET['page']);
}else{
	$page = 'home';
}

if ($page == 'home') {
	$articleController = new ArticleController($dbRequest);
	$listArticles = $articleController->indexAction();
//var_dump($listArticles);
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array('listArticles' => $listArticles));
}else{
	$template = $twig->load('error/error404.html.twig');
	echo $template->render();
}
