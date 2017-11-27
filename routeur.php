<?php


if ($page == 'home') {
	$listArticles = $articleController->indexAction('DESC', 30);
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array('listArticles' => $listArticles));
}
elseif ($page == 'inscription') {

	if (empty($_SESSION['user']) & empty($_POST['userInscription'])) {
		$template = $twig->load('core/inscription.html.twig');
		echo $template->render();

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
		echo $template->render();

	}
	elseif (empty($_SESSION['user']) & !empty($_POST['userConnexion'])) {

		
		$userConnexion = $userController->connexionUser($_POST);

		if ($userConnexion == true) {
			$flashMessage = 'Vous êtes bien connecté.';
			$flashName = 'success';
		}

		
		$listArticles = $articleController->indexAction('DESC', 30);
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => $flashName
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

	$article = $articleController->viewArticleAction($slug);

	$template = $twig->load('core/article.html.twig');
	echo $template->render(array('article' => $article));

}
elseif ($page == 'article' & $action == 'add') {

	if (!empty($_SESSION['user']) & empty($_POST['formArticle'])) {
		$listCategories = $articleController->findCategorieArticleAction();

		$template = $twig->load('core/add_article.html.twig');
		echo $template->render(array(
			'listCategories' => $listCategories
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
			'flashName' => $flashName
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


?>