<?php


if ($page == 'home') {
	$listArticles = $articleController->indexAction('DESC', 30);
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array(
		'listArticles' => $listArticles,
		'app_session_user' => $app_session_user
	));
}
elseif ($page == 'inscription') {

	if (empty($_SESSION['user']) & empty($_POST['userInscription'])) {
		$template = $twig->load('core/inscription.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));

	}
	elseif (empty($_SESSION['user']) & !empty($_POST['userInscription'])) {

		$datasUser = $_POST;
		$validAddUser = $userController->addUserInscription($datasUser);

		if ($validAddUser == false) {
			$flashMessage = 'Nous avons rencontrer un problème. Veuillez réessayer svp.';
			$flashName = 'danger';
		}

		$flashMessage = 'Vous êtes bien inscrit. Veuillez vous connecter pour vous identifier.';
		$flashName = 'success';

		header('Location: login');

	}else{
		header('Location: home');
	}
}
elseif ($page == 'login') {

	if (empty($_SESSION['user']) & empty($_POST['userConnexion'])) {
		$template = $twig->load('core/login.html.twig');
		echo $template->render(array(
			'app_session_user' => $app_session_user
		));

	}
	elseif (empty($_SESSION['user']) & !empty($_POST['userConnexion'])) {

		$user = $userController->connexionUser($_POST);

		if (!is_object($user) & !empty($user)) {
			trigger_error("L'user doit être de type object.");
		}

		$_SESSION['user']['id'] = $user->get_id();
		$_SESSION['user']['nom'] = $user->get_sNom();
		$_SESSION['user']['prenom'] = $user->get_sPrenom();
		$_SESSION['user']['mail'] = $user->get_sMail();
		$_SESSION['user']['role'] = $user->get_bAdmin();

		header('Location: home');

	}elseif (!empty($_SESSION['user'])) {
		header('Location: home');
	}
}
elseif ($page == 'admin') {

		$template = $twig->load('admin/manager.html.twig');
		echo $template->render(array(
		'app_session_user' => $app_session_user
		));
}
elseif ($page == 'article' & $action == 'view' & $slug != null) {

	$article = $articleController->viewArticleAction($slug);

	$template = $twig->load('core/article.html.twig');
	echo $template->render(array('article' => $article));
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

		//Affichage de l'article via son Slug
		$slug = $newArticle->get_sSlug();
		header("Location: article/view/$slug");

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
elseif ($page == 'search') {
var_dump($_POST);
	if (!empty($_POST)) {
		var_dump($keyWord);
		$keyWord = htmlentities($_POST['_search']);
		$listArticles = $articleController->searchArticles($keyWord);

		$template = $twig->load('core/search.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'app_session_user' => $app_session_user
		));

	}

	$template = $twig->load('core/search.html.twig');
	echo $template->render();
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

?>