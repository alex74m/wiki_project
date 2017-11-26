<?php

namespace App\Controller;


use \App\Repository\DbRequest;
use \App\Model\Article;
use \App\Model\User;
use \App\Model\Categorie;
use \App\Services\Slug;

/**
* @ArticleController
*/
class ArticleController
{
	
	private $repository;

	public function __construct(DbRequest $dbRequest){
		$this->repository = $dbRequest;
	}

	public function getDbRequest(){
		return $this->repository;
	}

	public function indexAction()
	{
		$articlesQuery = $this->getDbRequest()->queryAll("
			SELECT * FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id
			ORDER BY article.art_dDateCreation DESC LIMIT 30");

		$listArticle = [];
		foreach ($articlesQuery as $row){
			$listArticle[] = $this->articleBuilder($row);
		}

		return $listArticle;
	}

	public function viewArticleAction($slug)
	{
		$articleQuery = $this->getDbRequest()->findOneBySlug("
			SELECT * FROM article 
			LEFT JOIN user ON article.usr_id=user.usr_id 
			WHERE article.art_sSlug='$slug'",$slug);

		if(!empty($articleQuery))
			$article = $this->articleBuilder($articleQuery);

		return $article;
	}

	public function addArticleAction($post, $session)
	{
		//var_dump($datas);
		$slug = new Slug();
		$slugName = $slug->slugify('my article');
		var_dump($slugName);

		$checkSlug = $this->getDbRequest()->checkSlug("SELECT * FROM article WHERE art_sSlug=:slug", $slugName);
		var_dump($checkSlug);
		if ($checkSlug === false ) {
			
		$slugName = $slug->createSlug('my article');
		var_dump($slugName);
		}





		/*$insertNewArticle = $this->getDbRequest()->insert('
			INSERT INTO article (usr_id, art_sTitre, art_sContenu, art_dDateCreation, art_sSlug)
			VALUES (:userId, :artTitre, :artContenu, NOW(), :artSlug)
		');
		*/

	}

	public function findCategorieArticleAction()
	{
		$queryCategories = $this->getDbRequest()->queryAll('SELECT * FROM categorie');

		$listCategories = [];
		foreach ($queryCategories as $row){
			$listCategories[] = $this->categoryBuilder($row);
		}

		return $listCategories;

	}

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
		

	private function articleBuilder($row)
	{
			$article = new Article();
			$article->set_Id($row->{'art_id'});

			if (!empty($row->{'usr_id'})) {
				$author = new User();
				$author->set_id($row->{'usr_id'});
				$author->set_sNom($row->{'usr_sNom'});
				$author->set_sPrenom($row->{'usr_sPrenom'});
				$author->set_sMail($row->{'usr_sMail'});
				$author->set_bActif($row->{'usr_bActif'});
				$author->set_bAdmin($row->{'usr_bAdmin'});
				$author->set_sAvatar($row->{'usr_sAvatar'});
				$article->set_iAuteurId($author);
			}
			
			$article->set_sTitre($row->{'art_sTitre'});
			$article->set_sContenu($row->{'art_sContenu'});
			$article->set_dDateAjout($row->{'art_dDateCreation'});
			$article->set_dDateLastModif($row->{'art_dDateLastModif'});
			$article->set_bActif($row->{'art_bActif'});
			$article->set_sSlug($row->{'art_sSlug'});

			$categoryQuery = $this->getDbRequest()->findById("
				SELECT * FROM categorie,join_article_categorie
				WHERE join_article_categorie.art_id=:id 
				AND join_article_categorie.cat_id=categorie.cat_id
			", $article->get_Id());

			if (!empty($categoryQuery)) {
				$articleCategories = array();
				foreach ($categoryQuery as $row){
					$categories = new Categorie();
					$categories->set_sNom($row->{'cat_sNom'});
					$articleCategories[] = $categories;
				}
				$article->set_aCategories($articleCategories);
			}
			return $article;
	}
	
}