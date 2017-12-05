<?php
/*
use \Web\ControllerGeneral;

class Router
{

	private $page;
	private $action;
	private $data;
	private $post;
	private $app_session_user;
	private $manager;
	private $route;

	public function __construct($get = null, $post = null, $session = null)
	{
		$this->setController($get['page']);
		$this->setAction($get['action']);
		$this->setData($get['data']);
		$this->setPost($post);
		$this->setSession($session);
		$this->manager = new ControllerGeneral();
	}
	public static function setRoute()
	{

		switch ($this->page) {
			case 'home':
					return $this->manager->;
				break;
			
			default:
				# code...
				break;
		}
	}

	public function setPost($post)
	{
		if (!empty($post)) 
			$this->post = $post;
		else
			$this->post = null;
	}

	public function setController($get){
		if (isset($get['page'])) 
			$this->page = htmlentities($get['page']);
		else
			$this->page = 'home';

	}
	public function setAction($get){

		if (isset($get['action'])) 
			$this->action = htmlentities($get['action']);
		else
			$this->action = null;
	}
	public function setData($get){

		if (isset($get['data'])) 
			$this->data = htmlentities($get['data']);
		else
			$this->data = null;

	}
	public function setSession($session){

		if (isset($session['user'])) 
			$this->app_session_user = $session['user'];
		else
			$this->app_session_user = null;
	}

}
*/

/*-------------REQUEST---------------------------*/
if (isset($_GET['page'])) 
	$page = htmlentities($_GET['page']);
else
	$page = 'home';

if (isset($_GET['action'])) 
	$action = htmlentities($_GET['action']);
else
	$action = null;

if (isset($_GET['data'])) 
	$data = htmlentities($_GET['data']);
else
	$data = null;

if (isset($_SESSION['user'])) 
	$app_session_user = $_SESSION['user'];
else
	$app_session_user = null;


/*--------------RESPONSE---------------------------*/

