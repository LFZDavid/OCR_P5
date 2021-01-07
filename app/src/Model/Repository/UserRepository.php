<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class UserRepository extends Repository
{
    protected $table = 'users';
    protected $classManaged = '\App\Model\Entity\User';
}
