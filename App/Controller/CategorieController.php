<?php

namespace App\Controller;

use \App\Controller\Interfaces\InterfaceController;
use \App\Repository\DbRequest;
use \App\Model\Categorie;
use \App\Form\CategorieForm;

use \App\Services\Slug;

/**
 * @CategorieController
 * Entity : Categorie
 */
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

	public function getAllCategories()
	{
		$queryCategories = $this->getDbRequest()->queryAll("
			SELECT cat_id, cat_sNom, cat_sResume, cat_bActif, cat_sSlug, cat_sCodeHexa
			FROM categorie
		");

		$listCategories = [];
		if (!empty($queryCategories)) {
			foreach ($queryCategories as $key => $row) {
				$categorie = $this->entityBuilder($row);
				$listCategories[] = $categorie;
			}
		}
		return $listCategories;
	}

	public function addCategorie($post, $user)
	{
		if (empty($user)) {
			trigger_error("Merci de vous connecter pour accéder à ce service.");
			return false;
		}

		// Utilisation d'un objet ArticleForm pour la validation des données.
		// Cette objet implémente l'interface App\Services\FormValidator
		// return bool
		$categorieAddForm = new CategorieForm();
		$isValid = $categorieAddForm->builderFormValidator($post);
		if ($isValid === true)
		{
			
			/*
			 * Use App\Services\Slug
			 * Set Name slugyfication
			 * return string
	 		 * @param string 
			 */
			$slugName = Slug::slugify($post['_sNom']);
			$checkSlug = $this->getDbRequest()->checkField('categorie', 'cat_sSlug', $slugName);
			if ($checkSlug === false) {
				$slugName = Slug::createSlug($post['_sNom']);
			}

			// Create new Categorie
			$categorie = new Categorie();
			$categorie->set_sNom($post['_sNom']);
			$categorie->set_sResume($post['_sResume']);
			$categorie->set_bActif($post['_bActif']);
			$categorie->set_sSlug($slugName);
			$categorie->set_sCodeHexa($post['_sCodeHexa']);

			$params = array(
				':cat_sNom' => $categorie->get_sNom(),
				':cat_sResume' => $categorie->get_sResume(),
				':cat_bActif' => $categorie->get_bActif(),
				':cat_sSlug' => $slugName,
				':cat_sCodeHexa' => $categorie->get_sCodeHexa()
			);

			// create new insert request in database
			$insertNewCategorie = $this->getDbRequest()->insert("
				INSERT INTO categorie (cat_sNom, cat_sResume, cat_bActif, cat_sSlug,cat_sCodeHexa)
				VALUES (:cat_sNom, :cat_sResume, :cat_bActif, :cat_sSlug,:cat_sCodeHexa)
			",$params);
			return true;

		}else{
			return false;
		}

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