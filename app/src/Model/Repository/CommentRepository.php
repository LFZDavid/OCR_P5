<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CommentRepository extends Repository
{
    protected string $table = 'comments';
    protected string $classManaged = '\App\Model\Entity\Comment';
}
