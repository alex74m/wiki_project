<?php

namespace App\Controller;

use \App\Repository\DbRequest;
use \App\Model\User;

use \App\Services\Crypt;
use \App\Services\Token;


class UserController
{
	private $repository;

	public function __construct(DbRequest $dbRequest){
		$this->repository = $dbRequest;
	}

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
				$_SESSION['user']['id'] = $infoUser->{'usr_id'};
				$_SESSION['user']['nom'] = $infoUser->{'usr_sNom'};
				$_SESSION['user']['prenom'] = $infoUser->{'usr_sPrenom'};
				$_SESSION['user']['mail'] = $infoUser->{'usr_sMail'};
				return true;
			}
			else
			{
				trigger_error("Votre mot de passe ou e-mail est incorrect.");
			}
		}else{
			trigger_error("Cette e-mail n'est pas valide.");
		}


	}

	public function userBuilder($row)
	{

	}

}


