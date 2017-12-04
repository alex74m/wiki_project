<?php

namespace App\Repository\Database;

use App\Config\ParamConfig\Config;

use \PDO;

/**
 * @PDO [object]
 * return PDO instance
 */
class Database
{
	private static $db;
	private const HOST = 'host';
	private const DBNAME = 'wiki';
	private const ID = 'root';
	private const PASS = '';

	private function __construct()
	{
		self::$db = new PDO("mysql:localhost=".self::HOST.";dbname=".self::DBNAME."","".self::ID."","".self::PASS."",[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	}

	public static function getInstance()
	{
		if (empty(self::$db)) {
			new self;
		}
		return self::$db;
	}
}

