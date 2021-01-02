<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\Post;
use PDO;

class PostManager extends Manager
{

    protected $table = 'posts';
    protected $classManaged = '\App\Model\Entity\Post';

    /**
     * add/update entity
     * @param Post $post
     */
    public function save(Post $post)
    {
        if ($post->id() == null) {
            return $this->add($post);
        }
        return $this->update($post);
    }

    /**
     * 
     * @param Post $post
     * @return int $id //Return auto-incremented id for form treatment
     */
    protected function add(Post $post)
    {
        $request = 'INSERT INTO ' . $this->table . '(`title`, `chapo`, `content`, `id_author`,`active`) VALUES(:title, :chapo, :content, :id_author, :active)';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':title', (string) $post->title());
        $q->bindValue(':chapo', (string) $post->chapo());
        $q->bindValue(':content', (string) $post->content());
        $q->bindValue(':id_author', (int) $post->id_author(), PDO::PARAM_INT);
        $q->bindValue(':active', (int) $post->active(), PDO::PARAM_INT);
        $q->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * 
     * @param Post $post
     * @return int $id //Return auto-incremented id for form treatment
     */
    protected function update(Post $post)
    {
        $request = 'UPDATE ' . $this->table . ' SET `title` = :title, `chapo` = :chapo, `content` = :content, `id_author` = :id_author, `active` = :active, `updated_at` = NOW() WHERE `id` = :id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':title', (string) $post->title());
        $q->bindValue(':chapo', (string) $post->chapo());
        $q->bindValue(':content', (string) $post->content());
        $q->bindValue(':id_author', (int) $post->id_author(), PDO::PARAM_INT);
        $q->bindValue(':active', (int) $post->active(), PDO::PARAM_INT);
        $q->bindValue(':id', (int) $post->id(), PDO::PARAM_INT);
        $q->execute();
        return $post->id();
    }

    /**
     * @param int $id_post
     * @param int $id_category
     */
    public function linkCategory($id_post, $id_category)
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
    public function unlinkCategory($id_post, $id_category)
    {
        $request = 'DELETE FROM `category_post` WHERE id_post = :id_post AND id_category = :id_category';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':id_post', $id_post, PDO::PARAM_INT);
        $q->bindValue(':id_category', $id_category, PDO::PARAM_INT);
        $q->execute();
    }
}
