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
use \App\Form\ArticleAddForm;

/**
* @ArticleController
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
	 * return array Article Objects
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
	 * @param string $slug A string with slug name
	 */
	public function viewArticleAction($slug)
	{
		$articleQuery = $this->getDbRequest()->findOneByData("
			SELECT * FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id 
			WHERE article.art_sSlug='$slug'",$slug);

		if(!empty($articleQuery))
			$article = $this->entityBuilder($articleQuery);

		return $article;
	}

	/**
	 * Create a new entity Article App\Model\Article in bdd
	 * return Article Object
	 * @param array $post An array POST
	 * @param array $user Get User session
	 */
	public function addArticleAction($post, $user)
	{
		if (empty($user)) {
			trigger_error("Merci de vous connecter pour accéder à ce service.");
			return false;
		}

		$articleAddForm = new ArticleAddForm();
		$isValid = $articleAddForm->builderFormValidator($post);
		if ($isValid === true)
		{

			$userId = $user['user']['id'];
			$user = new User();
			$user->set_id($userId);


			$slugName = Slug::slugify($post['_sTitre']);
			$checkSlug = $this->getDbRequest()->checkField('article', 'art_sSlug', $slugName);
			if ($checkSlug === false) {
				$slugName = Slug::createSlug($post['_sTitre']);
			}

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

			
			$insertNewArticle = $this->getDbRequest()->insert("
				INSERT INTO article (usr_id, art_sTitre, art_sContenu, art_dDateCreation, art_sSlug)
				VALUES (:usr_id, :art_sTitre, :art_sContenu, NOW(), :art_sSlug)
			",$params);
			

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
					}
				}
			}
			return $article;

		}else{
			return false;
		}

	
	}

	/**
	 * Search entities Articles App\Model\Article
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
	 * Search entities Articles App\Model\Article within Categories
	 * return array Article Objects
	 * @param null|string $keyWord A string with keyWord name by GET method
	 * @param null|int $limit The number of Article in loop
	 */
	public function searchArticlesByCategorie($keyWord=null, $limit = null)
	{

		$articlesQuery = $this->getDbRequest()->queryAllBySearch("
			SELECT DISTINCT article.art_id,article.usr_id,article.art_sTitre,article.art_sContenu,article.art_dDateCreation,article.art_dDateLastModif,article.art_bActif,article.art_sSlug, user.usr_id,user.usr_sNom,user.usr_sPrenom,user.usr_sMail,user.usr_sToken,user.usr_bAdmin, user.usr_bActif,user.usr_sAvatar 
			FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id 
			LEFT JOIN join_article_categorie ON join_article_categorie.art_id=article.art_id 
			LEFT JOIN categorie ON categorie.cat_id=join_article_categorie.cat_id 
			LIKE categorie.cat_sNom=:keyWord
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
	 * Categorie(s) builder App\Model\Categorie
	 * return array Categorie Objects
	 *@ 
	 *
	private function categoryBuilder($row){
		$categorie = new Categorie();
		$categorie->set_id($row->{'cat_id'});
		$categorie->set_sNom($row->{'cat_sNom'});
		$categorie->set_sResume($row->{'cat_sResume'});
		$categorie->set_bActif($row->{'cat_bActif'});
		$categorie->set_sSlug($row->{'cat_sSlug'});
		$categorie->set_sCodeHexa($row->{'cat_sCodeHexa'});

		return $categorie;
	} 
	*/




	/**
	 * use Interface InterfaceController
	 * Set entities Article App\Model\Article
	 * return array Objects
	 * @param string $entity 
	 */	
	public function entityBuilder($row)
	{
			$article = new Article();
			$article->set_Id($row->{'art_id'});

			if (!empty($row->{'usr_id'})) {

				$author = $this->userBuilder->entityBuilder($row);

				/*$author = new User();
				$author->set_id($row->{'usr_id'});
				$author->set_sNom($row->{'usr_sNom'});
				$author->set_sPrenom($row->{'usr_sPrenom'});
				$author->set_sMail($row->{'usr_sMail'});
				$author->set_bActif($row->{'usr_bActif'});
				$author->set_bAdmin($row->{'usr_bAdmin'});
				$author->set_sAvatar($row->{'usr_sAvatar'});
				*/
				$article->set_iAuteurId($author);
			}
			
			$article->set_sTitre($row->{'art_sTitre'});
			$article->set_sContenu($row->{'art_sContenu'});
			$article->set_dDateAjout($row->{'art_dDateCreation'});
			$article->set_dDateLastModif($row->{'art_dDateLastModif'});
			$article->set_bActif($row->{'art_bActif'});
			$article->set_sSlug($row->{'art_sSlug'});

			$reqCategorieJoin = $this->getDbRequest()->queryJoinCategoriesByArticle($article->get_Id());

			if (!empty($reqCategorieJoin)) {
				$articleCategories = array();
				foreach ($reqCategorieJoin as $row){

					$categories = $this->categoryBuilder->entityBuilder($row);

					//$categories = $this->categoryBuilder($row);
					$articleCategories[] = $categories;
				}
				$article->set_aCategories($articleCategories);
			}
			return $article;
	}
	
}