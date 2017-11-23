<?php

namespace App\Controller;


use \App\Repository\DbRequest;
use \App\Model\Article;


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

	public function indexAction($sqlRequest)
	{
		$datasQuery = $this->getDbRequest()->queryAll($sqlRequest);
		//var_dump(count($datasQuery));

		//var_dump(($datasQuery));
		$listArticle = [];

		foreach ($datasQuery as $key => $value) 
		{
			$article = new Article();
			$article->set_Id($datasQuery['art_id']);
			$article->set_iAuteurId($datasQuery['usr_id']);
			$article->set_sTitre($datasQuery['art_sTitre']);
			$article->set_sContenu($datasQuery['art_sContenu']);
			//$article->set_dDateAjout($datasQuery['art_dDateCreation']);
			//$article->set_dDateLastModif($datasQuery['art_dDateLastModif']);
			$article->set_bActif($datasQuery['art_bActif']);
			$article->set_sSlug($datasQuery['art_sSlug']);
			$listArticle[] = $article;
		}

		//var_dump(count($listArticle));
		return $listArticle;
	}
}

/*
$article->set_Id($datasQuery['art_id']);
$article->set_iAuteurId($datasQuery['usr_id']);
$article->set_sTitre($datasQuery['art_sTitre']);
$article->set_sContenu($datasQuery['art_sContenu']);
//$article->set_dDateAjout($datasQuery['art_dDateCreation']);
//$article->set_dDateLastModif($datasQuery['art_dDateLastModif']);
$article->set_bActif($datasQuery['art_bActif']);
$article->set_sSlug($datasQuery['art_sSlug']);
*/

/*
	$article->hydrate(array(
		'Id' => $datasQuery['art_id'],
		'iAuteurId' => $datasQuery['usr_id'],
		'sTitre' => $datasQuery['art_sTitre'],
		'sContenu' => $datasQuery['art_sContenu'],
		'bActif' => $datasQuery['art_bActif'],
		'sSlug' => $datasQuery['art_sSlug']
	));
*/

/*
$article->set_Id($datasQuery->{'art_id'});
$article->set_iAuteurId($datasQuery->{'usr_id'});
$article->set_sTitre($datasQuery->{'art_sTitre'});
$article->set_sContenu($datasQuery->{'art_sContenu'});
$/$article->set_dDateAjout($datasQuery->{'art_dDateCreation'});
$/$article->set_dDateLastModif($datasQuery->{'art_dDateLastModif'});
$article->set_bActif($datasQuery->{'art_bActif'});
$article->set_sSlug($datasQuery->{'art_sSlug'});
*/