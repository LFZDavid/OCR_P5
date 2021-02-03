<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CategoryRepository extends Repository
{

    protected string $table = 'categories';
    protected string $classManaged = '\App\Model\Entity\Category';

    public function getListByPost(int $postId): array
    {
        $request = 'SELECT `id_category` as id, categories.name as name 
        FROM `category_post`
        INNER JOIN `categories` ON categories.id = `id_category` 
        WHERE `id_post` = :id_post ';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':id_post', $postId, PDO::PARAM_INT);
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        $query->execute();

        return $query->fetchAll();
    }
}
