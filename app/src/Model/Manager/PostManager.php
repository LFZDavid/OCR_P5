<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use PDO;

class PostManager extends Manager
{

    protected $table = 'posts';
    protected $classManaged = '\App\Model\Entity\Post';
}
