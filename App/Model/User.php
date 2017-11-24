<?php

namespace App\Model;

class User
{
	private $id;
	private $sNom;
	private $sPrenom;
	private $sMail;
	private $sPwd;
	private $sToken;
	private $bActif;
	private $bAdmin;
	private $sAvatar;


	public function get_id(){return $this->id;}
	public function get_sNom(){return $this->sNom;}
	public function get_sPrenom(){return $this->sPrenom;}
	public function get_sMail(){return $this->sMail;}
	public function get_sPwd(){return $this->sPwd;}
	public function get_sToken(){return $this->sToken;}
	public function get_bActif(){return $this->bActif;}
	public function get_bAdmin(){return $this->bAdmin;}
	public function get_sAvatar(){return $this->sAvatar;}	


	public function set_id($id){
		$this->id = $id;
	}
	public function set_sNom($sNom){
		$this->sNom = $sNom;
	}
	public function set_sPrenom($sPrenom){
		$this->sPrenom = $sPrenom;
	}
	public function set_sMail($sMail){
		$this->sMail = $sMail;
	}
	public function set_sPwd($sPwd){
		$this->sPwd = $sPwd;
	}
	public function set_sToken($sToken){
		$this->sToken = $sToken;
	}
	public function set_bActif($bActif){
		$this->bActif = $bActif;
	}
	public function set_bAdmin($bAdmin){
		$this->bAdmin = $bAdmin;
	}
	public function set_sAvatar($sAvatar){
		$this->sAvatar = $sAvatar;
	}


}