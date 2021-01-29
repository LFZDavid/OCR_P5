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
		$query = $this->pdo->query('SELECT * FROM ' . $this->table);
		$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		return $query->fetchAll();
	}

	public function getListOfActives(): array
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE active = 1';
		$query = $this->pdo->query($request);
		$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		return $query->fetchAll();
	}

	public function getUniqueById(int $id): object
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE id =:id';
		$query = $this->pdo->prepare($request);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
		return $query->fetch();
	}
}
