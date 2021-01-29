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
        if (isset($_POST) && !empty($_POST)) {
            $data = $_POST;
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
        foreach ($post->getCategories() as $post_category) {
            $checked_categories[$post_category->getName()] = true;
        }

        echo $this->twig->render('/admin/post/form.html.twig', [
            "title" => $title,
            "post" => $post,
            "categories" => $categories,
            "checked_categories" => $checked_categories ?? []
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
        $persisted_id = $this->postManager->save($post);

        $old_post_categories = $this->categoryRepository->getListByPost($persisted_id);
        $new_post_categories = $categories;

        foreach ($old_post_categories as $old_post_category) {
            if (isset($new_post_categories[$old_post_category->getName()])) {
                # Category's already linked
                unset($new_post_categories[$old_post_category->getName()]);
            } else {
                # Category isn't linked anymore
                $this->postManager->unlinkCategory($persisted_id, $old_post_category->getId());
            }
        }
        foreach ($new_post_categories as $new_post_category_id) {
            # Category has to be linked
            $this->postManager->linkCategory($persisted_id, $new_post_category_id);
        }

        $this->fillMessage('success', "Post n° $persisted_id sauvegardé !");
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
