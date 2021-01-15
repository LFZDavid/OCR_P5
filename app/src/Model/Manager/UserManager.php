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
        $q = $this->pdo->prepare($request);
        $q->bindValue(':name', $user->getName());
        $q->bindValue(':email', $user->getEmail());
        $q->bindValue(':pwd', $user->getPwd());
        $q->bindValue(':role', $user->getRole());
        $q->execute();
        return $this->pdo->lastInsertId();
    }

    protected function update(User $user): int
    {
        $request = 'UPDATE ' . $this->table . ' SET `name` = :name, `email` = :email, `pwd` = :pwd, `role` = :role WHERE `id` = :id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':name', $user->getName());
        $q->bindValue(':email', $user->getEmail());
        $q->bindValue(':pwd', $user->getPwd());
        $q->bindValue(':role', $user->getRole());
        $q->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $q->execute();
        return $user->getId();
    }
}
