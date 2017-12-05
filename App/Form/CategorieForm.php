<?php

namespace App\Form;

use \App\Services\FormValidator;

class CategorieForm
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
			'type' => 'text', 'lengthMin' => 2, 'lengthMax' => 50, 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sResume'], array(
			'type' => 'text', 'lengthMin' => 5, 'lengthMax' => 200, 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sCodeHexa'], array(
			'type' => 'color', 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_bActif'], array(
			'type' => 'text', 'required' => true
		));


		if (in_array(false, $this->tabFieldValidator)) {
			return false;
		}

		return true;
	}

}