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
if (isset($_GET['slug'])) {
	$slug = htmlentities($_GET['slug']);
}else{
	$slug = null;
}


if ($page == 'home') {

	$articleController = new ArticleController($dbRequest);
	$listArticles = $articleController->indexAction();
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array('listArticles' => $listArticles));
}
elseif ($page == 'login') {
	if (isset($_POST)) {
		$_SESSION['user']['bAdmin'] = 1;
	}
	$template = $twig->load('core/login.html.twig');
	echo $template->render(array('foo'=>'bar'));		
}
elseif ($page == 'admin') {

	if (isset($_SESSION) & $_SESSION['user']['bAdmin'] == 1) {
		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array('foo'=>'ok'));
	}else{
		$template = $twig->load('core/login.html.twig');
		echo $template->render(array('foo'=>'bar'));	
	}

}
elseif ($page == 'article' & $slug != null) {

	$articleController = new ArticleController($dbRequest);
	$article = $articleController->articleAction($slug);

	$template = $twig->load('core/article.html.twig');
	echo $template->render(array('article' => $article));

}
elseif ($page == 'logout') {
	setcookie(session_name(), 'vide', time() - 25*3600);
	session_destroy();
	$_SESSION = array();
	header('Location: home');
}
else{
	$template = $twig->load('error/error404.html.twig');
	echo $template->render();
}