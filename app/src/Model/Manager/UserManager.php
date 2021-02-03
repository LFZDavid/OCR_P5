<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\User;
use PDO;

class UserManager extends Manager
{
    protected string $table = 'users';
    protected string $classManaged = '\App\Model\Entity\User';

    public function save(User $user): int
    {
        if ($user->getId() == null) {
            return $this->add($user);
        }
        return $this->update($user);
    }

    protected function add(User $user): int
    {
        $request = 'INSERT INTO ' . $this->table . '(`name`, `email`, `pwd`, `role`) VALUES(:name, :email, :pwd, :role)';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':name', $user->getName());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':pwd', $user->getPwd());
        $query->bindValue(':role', $user->getRole());
        $query->execute();
        return $this->pdo->lastInsertId();
    }

    protected function update(User $user): int
    {
        $request = 'UPDATE ' . $this->table . ' SET `name` = :name, `email` = :email, `pwd` = :pwd, `role` = :role WHERE `id` = :id';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':name', $user->getName());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':pwd', $user->getPwd());
        $query->bindValue(':role', $user->getRole());
        $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $query->execute();
        return $user->getId();
    }
}
