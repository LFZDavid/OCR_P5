<?php

namespace App\Model\Manager;

use PDO;

abstract class Manager
{
    protected PDO $pdo;
    protected string $table;
    protected string $classManaged;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $request = 'DELETE FROM ' . $this->table . ' WHERE id =:id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':id', $id, PDO::PARAM_INT);
        $q->execute();
        return true;
    }
}
