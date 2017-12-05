<?php

namespace App\Controller;

use \App\Controller\Interfaces\InterfaceController;
use \App\Controller\CategorieController;
use \App\Controller\UserController;
use \App\Repository\DbRequest;
use \App\Model\Article;
use \App\Model\User;
use \App\Model\Categorie;
use \App\Services\Slug;
use \App\Form\ArticleForm;

/**
 * @ArticleController
 * Entity : Article
 */
class ArticleController implements InterfaceController
{

	private $repository;
	private $categoryBuilder;
	private $userBuilder;

	public function __construct(DbRequest $dbRequest){
		$this->repository = $dbRequest;
		$this->categoryBuilder = new CategorieController($dbRequest);
		$this->userBuilder = new UserController($dbRequest);
	}

	/**
	 * Get the entity repository App\Repository\DbRequest
	 * return PDO Object
	 */
	public function getDbRequest(){
		return $this->repository;
	}

	/**
	 * Get entities Article App\Model\Article
	 * return array [Articles Objects]
	 * @param string $order Set the order of Articles - Homepage (index.php)
	 * @param null|int $limit The number of Article in loop
	 */
	public function indexAction($order = 'DESC', $limit = null)
	{	
		$articlesQuery = $this->getDbRequest()->queryAll("
			SELECT * FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id
			ORDER BY article.art_dDateCreation $order LIMIT $limit");

		$listArticle = [];
		foreach ($articlesQuery as $row){
			$listArticle[] = $this->entityBuilder($row);
		}

		return $listArticle;
	}

	/**
	 * Get entity Article App\Model\Article
	 * return Article Object
	 * @param string $slug A string within article slug name
	 */
	public function getArticleAction($slug, $user = null)
	{
		/*
			$articleQuery = $this->getDbRequest()->findOneByData("
			SELECT * FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id 
			WHERE article.art_sSlug=:data",$slug);
		*/

		$articleQuery = $this->getDbRequest()->queryOneByOneField("
			SELECT * FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id 
			WHERE article.art_sSlug=:field",$slug, 'string', 'object'
		);

		if(!empty($articleQuery))
			$article = $this->entityBuilder($articleQuery);
		else
			$article = null;

		return $article;
	}

	/**
	 * Create a new entity Article App\Model\Article in bdd
	 * return Article Object || bool([false] if error)
	 * @param array $post An array POST
	 * @param array $user Get User session
	 */
	public function addArticleAction($post, $user)
	{
		if (empty($user)) {
			trigger_error("Merci de vous connecter pour accéder à ce service.");
			return false;
		}

		// Utilisation d'un objet ArticleForm pour la validation des données.
		// Cette objet implémente l'interface App\Services\FormValidator
		// return bool
		$articleAddForm = new ArticleForm();
		$isValid = $articleAddForm->builderFormValidator($post);
		if ($isValid === true)
		{
			// Create new pre-User object
			$userId = $user['user']['id'];
			$user = new User();
			$user->set_id($userId);

			/*
			 * Use App\Services\Slug
			 * Set title slugyfication
			 * return string
	 		 * @param string 
			 */
			$slugName = Slug::slugify($post['_sTitre']);
			$checkSlug = $this->getDbRequest()->checkField('article', 'art_sSlug', $slugName);
			if ($checkSlug === false) {
				$slugName = Slug::createSlug($post['_sTitre']);
			}

			// Create new Article
			$article = new Article();
			$article->set_iAuteurId($user);
			$article->set_sTitre($post['_sTitre']);
			$article->set_sContenu($post['_sContenu']);
			$article->set_bActif(0);
			$article->set_sSlug($slugName);
		
			$params = array(
				':usr_id' => $article->get_iAuteurId()->get_id(),
				':art_sTitre' => $article->get_sTitre(),
				':art_sContenu' => $article->get_sContenu(),
				':art_sSlug' => $slugName
			);

			// create new insert request in database
			$insertNewArticle = $this->getDbRequest()->insert("
				INSERT INTO article (usr_id, art_sTitre, art_sContenu, art_dDateCreation, art_sSlug)
				VALUES (:usr_id, :art_sTitre, :art_sContenu, NOW(), :art_sSlug)
			",$params);
			
			/*
			 * Extract datas catagories
			 * Check if is exist in database
			 */
			foreach ($post as $key => $value) {
				$keyName = explode('_', $key);
				$keyName = $keyName[0];
				if ($keyName === 'categorie') {
					$checkCategorie =$this->getDbRequest()->checkField('categorie', 'cat_id', $value);
					if ($checkCategorie === false) {
						$params = array(
							'art_id' => $insertNewArticle,
							'cat_id' => $value
						);
						$this->getDbRequest()->insert("
							INSERT INTO join_article_categorie (art_id, cat_id)
							VALUES (:art_id, :cat_id)
						", $params);
					}else{
						//throw new Exception("Les catégories sélectionnées n'existent pas.", 1);
						trigger_error("Les catégories sélectionnées n'existent pas.");
						return false;
					}
				}
			}
			return $article;
		}else{
			return false;
		}
	}

	/**
	 * Update an entity Article App\Model\Article
	 * return Article Object
	 * @param int $id of the article
	 */
	public function updateArticle($idArticle, $post, $user)
	{

		if (empty($user)) {
			trigger_error("Merci de vous connecter pour accéder à ce service.");
			//trigger_error("Merci de vous connecter pour accéder à ce service.");
			return false;
		}

		// Utilisation d'un objet ArticleForm pour la validation des données.
		// Cette objet implémente l'interface App\Services\FormValidator
		// return bool
		$articleUpdForm = new ArticleForm();
		$isValid = $articleUpdForm->builderFormValidator($post);
		if ($isValid === true)
		{
			// Create new pre-User object
			$userId = $user['id'];
			$user = new User();
			$user->set_id($userId);
	

			/*
			 * Use App\Services\Slug
			 * Set title slugyfication
			 * return string
	 		 * @param string 
			 */
			$slugName = Slug::slugify($post['_sTitre']);
			$checkSlug = $this->getDbRequest()->checkField('article', 'art_sSlug', $slugName);
			if ($checkSlug === false) {
				$slugName = Slug::createSlug($post['_sTitre']);
			}

			// Create new Article
			$article = new Article();
			$article->set_iAuteurId($user);
			$article->set_sTitre($post['_sTitre']);
			$article->set_sContenu($post['_sContenu']);
			$article->set_bActif(0);
			$article->set_sSlug($slugName);

			$params = array(
				':art_sTitre' => $article->get_sTitre(),
				':art_sContenu' => $article->get_sContenu(),
				':art_sSlug' => $slugName,
				':art_id' => $idArticle
			);

			// create new insert request in database
			$updateArticle = $this->getDbRequest()->update("
				UPDATE article 
				SET art_sTitre=:art_sTitre, art_sContenu=:art_sContenu, art_dDateCreation=NOW(), art_sSlug=:art_sSlug
				WHERE art_id=:art_id
			",$params);
			
			/*
			 * Extract datas catagories
			 * Check if is exist in database
			 */
			foreach ($post as $key => $idCategory) {
				$keyName = explode('_', $key);
				$keyName = $keyName[0];
				if ($keyName === 'categorie')
				{
					$checkCategorie =$this->getDbRequest()->checkField('categorie', 'cat_id', $idCategory);
					if ($checkCategorie === false) {
						$params = array(
							'art_id' => $idArticle,
							'cat_id' => $idCategory
						);
						$checkCategorieInArticle = $this->getDbRequest()->queryEntityWithMultiField("
							SELECT art_id 
							FROM join_article_categorie 
							WHERE art_id=:art_id AND cat_id=:cat_id", $params
						);
						if ($checkCategorieInArticle === null) {
							$this->getDbRequest()->insert("
								INSERT INTO join_article_categorie (art_id, cat_id)
								VALUES (:art_id, :cat_id)
							", $params);
						}else{

						}
					}else{
						//throw new Exception("Les catégories sélectionnées n'existent pas.", 1);
						trigger_error("Les catégories sélectionnées n'existent pas.");
						return false;
					}
				}
			}
			return $article;
		}else{
			return false;
		}	
		
	}

	/**
	 * Activation or inactivation of an article in database
	 * return bool
	 * @param int $id of the article
	 */
	public function activationArticle($idArticle, $user, $action){

		if (empty($user)) {
			trigger_error("Vous n'êtes pas connecté.");
			return false;
		}
		elseif (!empty($user)) {
			if ($user['role'] == 1) {
				$idExist = $this->getDbRequest()->checkField('article', 'art_id', $idArticle);
				if ($idExist == false) {
					if ($action == 'activation') {
						$actif = 1;
					}
					elseif ($action == 'inactivation') {
						$actif = 0;
					}else{
						trigger_error("Vous pouvez seulement activer ou inactiver un article.");
						return false;
					}
					$params = array(
						':art_bActif' => $actif,
						':art_id' => $idArticle
					);
					$this->getDbRequest()->update("UPDATE article SET art_bActif=:art_bActif WHERE art_id=:art_id",$params);
					return true;
				}
			}
			elseif ($user['role'] == 0) {
				trigger_error("Vous n'avez pas les droit nécéssaire.");
				return false;
			}
		}
		else{
			return false;
		}
	}

	/**
	 * Delete an article in database
	 * return bool
	 * @param int $id of the article
	 */
	public function deleteArticle($idArticle,$user){

		if (empty($user)) {
			trigger_error("Vous n'êtes pas connecté");
			return false;
		}
		elseif (!empty($user)) {
			if ($user['role'] == 1) {
				$idExist = $this->getDbRequest()->checkField('article', 'art_id', $idArticle);
				if ($idExist == false) {
					$this->getDbRequest()->delete("DELETE FROM join_article_categorie WHERE art_id=:id",$idArticle);
					$this->getDbRequest()->delete("DELETE FROM article WHERE art_id=:id",$idArticle);
					return true;
				}
			}
			elseif ($user['role'] == 0) {
				$params = array(
					':idArticle' => $idArticle,
					':idUser' => $user['id']
				);
				$correspondArticle = $this->getDbRequest()->queryEntityWithMultiField("SELECT art_id FROM article WHERE art_id=:idArticle AND usr_id=:idUser",$params);
				if ($correspondArticle == true) {
					$this->getDbRequest()->delete("DELETE FROM join_article_categorie WHERE art_id=:id",$idArticle);
					$this->getDbRequest()->delete("DELETE FROM article WHERE art_id=:id",$idArticle);
					return true;
				}else{
					trigger_error("Vous devez être l'auteur de l'article pour pouvoir le supprimer");
					return false;
				}
			}
		}
		else{
			return false;
		}
	}

	/**
	 * Search entities Articles App\Model\Article - METHOD POST
	 * return array Article Objects
	 * @param null|string $keyWord A string with keyWord name by POST method
	 * @param null|int $limit The number of Article in loop
	 */
	public function searchArticlesByKeyWord($keyWord=null, $limit=null)
	{

		$articlesQuery = $this->getDbRequest()->queryAllBySearch("
			SELECT article.art_id,article.usr_id,article.art_sTitre,article.art_sContenu,article.art_dDateCreation,article.art_dDateLastModif,article.art_bActif,article.art_sSlug, user.usr_id,user.usr_sNom,user.usr_sPrenom,user.usr_sMail,user.usr_bAdmin,user.usr_sToken, user.usr_bActif,user.usr_sAvatar
			FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id
			WHERE CONCAT(article.art_sTitre, article.art_sContenu,article.art_dDateCreation,article.art_sSlug) 
			LIKE :keyWord
			LIMIT $limit
			", $keyWord);

		$listArticle = [];
		foreach ($articlesQuery as $row){
			$listArticle[] = $this->entityBuilder($row);
		}

		return $listArticle;
	}

	/**
	 * Search entities Articles App\Model\Article within Categories - METHOD GET
	 * return array Article Objects
	 * @param null|string $keyWord A string with keyWord name by GET method
	 * @param null|int $limit The number of Article in loop
	 */
	public function searchArticlesByCategorie($keyWord = null, $limit = null)
	{

		$articlesQuery = $this->getDbRequest()->queryAllBySearch("
			SELECT DISTINCT article.art_id,article.usr_id,article.art_sTitre,article.art_sContenu,article.art_dDateCreation,article.art_dDateLastModif,article.art_bActif,article.art_sSlug, user.usr_id,user.usr_sNom,user.usr_sPrenom,user.usr_sMail,user.usr_sToken,user.usr_bAdmin, user.usr_bActif,user.usr_sAvatar, categorie.cat_sNom
			FROM article 
			INNER JOIN user ON article.usr_id=user.usr_id 
			INNER JOIN join_article_categorie ON join_article_categorie.art_id=article.art_id 
			INNER JOIN categorie ON categorie.cat_id=join_article_categorie.cat_id 
			WHERE categorie.cat_sNom LIKE :keyWord
			ORDER BY article.art_dDateCreation
			LIMIT $limit
			", $keyWord);

		$listArticle = [];
		foreach ($articlesQuery as $row){
			$listArticle[] = $this->entityBuilder($row);
		}

		return $listArticle;
	}


	/**
	 * Get entities Categorie App\Model\Categorie within Categories
	 * return array Categorie Objects
	 */
	public function findCategorieArticleAction()
	{
		$queryCategories = $this->getDbRequest()->queryAll('
			SELECT cat_id, cat_sNom, cat_sResume, cat_bActif, cat_sSlug, cat_sCodeHexa
			FROM categorie
		');

		$listCategories = [];
		foreach ($queryCategories as $row){
			$listCategories[] = $this->categoryBuilder->entityBuilder($row);
		}

		return $listCategories;

	}

	/**
	 * use App\Controller\Interface\InterfaceController 
	 * use App\Controller\UserController 		=> categoryBuilder 
	 * use App\Controller\CategorieController 	=> userBuilder
	 * Set InterfaceEntities Article App\Model\Article
	 * return array Objects
	 * @param string $entity 
	 */	
	final function entityBuilder($row)
	{
			$article = new Article();
			$article->set_Id($row->{'art_id'});

			if (!empty($row->{'usr_id'})) {
				$author = $this->userBuilder->entityBuilder($row);
				$article->set_iAuteurId($author);
			}
			
			$article->set_sTitre($row->{'art_sTitre'});
			$article->set_sContenu($row->{'art_sContenu'});
			$article->set_dDateAjout($row->{'art_dDateCreation'});
			$article->set_dDateLastModif($row->{'art_dDateLastModif'});
			$article->set_bActif($row->{'art_bActif'});
			$article->set_sSlug($row->{'art_sSlug'});

			$sqlRequest = "SELECT * FROM categorie,join_article_categorie
				WHERE join_article_categorie.art_id=:id 
				AND join_article_categorie.cat_id=categorie.cat_id";
			$reqCategorieJoin = $this->getDbRequest()->findById($sqlRequest,$article->get_Id(),'int', 'object');

			if (!empty($reqCategorieJoin)) {
				$articleCategories = array();
				foreach ($reqCategorieJoin as $row){
					$categories = $this->categoryBuilder->entityBuilder($row);
					$articleCategories[] = $categories;
				}

				$article->set_aCategories($articleCategories);
			}
			return $article;
	}
	
}