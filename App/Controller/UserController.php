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

		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)){
			trigger_error("Cette e-mail n'est pas valide. Format accepté : exemple@monsite.com.");
		}

		$psw = $datas['_sPwd'];
		if (strlen($psw) < 5) {
			trigger_error("Le mot de passe doit faire minimum 5 caractères!");
		}
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

			$userPreLoading = $this->entityBuilder($params);
			
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
				if (!is_object($user) & !empty($user)) {
					trigger_error("L'user doit être de type object.");
				}

				if ($user->get_bActif() != '0') {
					$_SESSION['user']['id'] = $user->get_id();
					$_SESSION['user']['nom'] = $user->get_sNom();
					$_SESSION['user']['prenom'] = $user->get_sPrenom();
					$_SESSION['user']['mail'] = $user->get_sMail();
					$_SESSION['user']['actif'] = $user->get_bActif();
					$_SESSION['user']['role'] = $user->get_bAdmin();
					return true;
				}
				else{
					trigger_error("Votre compte est désactivé!");
				}
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
	 * Get entities User App\Model\User
	 * return array User Objects
	 */
	public function queryAllUser()
	{
		$reqUsers = $this->getDbRequest()->queryAll("
			SELECT usr_id,usr_sNom,usr_sPrenom,usr_sMail,usr_sToken,usr_bActif,usr_bAdmin,usr_sAvatar
			FROM user
		");
		$listUsers = [];
		foreach ($reqUsers as $key => $row) {
			$user = new User();
			$user = $this->entityBuilder($row);
			$listUsers[] = $user;
		}

		return $listUsers;
	}
	/**
	 * Update _bActif of entity User App\Model\User - Inactivation
	 * return void
	 * @param int $idUser The id User
	 */
	public function inactivationUser($idUser)
	{
		
		$checkUser = $this->getDbRequest()->checkField('user', 'usr_id', $idUser);

		if ($checkUser != false) {
			trigger_error("Cette utilisateur n'existe pas.");
		}

		$reqInactivUser = $this->getDbRequest()->updateByOneField("
			UPDATE user SET usr_bActif=0 WHERE usr_id=:field
		", $idUser);
	}

	/**
	 * Update _bActif of entity User App\Model\User - Activation
	 * return void
	 * @param int $idUser The id User
	 */
	public function activationUser($idUser)
	{
		
		$checkUser = $this->getDbRequest()->checkField('user', 'usr_id', $idUser);

		if ($checkUser != false) {
			trigger_error("Cette utilisateur n'existe pas.");
		}

		$reqInactivUser = $this->getDbRequest()->updateByOneField("
			UPDATE user SET usr_bActif=1 WHERE usr_id=:field
		", $idUser);
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
		$user->set_id($row->{'usr_id'}) ?? null;
		$user->set_sNom($row->{'usr_sNom'}) ?? null;
		$user->set_sPrenom($row->{'usr_sPrenom'}) ?? null;
		$user->set_sMail($row->{'usr_sMail'}) ?? null;
		$user->set_sToken($row->{'usr_sToken'}) ?? null;
		$user->set_bActif($row->{'usr_bActif'}) ?? null;
		$user->set_bAdmin($row->{'usr_bAdmin'}) ?? null;
		$user->set_sAvatar($row->{'usr_sAvatar'}) ?? null;
		return $user;
	}

}


