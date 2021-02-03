<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Entity\Post;
use App\Model\Manager\CommentManager;
use App\Model\Manager\PostManager;
use Twig\Environment;
use App\Model\Repository\PostRepository;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\UserRepository;

class AdminPostController extends Controller
{
    protected string $required_role = "admin";
    private CategoryRepository $categoryRepository;
    private PostRepository $postRepository;
    private PostManager $postManager;
    private CommentManager $commentManager;

    public function __construct(
        Environment $twig,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository,
        PostManager $postManager,
        CommentManager $commentManager
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->postManager = $postManager;
        $this->commentManager = $commentManager;

        parent::__construct($twig);
    }

    public function index(): void
    {
        $posts = $this->postRepository->getList();

        echo $this->twig->render('/admin/post/index.html.twig', [
            "title" => "Administration des posts",
            "posts" => $posts
        ]);
    }

    public function getForm(int $postId): void
    {
        $data = $_POST;
        if (!empty($data)) {
            $this->postProcess($data, $this->categoryRepository);
        }

        if ($postId == 0) {
            $post = new Post();
            $title = "Création";
        } else {
            $post = $this->postRepository->getUniqueById($postId);
            $title = "Modification";
        }

        $categories = $this->categoryRepository->getList();
        foreach ($post->getCategories() as $postCategory) {
            $checkedCategories[$postCategory->getName()] = true;
        }

        echo $this->twig->render('/admin/post/form.html.twig', [
            "title" => $title,
            "post" => $post,
            "categories" => $categories,
            "checked_categories" => $checkedCategories ?? []
        ]);
    }

    public function postProcess(array $data): void
    {
        $categories = key_exists('categories', $data) ? $data['categories'] : [];
        $active = isset($data['active']) ? 1 : 0;
        $post = $data['id'] > 0 ? $this->postRepository->getUniqueById($data['id']) : new Post();
        $author = $this->getUser();
        if (!$author) {
            $this->fillMessage('error', 'Un problème est survenue : L\'auteur n\'est pas identifié');
        }
        $post->setTitle($data['title'])
            ->setChapo($data['chapo'])
            ->setContent($data['content'])
            ->setActive($active)
            ->setAuthor($author);
        $persistedId = $this->postManager->save($post);

        $oldPostCategories = $this->categoryRepository->getListByPost($persistedId);
        $newPostCategories = $categories;

        foreach ($oldPostCategories as $oldPostCategory) {
            if (isset($newPostCategories[$oldPostCategory->getName()])) {
                # Category's already linked
                unset($newPostCategories[$oldPostCategory->getName()]);
            } else {
                # Category isn't linked anymore
                $this->postManager->unlinkCategory($persistedId, $oldPostCategory->getId());
            }
        }
        foreach ($newPostCategories as $categoryId) {
            # Category has to be linked
            $this->postManager->linkCategory($persistedId, $categoryId);
        }

        $this->fillMessage('success', "Post n° $persistedId sauvegardé !");
        header('Location:/admin-post/list');
    }

    public function delete(int $postID): void
    {
        if ($this->postRepository->getUniqueById($postID)) {
            $this->commentManager->deleteByPost($postID);
            if ($this->postManager->delete($postID)) {
                $this->fillMessage('success', 'Le post n° ' . $postID . ' a été supprimé');
            } else {
                $this->fillMessage('error', 'Impossible de supprimer le post n° ' . $postID . ' !');
            }
        }
        header('Location:/admin-post/list');
    }
}
