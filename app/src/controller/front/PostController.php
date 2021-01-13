<?php

namespace App\Controller\Front;

use App\Controller\Controller;

class PostController extends Controller
{

    /**
     * Display post in template
     *
     * @param integer $id_post
     * @return void
     */
    public function show(int $id_post)
    {
        $post = $this->repository->getUniqueById((int)$id_post);


        if ($id_post < 1 || !$post->getActive()) {
            header('location: index.php');
        }

        echo $this->twig->render('/front/post/show.html.twig', [
            "title" => $post->getTitle(),
            "post" => $post
        ]);
    }

    /**
     * Display list of Post
     *
     * @return void
     */
    public function index()
    {
        $posts = $this->repository->getListOfActives();

        echo $this->twig->render('/front/post/index.html.twig', [
            "title" => "Liste des posts",
            "posts" => $posts
        ]);
    }
}
