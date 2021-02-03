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
        $query = $this->pdo->prepare($request);
        $query->bindValue(':name', $name);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        return $query->fetch();
    }

    public function getUniqueByEmail(string $email)
    {
        $request = 'SELECT * FROM ' . $this->table . ' WHERE email LIKE :email';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':email', $email);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        return $query->fetch();
    }

    public function getUniqueByPost(int $postId)
    {
        $request = 'SELECT users.* FROM `users` INNER JOIN `posts` ON posts.id = :postId WHERE users.id = posts.id_author';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':postId', $postId, PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        return $query->fetch();
    }
}
