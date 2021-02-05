<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\Post;
use PDO;

class PostManager extends Manager
{
    protected string $table = 'posts';
    protected string $classManaged = '\App\Model\Entity\Post';

    public function save(Post $post)
    {
        if ($post->getId() == null) {
            return $this->add($post);
        }
        return $this->update($post);
    }

    protected function add(Post $post): int
    {
        $request = 'INSERT INTO ' . $this->table . '(`title`, `chapo`, `content`, `id_author`,`active`) VALUES(:title, :chapo, :content, :id_author, :active)';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':title', $post->getTitle());
        $query->bindValue(':chapo', $post->getChapo());
        $query->bindValue(':content', $post->getContent());
        $query->bindValue(':id_author', $post->getAuthor()->getId(), PDO::PARAM_INT);
        $query->bindValue(':active', $post->getActive(), PDO::PARAM_INT);
        $query->execute();
        return $this->pdo->lastInsertId();
    }

    protected function update(Post $post): int
    {
        $request = 'UPDATE ' . $this->table . ' SET `title` = :title, `chapo` = :chapo, `content` = :content, `id_author` = :id_author, `active` = :active, `updated_at` = NOW() WHERE `id` = :id';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':title', $post->getTitle());
        $query->bindValue(':chapo', $post->getChapo());
        $query->bindValue(':content', $post->getContent());
        $query->bindValue(':id_author', $post->getAuthor()->getId(), PDO::PARAM_INT);
        $query->bindValue(':active', $post->getActive(), PDO::PARAM_INT);
        $query->bindValue(':id', $post->getId(), PDO::PARAM_INT);
        $query->execute();
        return $post->getId();
    }

    public function linkCategory(int $postId, int $categoryId): void
    {
        $request = 'INSERT INTO `category_post` (`id_post`, `id_category`) VALUES(:id_post, :id_category)';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':id_post', $postId, PDO::PARAM_INT);
        $query->bindValue(':id_category', $categoryId, PDO::PARAM_INT);
        $query->execute();
    }

    public function unlinkCategory(int $postId, int $categoryId): void
    {
        $request = 'DELETE FROM `category_post` WHERE id_post = :id_post AND id_category = :id_category';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':id_post', $postId, PDO::PARAM_INT);
        $query->bindValue(':id_category', $categoryId, PDO::PARAM_INT);
        $query->execute();
    }
}
