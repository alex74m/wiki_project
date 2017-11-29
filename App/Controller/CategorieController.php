<?php

namespace App\Controller;

use \App\Controller\Interfaces\InterfaceController;
use \App\Repository\DbRequest;
use \App\Model\Categorie;


class CategorieController implements InterfaceController
{
	
	private $repository;

	public function __construct(DbRequest $dbRequest){
		$this->repository = $dbRequest;
	}
	/**
	 * Get the entity repository App\Repository\dBrequest
	 * return PDO Object
	 */
	public function getDbRequest(){
		return $this->repository;
	}

	/**
	 * use Interface InterfaceController
	 * Set entities Categorie App\Model\Categorie
	 * return array Objects
	 * @param string $entity 
	 */
	public function entityBuilder($row){
		$categorie = new Categorie();
		$categorie->set_id($row->{'cat_id'});
		$categorie->set_sNom($row->{'cat_sNom'});
		$categorie->set_sResume($row->{'cat_sResume'});
		$categorie->set_bActif($row->{'cat_bActif'});
		$categorie->set_sSlug($row->{'cat_sSlug'});
		$categorie->set_sCodeHexa($row->{'cat_sCodeHexa'});

		return $categorie;
	}
}