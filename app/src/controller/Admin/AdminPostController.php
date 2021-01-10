<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

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
