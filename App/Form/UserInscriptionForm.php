<?php

namespace App\Form;

use \App\Services\FormValidator;

class UserInscriptionForm
{
	private $formValidator;
	private $tabFieldValidator = array();

	public function __construct()
	{
		$this->formValidator = new FormValidator();
	}
	final function builderFormValidator($datasForm)
	{

		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sNom'], array(
			'type' => 'text', 'lengthMin' => 2, 'lengthMax' => 250, 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sPrenom'], array(
			'type' => 'text', 'lengthMin' => 2, 'lengthMax' => 250, 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sMail'], array(
			'type' => 'mail', 'lengthMin' => 5, 'lengthMax' => 250, 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sPwd'], array(
			'type' => 'password', 'lengthMin' => 5, 'lengthMax' => 250, 'required' => true
		));

		if (in_array(false, $this->tabFieldValidator)) {
			return false;
		}

		return true;
	}

}