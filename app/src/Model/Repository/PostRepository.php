<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use App\Model\Repository\CategoryRepository;
use PDO;

class PostRepository extends Repository
{

	protected $table = 'posts';
	protected $classManaged = '\App\Model\Entity\Post';
	protected $paginate = 'LIMIT 0, 4';
	protected $CategoryRepository;


	/**
	 * overrided herited method for post
	 * Get list of Post adding categories (Collection)
	 * @return array $post_list
	 */
	public function getList()
	{
		$q = $this->pdo->query('SELECT * FROM ' . $this->table);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		$this->CategoryRepository = new CategoryRepository($this->pdo);
		$list = $q->fetchAll();
		foreach ($list as $post) {
			$post_categories = $this->CategoryRepository->getListByPost($post->getId());
			$post->setCategories($post_categories);
			$post_list[] = $post;
		}
		return $post_list;
	}

	/**
	 * overrided herited method for post
	 * Get Post adding categories (Collection)
	 * @return Post $post
	 */
	public function getUniqueById($id)
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE id =:id';
		$q = $this->pdo->prepare($request);
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);
		$post = $q->fetch();
		$this->CategoryRepository = new CategoryRepository($this->pdo);
		$post_categories = $this->CategoryRepository->getListByPost($post->getId());
		$post->setCategories($post_categories);
		return $post;
	}
}
