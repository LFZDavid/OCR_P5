<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use Twig\Environment;
use App\Model\Repository\PostRepository;
use App\Model\Repository\CommentRepository;

class PostController extends Controller
{
    private PostRepository $postRepository;
    private CommentRepository $commentRepository;


    public function __construct(Environment $twig, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;

        parent::__construct($twig);
    }

    public function show(int $id_post): void
    {
        $post = $this->postRepository->getUniqueById($id_post);

        if (!$post || !$post->getActive()) {
            header('location: index.php');
        }

        echo $this->twig->render('/front/post/show.html.twig', [
            "post" => $post,
            'comments' => $this->commentRepository->getListByPost($post->getId(), true),
            "title" => $post->getTitle()
        ]);
    }

    public function index(): void
    {
        $posts = $this->postRepository->getListOfActives();
        echo $this->twig->render('/front/post/index.html.twig', [
            "posts" => $posts,
            "title" => "Liste des articles"
        ]);
    }
}
