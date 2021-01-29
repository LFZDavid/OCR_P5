<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\Comment;
use PDO;

class CommentManager extends Manager
{

    protected string $table = 'comments';
    protected string $classManaged = '\App\Model\Entity\Comment';

    public function save(Comment $comment)
    {
        if ($comment->getId() == null) {
            return $this->add($comment);
        }
        return $this->update($comment);
    }

    protected function add(Comment $comment): int
    {
        $request = 'INSERT INTO ' . $this->table . '(`content`, `id_author`, `id_post`) VALUES(:content, :id_author, :id_post)';

        $query = $this->pdo->prepare($request);
        $query->bindValue(':content', $comment->getContent());
        $query->bindValue(':id_author', $comment->getIdAuthor(), PDO::PARAM_INT);
        $query->bindValue(':id_post', $comment->getIdPost(), PDO::PARAM_INT);
        $query->execute();
        return $this->pdo->lastInsertId();
    }

    protected function update(Comment $comment): int
    {
        $request = 'UPDATE ' . $this->table . ' SET `content` = :content, `id_author` = :id_author, `id_post` = :id_post, `active` = :active WHERE `id` = :id';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':content', $comment->getContent());
        $query->bindValue(':id_author', $comment->getIdAuthor(), PDO::PARAM_INT);
        $query->bindValue(':id_post', $comment->getIdPost(), PDO::PARAM_INT);
        $query->bindValue(':active', $comment->getActive(), PDO::PARAM_BOOL);
        $query->bindValue(':id', $comment->getId(), PDO::PARAM_INT);
        $query->execute();
        return $comment->getId();
    }

    public function deleteByPost(int $postID): bool
    {
        $request = 'DELETE FROM ' . $this->table . ' WHERE `id_post` = :id_post';
        $query = $this->pdo->prepare($request);
        $query->bindValue(':id_post', $postID, PDO::PARAM_INT);
        $query->execute();
        return true;
    }
}
