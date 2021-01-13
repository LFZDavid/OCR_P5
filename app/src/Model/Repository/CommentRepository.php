<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CommentRepository extends Repository
{
    protected string $table = 'comments';
    protected string $classManaged = '\App\Model\Entity\Comment';

    public function getCompleteList(bool $only_active = false)
    {
        $where = $only_active ? ' WHERE `comments.active` = 1' : '';
        $request = 'SELECT 
        ' . $this->table . '.id,
        comments.content,
        comments.id_author,
        users.name as author_name,
        comments.id_post,
        posts.title as post_title,
        comments.active,
        comments.created_at
        FROM ' . $this->table . '
        INNER JOIN users ON users.id = comments.id_author
        INNER JOIN posts ON posts.id = comments.id_post' . $where .
            ' ORDER BY comments.id
        DESC';
        $q = $this->pdo->query($request);
        return $q->fetchAll();
    }
}
