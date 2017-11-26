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
use \App\Controller\UserController;


// AUTOLOADER PERSO
require 'App/Autoloader.php';
Autoloader::register();
$instanceDb = Database::getInstance();
$dbRequest = new DbRequest($instanceDb);
$articleController = new ArticleController($dbRequest);
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
if (isset($_GET['action'])) {
	$action = htmlentities($_GET['action']);
}else{
	$action = null;
}

if ($page == 'home') {
	$articleController = new ArticleController($dbRequest);
	$listArticles = $articleController->indexAction();
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array('listArticles' => $listArticles));
}
elseif ($page == 'login') {

	if (empty($_SESSION['user']) & empty($_POST['userConnexion'])) {
		$template = $twig->load('core/login.html.twig');
		echo $template->render();

	}
	elseif (empty($_SESSION['user']) & !empty($_POST['userConnexion'])) {

		var_dump($_POST);

		// A traiter par la suite
		$_SESSION['user']['id'] = 1;
		$_SESSION['user']['sNom'] = 'marguet';
		$_SESSION['user']['sPrenom'] = 'alex';
		$_SESSION['user']['bActif'] = 1;

		// Vérification des données post vers le controleur User
		// Création de la session User
		$flashMessage = 'Vous êtes bien connecté.';


		$articleController = new ArticleController($dbRequest);
		$listArticles = $articleController->indexAction();
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => 'success'
		));
	}elseif (!empty($_SESSION['user'])) {
		header('Location: home');
	}

}
elseif ($page == 'admin') {

		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array('foo'=>'ok'));


}
elseif ($page == 'article' & $action == 'view' & $slug != null) {

	$articleController = new ArticleController($dbRequest);
	$article = $articleController->viewArticleAction($slug);

	$template = $twig->load('core/article.html.twig');
	echo $template->render(array('article' => $article));

}
elseif ($page == 'article' & $action == 'add') {

	if (!empty($_SESSION['user']) & empty($_POST['formArticle'])) {
		$articleController = new ArticleController($dbRequest);
		$listCategories = $articleController->findCategorieArticleAction();

		$template = $twig->load('core/add_article.html.twig');
		echo $template->render(array(
			'listCategories' => $listCategories
		));
	}
	elseif (!empty($_SESSION['user']) & !empty($_POST['formArticle'])) {
		
		//COMMIT ON EN EST A L'AJOUT D'ARTICLE --------------------------------------------<<<<<<<<<<<<<
		// supprimer les instenciation de "new ArticleController()" car il ets copié en haut de page

		//var_dump($_POST);

		$newArticle = $articleController->addArticleAction($_POST,$_SESSION);

	}
	else{
		$flashMessage = 'Vous devez être connecté pour ajouter un article.';
		$articleController = new ArticleController($dbRequest);
		$listArticles = $articleController->indexAction();
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => 'danger'
		));
	}


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