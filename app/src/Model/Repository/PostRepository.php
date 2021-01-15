<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\CommentRepository;
use PDO;

class PostRepository extends Repository
{

	protected string $table = 'posts';
	protected string $classManaged = '\App\Model\Entity\Post';
	protected string $paginate = 'LIMIT 0, 4';
	protected CategoryRepository $categoryRepository;
	protected CommentRepository $CommentRepository;


	public function __construct(
		PDO $pdo,
		CategoryRepository $categoryRepository,
		CommentRepository $commentRepository
	) {
		$this->categoryRepository = $categoryRepository;
		$this->CommentRepository = $commentRepository;
		parent::__construct($pdo);
	}

	/**
	 * overrided herited method for post
	 * Get list of Post adding categories (Collection)
	 * @return array $post_list
	 */
	public function getList(): array
	{
		$q = $this->pdo->query('SELECT * FROM ' . $this->table);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);



		$list = $q->fetchAll();
		foreach ($list as $post) {
			/**add categories */
			$post_categories = $this->categoryRepository->getListByPost($post->getId());
			$post->setCategories($post_categories);

			/**add comments */
			$post_comments = $this->CommentRepository->getListByPostId($post->getId());
			$post->setComments($post_comments);

			$post_list[] = $post;
		}
		return $post_list;
	}

	/**
	 * overrided herited method for post
	 * Get Post adding categories (Collection)
	 * @return Post $post
	 */
	public function getUniqueById(int $id): object
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE id =:id';
		$q = $this->pdo->prepare($request);
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		$post = $q->fetch();

		/**add categories */
		$post_categories = $this->categoryRepository->getListByPost($post->getId());
		$post->setCategories($post_categories);

		/**add comments */
		$post_comments = $this->CommentRepository->getListByPostId($post->getId());
		$post->setComments($post_comments);

		return $post;
	}
}
