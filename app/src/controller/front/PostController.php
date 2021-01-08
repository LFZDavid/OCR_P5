<?php

namespace App\Controller\Front;

use App\Controller\Controller;

class PostController extends Controller
{

    public function show($id_post)
    {
        $post = $this->repository->getUniqueById((int)$id_post);
        if (!$post->getId() || !$post->getActive()) {
            header('location: index.php');
        }

        echo $this->twig->render('/front/post/show.html.twig', [
            "title" => $post->getTitle(),
            "post" => $post
        ]);
    }

    public function index()
    {
        $posts = $this->repository->getListOfActives();

        echo $this->twig->render('/front/post/index.html.twig', [
            "title" => "Liste des posts",
            "posts" => $posts
        ]);
    }
}
