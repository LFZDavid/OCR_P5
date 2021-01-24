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

    public function getForm(int $id_post): void
    {
        if (isset($_POST)) {
            $this->postProcess($_POST, $this->categoryRepository);
        }

        if ($id_post == 0) {
            $post = new Post();
            $title = "Création";
        } else {
            $post = $this->postRepository->getUniqueById($id_post);
            $title = "Modification";
        }

        $categories = $this->categoryRepository->getList();

        foreach ($post->getCategories() as $post_category) {
            $checked_categories[$post_category->getName()] = true;
        }

        $inputs = [
            [
                "label" => 'id_post',
                "field" => 'id',
                "type" => 'text',
                "hidden" => true,
                "value" => $post->getId() ?? 0,

            ],
            [
                "label" => 'Titre',
                "field" => 'title',
                "type" => 'text',
                "hidden" => false,
                "value" => $post->getTitle() ?? "",
                "required" => true
            ],
            [
                "label" => 'Chapô',
                "field" => 'chapo',
                "type" => 'text',
                "hidden" => false,
                "value" => $post->getChapo() ?? "",
                "required" => true
            ],
            [
                "label" => 'Categories',
                "field" => 'categories',
                "type" => 'checkbox',
                "hidden" => false,
                "value" => $categories,
                "checked_categories" => $checked_categories ?? []
            ],
            [
                "label" => 'Contenu',
                "field" => 'content',
                "type" => 'textarea',
                "hidden" => false,
                "value" => $post->getContent() ?? "",
                "required" => true
            ],
            [
                "label" => 'Visible',
                "field" => 'active',
                "type" => 'switch',
                "hidden" => false,
                "value" => $post->getActive(),
            ],
        ];

        echo $this->twig->render('/admin/post/form.html.twig', [
            "title" => $title . " d'un post",
            "inputs" => $inputs,
            "id_post" => $post->getId() ?? 0
        ]);
    }

    public function postProcess(array $data): void
    {
        if ($data != []) {

            /**Check post values */
            $categories = key_exists('categories', $data) ? $data['categories'] : [];

            /**active */
            if (isset($data['active'])) {
                $active = 1;
            } else {
                $active = 0;
            }

            if ($data['id'] > 0) {
                /** Edit */
                $post = $this->postRepository->getUniqueById($data['id']);
            } else {
                /** Create */
                $post = new Post();
            }

            $author = $this->getUser();
            if (!$author) {
                $this->fillMessage('error', 'Un problème est survenue : L\'auteur n\'est pas identifié');
            }
            /** Set post values for saving */
            $post->setTitle($data['title'])
                ->setChapo($data['chapo'])
                ->setContent($data['content'])
                ->setActive($active)
                ->setAuthor($author);
            /** Get id_post from database (auto-incremented) */
            $persisted_id = $this->postManager->save($post);

            $old_post_categories = $this->categoryRepository->getListByPost($persisted_id);
            $new_post_categories = $categories;

            /** Compare old linked  categories */
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
            /**--- */

            if ($persisted_id > 0) {
                $this->fillMessage('success', "Post n° $persisted_id sauvegardé !");
            } else {
                $this->fillMessage('error', "Erreur : un problème est survenu lors de l'enregistrement !");
            }
            header('Location:/admin-post/list');
        }
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
