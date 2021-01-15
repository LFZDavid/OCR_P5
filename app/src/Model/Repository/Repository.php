<?php

namespace App\Model\Repository;

use PDO;

abstract class Repository
{
	protected PDO $pdo;
	protected string $table;
	protected string $classManaged;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function getList(): array
	{
		$q = $this->pdo->query('SELECT * FROM ' . $this->table);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		return $q->fetchAll();
	}

	public function getListOfActives(): array
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE active = 1';
		$q = $this->pdo->query($request);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		return $q->fetchAll();
	}

	public function getUniqueById(int $id): object
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE id =:id';
		$q = $this->pdo->prepare($request);
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
		return $q->fetch();
	}
}
