<?php

namespace App\Repository;

use \PDO;
use \App\Model\Article;

/**
* @
*/
class DbRequest
{
	private $db;
	private $entityName;
	
	public function __construct(PDO $db)
	{
		$this->db = $db;
	}
	public function getDb()
	{
		return $this->db;
	}

	public static function setEntityName($entityName)
	{
		$this->entityName = ucfirst(substr(strrchr(strtolower(($entityName)), "\\"), 1));
		return $this->entityName;
	}

	public function queryAll($sqlRequest){
		
		$req = $this->getDb()->query($sqlRequest);
		$datas = $req->fetchAll(PDO::FETCH_COLUMN, 0);
		var_dump($datas);
		return $datas;
	}
}
