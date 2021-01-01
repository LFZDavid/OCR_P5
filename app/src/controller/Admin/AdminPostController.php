<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Entity\Post;

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

    public function getForm(int $id_post = null, array $message = null)
    {
        if ($id_post === null) {
            $post = new Post();
            $title = "Création";
        } else {
            $post = $this->repository->getUniqueById((int)$id_post);
            $title = "Modification";
        }

        $post_data = [
            "title" => $post->title() ?? "",
            "chapo" => $post->chapo() ?? "",
            "content" => $post->content() ?? ""
        ];

        echo $this->twig->render('/admin/post/form.html.twig', [
            "title" => $title . " d'un post",
            "post_data" => $post_data,
            "message" => $message // chargé après traitement du formulaire
        ]);
    }

    public function postProcess(array $data)
    {
        $post = new Post($data);

        if ($this->manager->save($post)) {
            $message = [
                "success" => true,
                "content" => "Post sauvegardé !"
            ];
        } else {
            $message = [
                "success" => false,
                "content" => "Erreur : un problème est survenu lors de l'enregistrement !"
            ];
        }
        $this->getForm($post->id(), $message);
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
