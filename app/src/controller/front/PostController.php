<?php

namespace App\Controller\Front;

use App\Controller\Front\Controller;

class PostController extends Controller
{

    public function show($id_post)
    {
        $post = $this->repository->getUniqueById((int)$id_post);
        if(!$post->id() || !$post->active()){
            header('location: index.php');
        }

        echo $this->twig->render('/front/post/show.html.twig', [
            "title" => $post->title(),
            "post" => $post
        ]);
    }
}