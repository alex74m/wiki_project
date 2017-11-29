<?php

namespace App\Controller;

use \App\Controller\Interfaces\InterfaceController;

use \App\Repository\DbRequest;
use \App\Model\User;

use \App\Services\Crypt;
use \App\Services\Token;


class UserController implements InterfaceController
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

	public function addUserInscription($datas)
	{
		$mail = $datas['_sMail'];
		$nom = $datas['_sNom'];
		$prenom = $datas['_sPrenom'];
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
		{
			trigger_error("Cette e-mail n'est pas valide. Format accepté : exemple@monsite.com.");
		}
		$psw = $datas['_sPwd'];
		$hashPsw = Crypt::crypt($psw);
		$userToken = Token::createToken();

		$checkMail = $this->getDbRequest()->checkField('user', 'usr_sMail', $mail);
		if ($checkMail == true)
		{
			$params = array(
				':usr_sNom' => $nom,
				':usr_sPrenom' => $prenom,
				':usr_sMail' => $mail,
				':usr_sPwd' => $hashPsw,
				':usr_sToken' => $userToken
			);

			
			$insertNewUser = $this->getDbRequest()->insert("
				INSERT INTO user (usr_sNom, usr_sPrenom, usr_sMail,usr_sPwd, usr_sToken)
				VALUES (:usr_sNom, :usr_sPrenom, :usr_sMail, :usr_sPwd, :usr_sToken)
			",$params);

		}else{
			trigger_error("Cette e-mail n'est pas disponible.");			
		}

		if (is_numeric($insertNewUser))
			return true;
		else
			return false;

	}

	public function connexionUser($datas)
	{
	
		$mail = $datas['_sMail'];
		$checkMail = $this->getDbRequest()->checkField('user', 'usr_sMail', $mail);
		if ($checkMail == false ) {
			$infoUser = $this->getDbRequest()->findOne("SELECT * FROM user WHERE usr_sMail=:field", $mail);
			$psw = $datas['_sPwd'];

			$pswHash = $infoUser->{'usr_sPwd'};
			$checkPsw = Crypt::decrypt($pswHash, $psw);
	
			if ($checkPsw ==  true) {
				$user = $this->entityBuilder($infoUser);
				return $user;
			}
			else
			{
				trigger_error("Votre mot de passe ou e-mail est incorrect.");
			}
		}else{
			trigger_error("Cette e-mail n'est pas valide.");
		}
	}

	/**
	 * use Interface InterfaceController
	 * Set entities User App\Model\User
	 * return array Objects
	 * @param string $row 
	 */
	public function entityBuilder($row)
	{
		$user = new User();
		$user->set_id($row->{'usr_id'});
		$user->set_sNom($row->{'usr_sNom'});
		$user->set_sPrenom($row->{'usr_sPrenom'});
		$user->set_sMail($row->{'usr_sMail'});
		$user->set_sPwd($row->{'usr_sPwd'});
		$user->set_sToken($row->{'usr_sToken'});
		$user->set_bActif($row->{'usr_bActif'});
		$user->set_bAdmin($row->{'usr_bAdmin'});
		$user->set_sAvatar($row->{'usr_sAvatar'});
		return $user;
	}

}


