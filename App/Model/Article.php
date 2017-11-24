<?php

namespace App\Model;

use App\Model\User;

class Article {
	// propriétés
	protected $id;
	protected $iAuteurId;
	protected $sTitre;
	protected $sAuteur;
	protected $sContenu;
	protected $dDateAjout;
	protected $dDateLastModif;
	protected $bActif;
	protected $sSlug;
	protected $aCategories;

	public function __construct(/*array $aValeurs*/){
		//$this->hydrate($aValeurs);
	}

	//hydratation
	/*public function hydrate(array $aValeurs){
		if(!empty($aValeurs)){
			foreach ($aValeurs as $key => $valeur){
				$methode = 'set_'.$key;
				if(method_exists($this, $methode)){
					$this->{$methode}($valeur);
				}
			}
		}
	}
	*/
	
	//setters
	public function set_Id($id){
		$id = (int) $id;
		$this->id = $id;
	}
	public function set_iAuteurId(User $idAuteur){
	 $this->iAuteurId = $idAuteur;
	}
	
	public function set_sTitre($sTitre){
	 $this->sTitre = $sTitre;
	}
	
	public function set_sContenu($sContenu){
	 $this->sContenu = $sContenu;
	}
	
	public function set_dDateAjout($dDateAjout){
	 $this->dDateAjout = $dDateAjout;
	}
	
	public function set_dDateLastModif($dDateLastModif){
	 $this->dDateLastModif = $dDateLastModif;
	}
	
	public function set_bActif($bActif){
	 $this->bActif = $bActif;
	}
	
	public function set_sSlug($sSlug){
	 $this->sSlug = $sSlug;
	}
	
	public function set_aCategories($aCategories){
	 $this->aCategories = $aCategories;
	}
	

	//getters
	public function get_Id(){
		return $this->id;
	}
	public function get_iAuteurId(){
		return $this->iAuteurId;
	}
	
	public function get_sTitre(){
		return $this->sTitre;
	}
	
	public function get_sContenu(){
		return $this->sContenu;
	}
	
	public function get_dDateAjout(){
		return $this->dDateAjout;
	}
	
	public function get_dDateLastModif(){
		return $this->dDateLastModif;
	}
	
	public function get_bActif(){
		return $this->bActif;
	}
	public function get_sSlug(){
		return $this->sSlug;
	}
	
	public function get_aCategories(){
		return $this->aCategories;
	}
	
	

}
