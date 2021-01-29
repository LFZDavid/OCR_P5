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

    public function show(int $id): void
    {
        $post = $this->postRepository->getUniqueById($id);

        if (!$post || !$post->getActive()) {
            header('location: /'); // Remplacer par redirect to 404
        }

        $comments = $this->commentRepository->getListByPost($post->getId(), true);

        echo $this->twig->render('/front/post/show.html.twig', [
            "post" => $post,
            'comments' => $comments,
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
