<?php

namespace App\Services;

class Token
{
	private static $token;

	public static function createToken()
	{
		self::$token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)).uniqid();
		return self::$token;
	}

}