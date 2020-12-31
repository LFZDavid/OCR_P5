<?php

namespace App\Model\Repository;

use PDO;

abstract class Repository
{
	protected $pdo;
	protected $table;
	protected $classManaged;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function getList()
	{
		$q = $this->pdo->query('SELECT * FROM ' . $this->table);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		return $q->fetchAll();
	}

	public function getListOfActives()
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE active = 1';
		$q = $this->pdo->query($request);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		return $q->fetchAll();
	}

	public function getUniqueById($id)
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE id =:id';
		$q = $this->pdo->prepare($request);
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
		return $q->fetch();
	}
}
