<?php

namespace App\Controller\Front;

use Twig\Environment;
use App\Controller\Front\Controller;
use App\Model\Entity\Post;
use App\Model\Repository\PostRepository;

class PostController extends Controller
{

    public function show($id_post)
    {
        if(!$post = $this->repository->getUniqueById((int)$id_post)){
            header('location: index.php');
        }

        echo $this->twig->render('/front/post/show.html.twig', [
            "title" => $post->title(),
            "post" => $post
        ]);
    }
}