if ($page == 'home') {
	$listArticles = $articleController->indexAction('DESC', 30);
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array(
		'listArticles' => $listArticles,
		'app_session_user' => $app_session_user
	));
}
elseif ($page == 'inscription') {

	if (empty($app_session_user) & empty($_POST)) {
		$template = $twig->load('core/inscription.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));
	}
	elseif (empty($app_session_user) & !empty($_POST)) {

		$datasUser = $_POST;
		$validAddUser = $userController->addUserInscription($datasUser);

		if ($validAddUser === false) {
			$flashMessage = 'Nous avons rencontré un problème. Veuillez réessayer svp.';
			$flashName = 'danger';
			$template = $twig->load('core/inscription.html.twig');
			echo $template->render(array(
				'datasForm' => $_POST,
				'app_session_user' => $app_session_user,
				'flashMessage' => $flashMessage,
				'flashName' =>$flashName
			));
		}
		elseif ($validAddUser === true) {
			$flashMessage = 'Vous êtes bien inscrit. Veuillez vous connecter pour vous identifier.';
			$flashName = 'success';
			$template = $twig->load('core/login.html.twig');
			echo $template->render(array(
				'app_session_user' => $app_session_user,
				'flashMessage' => $flashMessage,
				'flashName' =>$flashName
			));
		}else{
			header('Location: inscription');
		}
	}else{
		header('Location: home');
	}
}
elseif ($page == 'login') {

	if (empty($app_session_user) & empty($_POST))
	{
		$template = $twig->load('core/login.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));

	}
	elseif (empty($app_session_user) & !empty($_POST))
	{
		$userConnection = $userController->connexionUser($_POST);
		if ($userConnection === true) {
			header('Location: home');
		}
		elseif ($userConnection === false) {
			$template = $twig->load('core/login.html.twig');
			echo $template->render(array(
				'datasForm' => $_POST,
				'app_session_user' => $app_session_user
			));
		}
		else{
			header('Location: home');
		}

	}else{
		header('Location: home');
	}
}
elseif ($page == 'admin' & ($action == 'inactivationUser' || $action == 'activationUser') & $data != null) {
		if ($app_session_user['role'] != 1) {
			trigger_error("Vous devez être administrateur.");
		}
		$idUser = (int)$data;

		$userController->activationUser($action, $idUser);

		if ($userController == false) {
			$flashMessage = "L'utilisateur a bien été désactivé.";
			$flashName = "success";
		}
		if ($userController == true) {
			$flashMessage = "L'utilisateur a bien été activé.";
			$flashName = "success";
		}
		
		$listUsers = $userController->queryAllUser();
		$listArticles = $articleController->indexAction('DESC', 100);

		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'listUsers' => $listUsers,
			'app_session_user' => $app_session_user,
			'flashMessage' => $flashMessage,
			'flashName' => $flashName,
			'activeMenu' => 'tabUser'
		));
}
elseif ($page == 'admin' & $action == 'delArticle' & $data != null) {
	$idArticle = (int) $data;
	$delArticle = $articleController->deleteArticle($idArticle, $app_session_user);

	if ($delArticle == true) {
		header("Location: admin");
	}
	elseif ($delArticle == false) {
		$flashMessage = '.....';
		$flashName = 'danger';
		$listArticles = $articleController->indexAction('DESC', 30);
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => $flashName,
			'activeMenu' => 'tabArticle',
			'app_session_user' => $app_session_user
		));
	}else{
		header("Location: /admin");		
	}
}
elseif ($page == 'admin' & $action == 'updArticle' & $data != null) {

	$idArticle = $data;
	$article = $articleController->getArticleAction($idArticle, $app_session_user);

	if ($app_session_user != null & empty($_POST)) {
		$listCategories = $articleController->findCategorieArticleAction();
		$template = $twig->load('core/upd_article.html.twig');
		echo $template->render(array(
			'datasForm' => $article,
			'listCategories' => $listCategories,
			'app_session_user' => $app_session_user
		));	
	}
	elseif ($app_session_user != null & !empty($_POST)) {
		$articleUpdate = $articleController->updateArticle($idArticle, $_POST, $app_session_user);
		$slug = $articleUpdate->get_sSlug();
		header("Location: article/view/$slug");
	}
}
elseif ($page == 'admin' & ($action == 'activationArticle' || $action == 'inactivationArticle') & $data != null) {
		if ($app_session_user['role'] != 1) {
			trigger_error("Vous devez être administrateur.");
		}
		$idArticle = (int)$data;
		if ($action == 'inactivationArticle' & $app_session_user['role'] == 1) {
			$articleController->activationArticle($idArticle, $app_session_user, 'inactivation');
			$flashMessage = "L'article a bien été désactivé.";
			$flashName = "success";
		}
		if ($action == 'activationArticle' & $app_session_user['role'] == 1) {
			$articleController->activationArticle($idArticle, $app_session_user, 'activation');
			$flashMessage = "L'article a bien été activé.";
			$flashName = "success";
		}
		
		$listUsers = $userController->queryAllUser();
		$listArticles = $articleController->indexAction('DESC', 100);

		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'listUsers' => $listUsers,
			'app_session_user' => $app_session_user,
			'flashMessage' => $flashMessage,
			'flashName' => $flashName,
			'activeMenu' => 'tabArticle'
		));
}
elseif ($page == 'admin' & $action == null & $data == null) {

		if ($app_session_user['role'] != 1) {
			trigger_error("Vous devez être administrateur.");
		}

		$listArticles = $articleController->indexAction('DESC', 100);
		$listCategories = $categorieController->getAllCategories();
		$listUsers = $userController->queryAllUser();

		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'listCategories' => $listCategories,
			'listUsers' => $listUsers,
			'app_session_user' => $app_session_user,
			'activeMenu' => 'tabUser'
		));
}
elseif ($page == 'admin' & $action == 'addCategorie' & $data == null) {

	if ($app_session_user != null & empty($_POST)) {
		header('Location: admin');
	}
	elseif ($app_session_user != null & !empty($_POST)) {
		
		$newCategorie = $categorieController->addCategorie($_POST,$app_session_user);

		if ($newCategorie === true) {
			header("Location: admin");
		}else{
			header("Location: home");
		}

	}
	else{
		$flashMessage = 'Vous devez être connecté pour ajouter un article.';
		$flashName = 'danger';
		$listArticles = $articleController->indexAction('DESC', 30);
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => $flashName,
			'app_session_user' => $app_session_user
		));
	}
}
elseif ($page == 'article' & $action == 'view' & $data != null) {

	$article = $articleController->getArticleAction($data);

	$template = $twig->load('core/article.html.twig');
	echo $template->render(array(
		'article' => $article,
		'app_session_user' => $app_session_user
	));
}
elseif ($page == 'article' & $action == 'addArticle') {

	if ($app_session_user != null & empty($_POST['formArticle'])) {
		$listCategories = $articleController->findCategorieArticleAction();

		$template = $twig->load('core/add_article.html.twig');
		echo $template->render(array(
			'listCategories' => $listCategories,
			'app_session_user' => $app_session_user
		));
	}
	elseif ($app_session_user != null & !empty($_POST['formArticle'])) {
		
		$newArticle = $articleController->addArticleAction($_POST,$app_session_user);

		if ($newArticle === false) {
			$flashMessage = 'Nous avons rencontré un problème. Veuillez réessayer svp.';
			$flashName = 'danger';
			$listCategories = $articleController->findCategorieArticleAction();
			$template = $twig->load('core/add_article.html.twig');
			echo $template->render(array(
				'datasForm' => $_POST,
				'listCategories' => $listCategories,
				'app_session_user' => $app_session_user,
				'flashMessage' => $flashMessage,
				'flashName' =>$flashName
			));
		}else{
			//Affichage de l'article via son Slug
			$slug = $newArticle->get_sSlug();
			header("Location: article/view/$slug");
		}

	}
	else{
		$flashMessage = 'Vous devez être connecté pour ajouter un article.';
		$flashName = 'danger';
		$listArticles = $articleController->indexAction('DESC', 30);
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => $flashName,
			'app_session_user' => $app_session_user
		));
	}
}
// Recherche via les liens catégories
elseif ($page == 'search' & !empty($_GET['action']) & empty($_POST)) {

	$keyWordSearch = $_GET['action'];

	if (!empty($keyWordSearch)) 
	{
		$limit = 30;
		$listArticles = $articleController->searchArticlesByCategorie($keyWordSearch, $limit);

		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'app_session_user' => $app_session_user
		));
	}else{
		$template = $twig->load('core/search.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));		
	}
}// Recherche via le formulaire
elseif ($page == 'search' & empty($_GET['search']) & isset($_POST)) {

	if (!empty($_POST)) 
	{
		$keyWord = $_POST['_search'];
		$limit = 30;
		$listArticles = $articleController->searchArticlesByKeyWord($keyWord, $limit);
	
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'app_session_user' => $app_session_user
		));

	}else{
		$template = $twig->load('core/search.html.twig');
		echo $template->render(array('app_session_user' => $app_session_user));		
	}
}
elseif ($page == 'logout') {
	setcookie(session_name(), 'vide', time() - 25*3600);
	session_destroy();
	$_SESSION = array();
	unset($app_session_user);
	header('Location: home');
}
else{
	$template = $twig->load('error/error404.html.twig');
	echo $template->render(array('app_session_user' => $app_session_user));
}

?>