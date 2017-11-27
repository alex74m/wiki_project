<?php


if ($page == 'home') {
	$listArticles = $articleController->indexAction('DESC', 30);
	$template = $twig->load('core/index.html.twig');
	echo $template->render(array('listArticles' => $listArticles));
}
elseif ($page == 'inscription') {

	var_dump($_SESSION);
	var_dump($_POST);

	if (empty($_SESSION['user']) & empty($_POST['userInscription'])) {
		$template = $twig->load('core/inscription.html.twig');
		echo $template->render();

	}
	elseif (empty($_SESSION['user']) & !empty($_POST['userInscription'])) {

		

		// A traiter par la suite
		$_SESSION['user']['id'] = 1;
		$_SESSION['user']['sNom'] = 'marguet';
		$_SESSION['user']['sPrenom'] = 'alex';
		$_SESSION['user']['bActif'] = 1;

		// Vérification des données post vers le controleur User
		// Création de la session User
		$flashMessage = 'Vous êtes bien connecté.';


		$listArticles = $articleController->indexAction('DESC', 30);
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'flashMessage' => $flashMessage,
			'flashName' => 'success'
		));
	}else{
		var_dump('ok');
		die();
		header('Location: home');
	}
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
		$listArticles = $articleController->indexAction('DESC', 30);
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
		$listArticles = $articleController->indexAction('DESC', 30);
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


?>