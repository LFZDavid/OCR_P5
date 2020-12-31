<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;

class PostRepository extends Repository
{

	protected $table = 'posts';
	protected $classManaged = '\App\Model\Entity\Post';
	protected $paginate = 'LIMIT 0, 4';
}
