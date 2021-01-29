<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CommentRepository extends Repository
{
    protected string $table = 'comments';
    protected string $classManaged = '\App\Model\Entity\Comment';

    public function getCompleteList(bool $onlyActive = false, ?string $limit = null): array
    {
        $where = $onlyActive ? ' WHERE comments.active = 1' : '';
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

        if ($limit != null) {
            $request .= ' LIMIT ' . $limit;
        }

        $query = $this->pdo->query($request);
        return $query->fetchAll();
    }

    public function getListByPost(int $postId, bool $onlyActive = false): array
    {
        $where = ' WHERE `id_post` = ' . $postId . ' ';
        $where .= $onlyActive ? 'AND comments.active = 1' : '';
        $request = 'SELECT comments.id, content, id_author id_post, active, comments.created_at, users.name as author_name FROM ' . $this->table . ' INNER JOIN users ON users.id = id_author ' . $where;
        $query = $this->pdo->query($request);
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
        return $query->fetchAll();
    }
}
