<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CategoryRepository extends Repository
{

    protected $table = 'categories';
    protected $classManaged = '\App\Model\Entity\Category';

    public function getListByPost($id_post)
    {
        $request = 'SELECT `id_category`, categories.name as name 
        FROM `category_post`
        INNER JOIN `categories` ON categories.id = `id_category` 
        WHERE `id_post` = :id_post ';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':id_post', $id_post, PDO::PARAM_INT);
        $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        $q->execute();

        return $q->fetchAll();
    }
}
