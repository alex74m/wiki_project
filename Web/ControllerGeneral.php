<?php

namespace Web;

use \App\Controller\ArticleController;
use \App\Controller\CategorieController;
use \App\Controller\UserController;

class ControllerGeneral
{
	
	private $repository;
	private $ArticleManager;
	private $categoriesManager;
	private $userManager;

	public function __construct(DbRequest $dbRequest){
		$this->repository = $dbRequest;
		$this->articleManager = new ArticleController($dbRequest);
		$this->categorieManager = new CategorieController($dbRequest);
		$this->userManager = new UserController($dbRequest);
	}

	/**
	 * Get the entity repository App\Repository\DbRequest
	 * return PDO Object
	 */
	public function getDbRequest(){
		return $this->repository;
	}

	public function setHomepage()
	{
		$listArticles = $this->articleManager->indexAction('DESC', 30);
		$template = $twig->load('core/index.html.twig');
		echo $template->render(array(
			'listArticles' => $listArticles,
			'app_session_user' => $app_session_user
		));
	}
}