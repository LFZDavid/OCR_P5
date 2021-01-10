<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\Post;
use PDO;

class PostManager extends Manager
{

    protected $table = 'posts';
    protected $classManaged = '\App\Model\Entity\Post';


    public function save(Post $post)
    {
        if ($post->id() == null) {
            return $this->add($post);
        }
        return $this->update($post);
    }

    protected function add(Post $post)
    {
        $request = 'INSERT INTO ' . $this->table . '(`title`, `chapo`, `content`, `id_author`) VALUES(:title, :chapo, :content, :id_author)';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':title', (string) $post->title());
        $q->bindValue(':chapo', (string) $post->chapo());
        $q->bindValue(':content', (string) $post->content());
        $q->bindValue(':id_author', (int) $post->id_author(), PDO::PARAM_INT);
        return $q->execute();
    }

    protected function update(Post $post)
    {
        $request = 'UPDATE ' . $this->table . ' SET `title` = :title, `chapo` = :chapo, `content` = :content, `id_author` = :id_author, `updated_at` = NOW() WHERE `id` = :id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':title', (string) $post->title());
        $q->bindValue(':chapo', (string) $post->chapo());
        $q->bindValue(':content', (string) $post->content());
        $q->bindValue(':id_author', (int) $post->id_author(), PDO::PARAM_INT);
        $q->bindValue(':id', (int) $post->id(), PDO::PARAM_INT);
        return $q->execute();
    }
}
