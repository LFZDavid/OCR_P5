<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use PDO;

class CommentRepository extends Repository
{
    protected string $table = 'comments';
    protected string $classManaged = '\App\Model\Entity\Comment';

    /**
     * Get list of comment with author name and post title
     *
     * @param boolean $only_active
     * @return array
     */
    public function getCompleteList(bool $only_active = false): array
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

    /**
     * Get Post comments
     *
     * @param integer $post_id
     * @param boolean $only_active
     * @return array
     */
    public function getListByPostId(int $post_id, bool $only_active = false): array
    {
        $where = ' WHERE `id_post` = ' . $post_id;
        $where .= $only_active ? 'AND  `comments.active` = 1' : '';
        $request = 'SELECT * FROM ' . $this->table . $where;
        $q = $this->pdo->query($request);
        $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

        return $q->fetchAll();
    }
}
