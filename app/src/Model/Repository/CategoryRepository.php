<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;

class CategoryRepository extends Repository
{

    protected $table = 'categories';
    protected $classManaged = '\App\Model\Entity\Category';
}
