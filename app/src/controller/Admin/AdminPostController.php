<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Entity\Post;
use App\Model\Repository\CategoryRepository;

class AdminPostController extends Controller
{
    /**
     * 
     * Build and display post BO
     */
    public function index()
    {
        $posts = $this->repository->getList();

        echo $this->twig->render('/admin/post/index.html.twig', [
            "title" => "Administration des posts",
            "posts" => $posts,
            "message" => $this->messages
        ]);
    }

    /**
     * 
     * Build and display form
     * 
     * @param int $id_post
     * @param CategoryRepository $CategoryRepository
     */
    public function getForm(int $id_post, CategoryRepository $CategoryRepository)
    {
        if (isset($_POST)) {
            $message = $this->postProcess($_POST, $CategoryRepository);
        }

        if ($id_post == 0) {
            $post = new Post();
            $title = "Création";
        } else {
            $post = $this->repository->getUniqueById($id_post);
            $title = "Modification";
        }

        $categories = $CategoryRepository->getList();

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
            ],
            [
                "label" => 'Chapô',
                "field" => 'chapo',
                "type" => 'text',
                "hidden" => false,
                "value" => $post->getChapo() ?? "",
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
            "id_post" => $post->getId() ?? 0,
            "message" => $message // chargé après traitement du formulaire
        ]);
    }

    /**
     * @param array $data
     * @param CategoryRepository $CategoryRepository
     * @return array $message
     */
    public function postProcess(array $data, CategoryRepository $CategoryRepository): array
    {
        if ($data != []) {

            /**Check post values */
            $id = $this->checkInput($data['id']);
            $title = $this->checkInput($data['title']);
            $chapo = $this->checkInput($data['chapo']);
            $content = $this->checkInput($data['content']);
            $categories = $data['categories'] ?? [];

            /**active */
            if (isset($data['active'])) {
                $active = 1;
            } else {
                $active = 0;
            }

            if ($id > 0) {
                /** Edit */
                $post = $this->repository->getUniqueById((int) $id);
            } else {
                /** Create */
                $post = new Post();
            }

            /** Set post values for saving */
            $post->setTitle($title)->setChapo($chapo)->setContent($content)->setActive($active);

            /** Get id_post from database (auto-incremented) */
            $persisted_id = $this->manager->save($post);

            $old_post_categories = $CategoryRepository->getListByPost($persisted_id);
            $new_post_categories = $categories;

            /** Compare old linked  categories */
            foreach ($old_post_categories as $old_post_category) {
                if (isset($new_post_categories[$old_post_category->getName()])) {
                    # Category's already linked
                    unset($new_post_categories[$old_post_category->getName()]);
                } else {
                    # Category isn't linked anymore
                    $this->manager->unlinkCategory($persisted_id, $old_post_category->getId());
                }
            }
            foreach ($new_post_categories as $new_post_category_name => $new_post_category_id) {
                # Category has to be linked
                $this->manager->linkCategory($persisted_id, $new_post_category_id);
            }
            /**--- */

            if ($persisted_id > 0) {
                $message = [
                    "success" => true,
                    "content" => "Post n° $persisted_id sauvegardé !",
                    "id_post" => $persisted_id
                ];
            } else {
                $message = [
                    "success" => false,
                    "content" => "Erreur : un problème est survenu lors de l'enregistrement !"
                ];
            }
            return $message;
        }
    }

    /**
     * @param int $id_post
     */
    public function delete(int $id_post)
    {
        if ($this->repository->getUniqueById((int)$id_post)) {
            if ($this->manager->delete($id_post)) {
                $message = 'Le post n° ' . $id_post . ' a été supprimé';
            } else {
                $message = 'Impossible de supprimer le post n° ' . $id_post . ' !';
            }
        }
        $this->index($message);
    }
}
