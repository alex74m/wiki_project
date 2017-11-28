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
	//private $entityName;
	
	public function __construct(PDO $db)
	{
		$this->db = $db;
	}
	public function getDb()
	{
		return $this->db;
	}

	public function queryAll($sqlRequest){
		
		$req = $this->getDb()->query($sqlRequest);
		$datas = $req->fetchAll(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}

	public function findById($sqlRequest, $id)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$datas = $req->fetchAll(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}


	public function findOne($sqlRequest, $field)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->bindValue(':field', $field, PDO::PARAM_INT);
		$req->execute();
		$datas = $req->fetch(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}

	public function findOneBySlug($sqlRequest, $slug)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->bindValue(':slug', $slug, PDO::PARAM_INT);
		$req->execute();
		$datas = $req->fetch(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}

	public function checkField($table, $champ, $field){
		$req = $this->getDb()->prepare("
			SELECT $champ FROM $table WHERE $champ=:field
		");
		//$req->bindValue(':field', $field, PDO::PARAM_INT);
		$req->execute(array(':field', $field));
		$datas = $req->rowCount();
		$req->closeCursor();

		if ($datas == 0)
			return true;
		else
			return false;
	}

	public function insert($sqlRequest, $params = null)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute($params);
		$lastId = $this->getDb()->lastInsertId();
		return $lastId;
	}

	public function queryAllBySearch($sqlRequest, $keyWord){
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute(array(':keyWord', '%'.$keyWord.'%'));
		$datas = $req->fetchAll(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}
}
