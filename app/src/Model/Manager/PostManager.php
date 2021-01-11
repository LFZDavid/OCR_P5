<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\Post;
use PDO;

class PostManager extends Manager
{

    protected string $table = 'posts';
    protected string $classManaged = '\App\Model\Entity\Post';

    /**
     * add/update entity
     * @param Post $post
     */
    public function save(Post $post)
    {
        if ($post->getId() == null) {
            return $this->add($post);
        }
        return $this->update($post);
    }

    /**
     * 
     * @param Post $post
     * @return int $id //Return auto-incremented id for form treatment
     */
    protected function add(Post $post): int
    {
        $request = 'INSERT INTO ' . $this->table . '(`title`, `chapo`, `content`, `id_author`,`active`) VALUES(:title, :chapo, :content, :id_author, :active)';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':title', $post->getTitle());
        $q->bindValue(':chapo', $post->getChapo());
        $q->bindValue(':content', $post->getContent());
        $q->bindValue(':id_author', $post->getIdAuthor(), PDO::PARAM_INT);
        $q->bindValue(':active', $post->getActive(), PDO::PARAM_INT);
        $q->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * 
     * @param Post $post
     * @return int $id //Return auto-incremented id for form treatment
     */
    protected function update(Post $post): int
    {
        $request = 'UPDATE ' . $this->table . ' SET `title` = :title, `chapo` = :chapo, `content` = :content, `id_author` = :id_author, `active` = :active, `updated_at` = NOW() WHERE `id` = :id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':title', $post->getTitle());
        $q->bindValue(':chapo', $post->getChapo());
        $q->bindValue(':content', $post->getContent());
        $q->bindValue(':id_author', $post->getIdAuthor(), PDO::PARAM_INT);
        $q->bindValue(':active', $post->getActive(), PDO::PARAM_INT);
        $q->bindValue(':id', $post->getId(), PDO::PARAM_INT);
        $q->execute();
        return $post->getId();
    }

    /**
     * @param int $id_post
     * @param int $id_category
     */
    public function linkCategory(int $id_post, int $id_category)
    {
        $request = 'INSERT INTO `category_post` (`id_post`, `id_category`) VALUES(:id_post, :id_category)';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':id_post', $id_post, PDO::PARAM_INT);
        $q->bindValue(':id_category', $id_category, PDO::PARAM_INT);
        $q->execute();
    }

    /**
     * @param int $id_post
     * @param int $id_category
     */
    public function unlinkCategory(int $id_post, int $id_category)
    {
        $request = 'DELETE FROM `category_post` WHERE id_post = :id_post AND id_category = :id_category';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':id_post', $id_post, PDO::PARAM_INT);
        $q->bindValue(':id_category', $id_category, PDO::PARAM_INT);
        $q->execute();
    }
}
