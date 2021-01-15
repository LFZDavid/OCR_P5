<?php

namespace App\Model\Manager;

use PDO;

abstract class Manager
{
    protected PDO $pdo;
    protected string $table;
    protected string $classManaged;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function delete(int $id): bool
    {
        $request = 'DELETE FROM ' . $this->table . ' WHERE id =:id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':id', $id, PDO::PARAM_INT);
        $q->execute();
        return true;
    }
}
