<?php

namespace App\Model;

class Categorie
{
	private $id;
	private $sNom;
	private $sResume;
	private $bActif;
	private $sSlug;
	private $sCodeHexa;

	public function get_id(){return $this->id;}
	public function get_sNom(){return $this->sNom;}
	public function get_sResume(){return $this->sResume;}
	public function get_bActif(){return $this->bActif;}
	public function get_sSlug(){return $this->sSlug;}
	public function get_sCodeHexa(){return $this->sCodeHexa;}


	public function set_id($id)
	{
		$id = (int) $id;
		$this->id = $id;
	}
	public function set_sNom($sNom)
	{
		$this->sNom = $sNom;
	}
	public function set_sResume($sResume)
	{
		$this->sResume = $sResume;
	}
	public function set_bActif($bActif)
	{
		$this->bActif = $bActif;
	}
	public function set_sSlug($sSlug)
	{
		$this->sSlug = $sSlug;
	}
	public function set_sCodeHexa($sCodeHexa)
	{
		$this->sCodeHexa = $sCodeHexa;
	}
}