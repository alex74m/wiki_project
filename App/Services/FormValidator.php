<?php

namespace App\Services;

class FormValidator
{
	private $validField = array();

	public function validFormType($field, $verification = array())
	{
		$this->validField = $this->getValidation($field, $verification);
		
		if (in_array(false, $this->validField)) {
			return false;
		}

		return true;
	}

	public function getValidation($field, $verification = array())
	{
		$validIsTrue = array();
		foreach ($verification as $key => $control) {
			switch ($key) {
				case 'type':
						$validIsTrue[] = $this->validType($field, $control);
					break;
				case 'lengthMin':
						$validIsTrue[] = $this->validLengthMin($field, $control);
					break;
				case 'lengthMax':
						$validIsTrue[] = $this->validLengthMax($field, $control);
					break;
				case 'required':
						$validIsTrue[] = $this->validRequired($field, $control);
					break;
				default:
						$validIsTrue[] = true;
					break;
			}
		}
		return $validIsTrue;
	}

	public function validRequired($field, $control)
	{	
		if ($field == null & $control == true) {
			return false;
		}
		return true;
	}
	public function validLengthMin($field, $min)
	{	
		$lengthField = strlen($field);
		if ($lengthField < $min) {
			trigger_error("Ce champs doit faire minimum $min caractères.");
			return false;
		}
		return true;
	}
	public function validLengthMax($field, $max)
	{
		$lengthField = strlen($field);
		if ($lengthField > $max) {
			trigger_error("Ce champs doit faire maximum $max caractères.");
			return false;
		}
		return true;
	}
	public function validType($field, $control)
	{
		if ($control == 'mail') {
			if(filter_var($field, FILTER_VALIDATE_EMAIL)){
				return true;
			}else{
				//throw new \Exception("Cette e-mail n'est pas valide. Format accepté : exemple@monsite.com.", 1);
				trigger_error("Cette e-mail n'est pas valide. Format accepté : exemple@monsite.com.");
				return false;
			}
		}
		elseif ($control == 'url') {
			if(filter_var($field, FILTER_VALIDATE_URL)){
				return true;
			}else{
				//throw new \Exception("Cette e-url n'est pas valide.", 1);
				trigger_error("Cette e-url n'est pas valide.");
				return false;
			}
		}
		elseif ($control == 'text') {
			return true;
		}
		elseif ($control == 'password') {
			if(!preg_match("/(([a-z][0-9])|([0-9][a-z])|[A-Z][0-9]|([0-9][A-Z]))/",$field)){
				//throw new \Exception("Le mot de passe doit comporter des lettres et des chiffres.", 1);
				trigger_error("Le mot de passe doit comporter des lettres et des chiffres.");
				return false;
			}
			else{
				return true;
			}
		}
		else {
			//throw new \Exception("Ce type de champ n'est pas valide : $control", 1);
			trigger_error("Ce type de champ n'est pas valide.");
			return false;
		}
	}

}