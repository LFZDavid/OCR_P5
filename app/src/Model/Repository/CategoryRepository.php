<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CategoryRepository extends Repository
{

    protected string $table = 'categories';
    protected string $classManaged = '\App\Model\Entity\Category';

    /**
     * @param int $id_post
     * @return array
     */
    public function getListByPost(int $id_post): array
    {
        $request = 'SELECT `id_category` as id, categories.name as name 
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
