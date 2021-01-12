<?php

namespace App\Model\Manager;

use App\Model\Manager\Manager;
use App\Model\Entity\Comment;
use PDO;

class CommentManager extends Manager
{

    protected string $table = 'comments';
    protected string $classManaged = '\App\Model\Entity\Comment';

    /**
     * add/update entity
     * @param Comment $comment
     */
    public function save(Comment $comment)
    {
        if ($comment->getId() == null) {
            return $this->add($comment);
        } else {
            return $this->update($comment);
        }
    }

    /**
     * Add Comment into database
     *
     * @param Comment $comment
     * @return integer
     */
    protected function add(Comment $comment): int
    {
        $request = 'INSERT INTO ' . $this->table . '(`content`, `id_author`, `id_post`) VALUES(:content, :id_author, :id_post)';

        $q = $this->pdo->prepare($request);
        $q->bindValue(':content', $comment->getContent());
        $q->bindValue(':id_author', $comment->getIdAuthor(), PDO::PARAM_INT);
        $q->bindValue(':id_post', $comment->getIdPost(), PDO::PARAM_INT);
        $q->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * Update Comment
     *
     * @param Comment $comment
     * @return integer
     */
    protected function update(Comment $comment): int
    {
        $request = 'UPDATE ' . $this->table . ' SET `content` = :content, `id_author` = :id_author, `id_post` = :id_post, `active` = :active, `updated_at` = NOW() WHERE `id` = :id';
        $q = $this->pdo->prepare($request);
        $q->bindValue(':content', $comment->getContent());
        $q->bindValue(':id_author', $comment->getIdAuthor(), PDO::PARAM_INT);
        $q->bindValue(':id_post', $comment->getIdPost(), PDO::PARAM_INT);
        $q->bindValue(':active', $comment->getActive(), PDO::PARAM_INT);
        $q->bindValue(':id', $comment->getId(), PDO::PARAM_INT);
        $q->execute();
        return $comment->getId();
    }
}