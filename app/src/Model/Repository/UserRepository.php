<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class UserRepository extends Repository
{
    protected string $table = 'users';
    protected string $classManaged = '\App\Model\Entity\User';

    public function getUniqueByName(string $name)
    {
        $request = 'SELECT * FROM ' . $this->table . ' WHERE name LIKE :name';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':name', $name);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        return $q->fetch();
    }

    public function getUniqueByEmail(string $email)
    {
        $request = 'SELECT * FROM ' . $this->table . ' WHERE email LIKE :email';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':email', $email);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        return $q->fetch();
    }
}
