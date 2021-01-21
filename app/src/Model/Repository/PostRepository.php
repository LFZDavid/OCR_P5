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
	protected UserRepository $userRepository;


	public function __construct(
		PDO $pdo,
		CategoryRepository $categoryRepository,
		UserRepository $userRepository
	) {
		$this->categoryRepository = $categoryRepository;
		$this->userRepository = $userRepository;
		parent::__construct($pdo);
	}

	public function getList(?string $limit = null): array
	{
		$request = 'SELECT * FROM ' . $this->table . ' ORDER BY `id` DESC';
		if ($limit != null) {
			$request .= ' LIMIT ' . $limit;
		}

		$q = $this->pdo->query($request);
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		$list = $q->fetchAll();
		foreach ($list as $post) {
			/**add categories */
			$postCategories = $this->categoryRepository->getListByPost($post->getId());
			$post->setCategories($postCategories);

			$post_list[] = $post;
		}
		return $post_list;
	}

	public function getUniqueById(int $id): object
	{
		$request = 'SELECT * FROM ' . $this->table . ' WHERE id =:id';
		$q = $this->pdo->prepare($request);
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classManaged);

		$post = $q->fetch();

		$postCategories = $this->categoryRepository->getListByPost($post->getId());
		$author = $this->userRepository->getUniqueByPost($id);
		$post->setCategories($postCategories)->setAuthor($author);

		return $post;
	}
}
