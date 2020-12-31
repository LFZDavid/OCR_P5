<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = $this->repository->getList();

        echo $this->twig->render('/admin/post/index.html.twig', [
            "title" => "Administration des posts",
            "posts" => $posts
        ]);
    }
}
