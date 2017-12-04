<?php

namespace App\Form;

use \App\Services\FormValidator;

class ArticleForm
{
	private $formValidator;
	private $tabFieldValidator = array();

	public function __construct()
	{
		$this->formValidator = new FormValidator();
	}
	final function builderFormValidator($datasForm)
	{

		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sTitre'], array(
			'type' => 'text', 'lengthMin' => 2, 'lengthMax' => 250, 'required' => true
		));
		$this->tabFieldValidator[] = $this->formValidator->validFormType($datasForm['_sContenu'], array(
			'type' => 'text', 'lengthMin' => 10, 'required' => true
		));


		if (in_array(false, $this->tabFieldValidator)) {
			return false;
		}

		return true;
	}

}