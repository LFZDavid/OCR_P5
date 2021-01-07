<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\User;
use PDO;

class UserManager extends Manager
{
    protected $table = 'users';
    protected $classManaged = '\App\Model\Entity\User';

    /**
     * add/update entity
     * @param User $user
     */
    public function save(User $user)
    {
        if ($user->id() == null) {
            return $this->add($user);
        }
        return $this->update($user);
    }

    /**
     * 
     * @param User $user
     * @return int $id //Return auto-incremented id for form treatment
     */
    protected function add(User $user)
    {
        $request = 'INSERT INTO ' . $this->table . '(`name`, `email`, `pwd`, `role`,`active`) VALUES(:name, :email, :pwd, :role, :active)';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':name', (string) $user->name());
        $q->bindValue(':email', (string) $user->email());
        $q->bindValue(':pwd', (string) $user->pwd());
        $q->bindValue(':role', (int) $user->role());
        $q->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * 
     * @param User $user
     * @return int $id //Return auto-incremented id for form treatment
     */
    protected function update(User $user)
    {
        $request = 'UPDATE ' . $this->table . ' SET `name` = :name, `email` = :email, `pwd` = :pwd, `role` = :role WHERE `id` = :id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':name', (string) $user->name());
        $q->bindValue(':email', (string) $user->email());
        $q->bindValue(':pwd', (string) $user->pwd());
        $q->bindValue(':role', (int) $user->role());
        $q->bindValue(':id', (int) $user->id(), PDO::PARAM_INT);
        $q->execute();
        return $user->id();
    }
}
