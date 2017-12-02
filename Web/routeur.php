<?php

/*-------------REQUEST---------------------------*/
if (isset($_GET['page'])) {
	$page = htmlentities($_GET['page']);
}else{
	$page = 'home';
}
if (isset($_GET['action'])) {
	$action = htmlentities($_GET['action']);
}else{
	$action = null;
}
if (isset($_GET['data'])) {
	$data = htmlentities($_GET['data']);
}else{
	$data = null;
}
if (isset($_SESSION['user'])) {
	$app_session_user = $_SESSION['user'];
}else{
	$app_session_user = null;
}

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

	if (empty($_SESSION['user']) & empty($_POST)) {
		$template = $twig->load('core/inscription.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));
	}
	elseif (empty($_SESSION['user']) & !empty($_POST)) {

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

	if (empty($_SESSION['user']) & empty($_POST))
	{
		$template = $twig->load('core/login.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));

	}
	elseif (empty($_SESSION['user']) & !empty($_POST))
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
		if ($action == 'inactivationUser') {
			$userController->inactivationUser($idUser);
			$flashMessage = "L'utilisateur a bien été désactivé.";
			$flashName = "success";
		}
		if ($action == 'activationUser') {
			$userController->activationUser($idUser);
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
			'flashName' => $flashName
		));
}
elseif ($page == 'admin' & $action == null) {

		if ($app_session_user['role'] != 1) {
			trigger_error("Vous devez être administrateur.");
		}

		$listUsers = $userController->queryAllUser();
		$listArticles = $articleController->indexAction('DESC', 100);

		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'listUsers' => $listUsers,
			'app_session_user' => $app_session_user
		));
}
elseif ($page == 'article' & $action == 'view' & $data != null) {

	$article = $articleController->viewArticleAction($data);

	$template = $twig->load('core/article.html.twig');
	echo $template->render(array(
		'article' => $article,
		'app_session_user' => $app_session_user
	));
}
elseif ($page == 'article' & $action == 'add') {

	if (!empty($_SESSION['user']) & empty($_POST['formArticle'])) {
		$listCategories = $articleController->findCategorieArticleAction();

		$template = $twig->load('core/add_article.html.twig');
		echo $template->render(array(
			'listCategories' => $listCategories,
			'app_session_user' => $app_session_user
		));
	}
	elseif (!empty($_SESSION['user']) & !empty($_POST['formArticle'])) {
		
		$newArticle = $articleController->addArticleAction($_POST,$_SESSION);

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
			$data = $newArticle->get_sSlug();
			header("Location: article/view/$data");
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
}// Recherche via les liens catégories
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
		echo $template->render(array('app_session_user' => $app_session_user,));		
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
	echo $template->render(array('app_session_user' => $app_session_user,));
}

?>