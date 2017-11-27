<?php
namespace App\Services;


class Crypt
{
	public static function crypt($pass)
	{
		$hashPass = password_hash($pass, PASSWORD_DEFAULT);
		return $hashPass;
	}

	public static function decrypt($hash,$pass)
	{
		if (password_verify($pass, $hash))
		    return true;
		else
			return false;
	}
}