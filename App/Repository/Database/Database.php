<?php

namespace App\Repository\Database;

use \PDO;

/**
 * @PDO [object]
 * return PDO instance
 */
class Database
{
	private static $db;

	private function __construct()
	{
		self::$db = new PDO('mysql:localhost=host;dbname=wiki','root','',[PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
	}

	public static function getInstance()
	{
		if (empty(self::$db)) {
			new self;
		}
		return self::$db;
	}
}

