<?php

namespace App\Model;

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
	public function hydrate(array $aValeurs){
		if(!empty($aValeurs)){
			foreach ($aValeurs as $key => $valeur){
				$methode = 'set_'.$key;
				if(method_exists($this, $methode)){
					$this->{$methode}($valeur);
				}
			}
		}
	}
	
	//setters
	public function set_Id(int $id){
		$this->id = $id;
	}
	public function set_iAuteurId(int $idAuteur){
	 $this->iAuteurId = $idAuteur;
	}
	
	public function set_sTitre(string $sTitre){
	 $this->sTitre = $sTitre;
	}
	
	public function set_sContenu(?string $sContenu){ // le ? uniquement en 7.1 php, veut dire nul ou string dans ce cas
	 $this->sContenu = $sContenu;
	}
	
	public function set_dDateAjout(DateTime $dDateAjout){
	 $this->dDateAjout = $dDateAjout;
	}
	
	public function set_dDateLastModif(DateTime $dDateLastModif){
	 $this->dDateLastModif = $dDateLastModif;
	}
	
	public function set_bActif(bool $bActif){
	 $this->bActif = $bActif;
	}
	
	public function set_sSlug(bool $sSlug){
	 $this->sSlug = $sSlug;
	}
	
	public function set_aCategories(array $aCategories){
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
	
	public function get_aCategories(): array{
		return $this->aCategories;
	}
	
	

}
