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
	
	public function __construct(PDO $db)
	{
		$this->db = $db;
	}
	public function getDb()
	{
		return $this->db;
	}

	/**
	 * Query all entities with uniq SQL REQUEST
	 * return List Object
	 * @param string sqlRequest
	 */
	public function queryAll($sqlRequest){
		
		$req = $this->getDb()->query($sqlRequest);
		$result = $req->fetchAll(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $result;
	}

	/**
	 * Query One Entity with SQL REQUEST and One Field
	 * return uniq Entity
	 * @param string sqlRequest
	 * @param string field
	 * @param PDO::STATEMENT typeData [integer|string|bool|null]
	 * @param PDO::STATEMENT typeFetch [array|object|column]
	 */
	public function queryOneByOneField($sqlRequest, $field, $typeData = null, $typeFetch = null)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->bindValue(':field', $field, $this->getTypeData($typeData));
		$req->execute();
		$result = $req->fetch($this->getTypeFetch($typeFetch));
		$req->closeCursor();
		return $result;
	}

	/**
	 * Query undefined Entities with SQL REQUEST and Multi-Field
	 * return saveral Entities
	 * @param string sqlRequest
	 * @param array params
	 */
	public function queryEntityWithMultiField($sqlRequest, $params = null)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute($params);
		$result = $req->fetchAll(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $result;
	}

	/**
	 * Check if Entities is defined 
	 * return bool
	 * @param string table
	 * @param string champ
	 * @param string field
	 */
	public function checkField($table, $champ, $field):bool{
		$req = $this->getDb()->prepare("
			SELECT $champ FROM $table WHERE $champ=:field
		");
		$req->execute(array(':field'=> $field));
		$datas = $req->rowCount();
		$req->closeCursor();

		if ($datas == 0)
			return true;
		else
			return false;
	}
	/**
	 * ALIAS of queryOneByOneField within integer field rather than other type
	 * Query One Entity with SQL REQUEST and One Field
	 * return uniq Entity
	 * @param string sqlRequest
	 * @param int id
	 * @param PDO::STATEMENT typeData [integer|string|bool|null]
	 * @param PDO::STATEMENT typeFetch [array|object|column]
	 */
	public function findById($sqlRequest, int $id, $typeData = null, $typeFetch = null){
		$req = $this->getDb()->prepare($sqlRequest);
		$req->bindValue(':id', $id, $this->getTypeData($typeData));
		$req->execute();
		$result = $req->fetchAll($this->getTypeFetch($typeFetch));
		$req->closeCursor();
		return $result;
	}

	/**
	 * Insert entity with SQL REQUEST and Multi-Field
	 * return int last insert id in table
	 * @param string sqlRequest
	 * @param array params
	 */
	public function insert($sqlRequest, $params = null)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute($params);
		$lastId = $this->getDb()->lastInsertId();
		return $lastId;
	}
	/**
	 * Query saveral entities with SQL REQUEST including LIKE CLAUSE with keyword
	 * return array Entities
	 * @param string sqlRequest
	 * @param string keyWord
	 */
	public function queryAllBySearch($sqlRequest, $keyWord){
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute(array(':keyWord' => '%'.$keyWord.'%'));
		$datas = $req->fetchAll(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}

	/**
	 * Update entity with SQL REQUEST and Multi-Field
	 * return void
	 * @param string sqlRequest
	 * @param array params
	 */
	public function update($sqlRequest, $params = null)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute($params);
	}

	/**
	 * Delete entity with SQL REQUEST and this id
	 * return void
	 * @param string sqlRequest
	 * @param int id
	 */
	public function delete($sqlRequest, int $id)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
	}

	private function getTypeData($type)
	{
		switch ($type) {
			case 'integer':
				return PDO::PARAM_INT;
				break;
			case 'bool':
				return PDO::PARAM_BOOL;
				break;
			case 'null':
				return PDO::PARAM_NULL;
				break;
			case 'string':
				return PDO::PARAM_STR;
				break;
			default:
				return PDO::PARAM_STR;
				break;
		}
	}
	private function getTypeFetch($type)
	{
		switch ($type) {
			case 'array':
				return PDO::FETCH_ASSOC;
				break;
			case 'object':
				return PDO::FETCH_OBJ;
				break;
			case 'column':
				return PDO::FETCH_COLUMN;
				break;
			default:
				return PDO::FETCH_OBJ;
				break;
		}
	}
}

	/*public function findOneByData($sqlRequest, $data)
	{
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute(array(
			':data' => $data
		));
		$datas = $req->fetch(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $datas;
	}*/

		/*public function updateByOneField($sqlRequest, $field){
		$req = $this->getDb()->prepare($sqlRequest);
		$req->execute(array(':field'=> $field));
	}*/

	/*public function queryJoinEntitiesPerEntity($sqlRequest,$idArticle){
		$categoriesQuery = $this->findById($sqlRequest, $idArticle);

		return $categoriesQuery;
	}*/