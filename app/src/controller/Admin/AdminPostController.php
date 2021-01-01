<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Entity\Post;
use App\Model\Repository\CategoryRepository;

class AdminPostController extends Controller
{
    public function index($message = null)
    {
        $message ?? "";
        $posts = $this->repository->getList();

        echo $this->twig->render('/admin/post/index.html.twig', [
            "title" => "Administration des posts",
            "posts" => $posts,
            "message" => $message
        ]);
    }

    public function getForm(int $id_post)
    {

        if (isset($_POST)) {
            $message = $this->postProcess($_POST);
        }

        if ($id_post == 0) {
            $post = new Post();
            $title = "Création";
        } else {
            $post = $this->repository->getUniqueById((int)$id_post);
            $title = "Modification";
        }

        $inputs = [
            [
                "label" => 'id_post',
                "field" => 'id',
                "type" => 'text',
                "hidden" => true,
                "value" => $post->id() ?? 0,

            ],
            [
                "label" => 'Titre',
                "field" => 'title',
                "type" => 'text',
                "hidden" => false,
                "value" => $post->title() ?? "",
            ],
            [
                "label" => 'Chapô',
                "field" => 'chapo',
                "type" => 'text',
                "hidden" => false,
                "value" => $post->chapo() ?? "",
            ],
            [
                "label" => 'Contenu',
                "field" => 'content',
                "type" => 'textarea',
                "hidden" => false,
                "value" => $post->content() ?? "",
            ],
            [
                "label" => 'Visible',
                "field" => 'active',
                "type" => 'switch',
                "hidden" => false,
                "value" => $post->active(),
            ],
        ];

        echo $this->twig->render('/admin/post/form.html.twig', [
            "title" => $title . " d'un post",
            "inputs" => $inputs,
            "id_post" => $post->id() ?? 0,
            "message" => $message // chargé après traitement du formulaire
        ]);
    }

    public function postProcess(array $data)
    {
        if ($data != []) {

            $id = $this->checkInput($data['id']);
            $title = $this->checkInput($data['title']);
            $chapo = $this->checkInput($data['chapo']);
            $content = $this->checkInput($data['content']);
            if (isset($data['active'])) {
                $active = 1;
            } else {
                $active = 0;
            }

            if ($id > 0) {
                $post = $this->repository->getUniqueById((int) $id);
            } else {
                $post = new Post();
            }
            $post->setTitle($title)->setChapo($chapo)->setContent($content)->setActive($active);

            $persisted_id = $this->manager->save($post);
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

    public function delete($id_post)
    {
        if ($this->repository->getUniqueById((int)$id_post)) {
            if ($this->manager->delete((int)$id_post)) {
                $message = 'Le post n° ' . $id_post . ' a été supprimé';
            } else {
                $message = 'Impossible de supprimer le post n° ' . $id_post . ' !';
            }
        }
        $this->index($message);
    }
}
