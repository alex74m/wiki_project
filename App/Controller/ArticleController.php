<?php

namespace App\Controller;


use \App\Repository\DbRequest;
use \App\Model\Article;
use \App\Model\User;

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
		//"SELECT * FROM article"
		$datasQuery = $this->getDbRequest()->queryAll("
			SELECT * FROM article 
			NATURAL JOIN user WHERE article.usr_id=user.usr_id
		");

	//	var_dump(($datasQuery));
		$listArticle = [];

		foreach ($datasQuery as $row) 
		{
			$listArticle[] = $this->entityBuilder($row);



			/*$article = new Article();
			$article->set_Id($row->{'art_id'});
			$article->set_iAuteurId($row->{'usr_id'});
			$article->set_sTitre($row->{'art_sTitre'});
			$article->set_sContenu($row->{'art_sContenu'});
			$article->set_dDateAjout($row->{'art_dDateCreation'});
			$article->set_dDateLastModif($row->{'art_dDateLastModif'});
			$article->set_bActif($row->{'art_bActif'});
			$article->set_sSlug($row->{'art_sSlug'});
			$listArticle[] = $article;
			*/
		}

		//var_dump(count($listArticle));
		return $listArticle;
	}

	private function entityBuilder($row)
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
			return $article;

	}
}

