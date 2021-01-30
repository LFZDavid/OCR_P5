<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Repository\CommentRepository;
use App\Model\Manager\CommentManager;
use Twig\Environment;

class AdminCommentController extends Controller
{

    protected string $required_role = "admin";

    private CommentRepository $commentRepository;
    private CommentManager $commentManager;

    public function __construct(Environment $twig, CommentRepository $commentRepository, CommentManager $commentManager)
    {
        $this->commentRepository = $commentRepository;
        $this->commentManager = $commentManager;

        parent::__construct($twig);
    }

    public function index(): void
    {
        $commentsList = $this->commentRepository->getCompleteList();
        echo $this->twig->render('/admin/comment/index.html.twig', [
            "title" => "Administration des commentaires",
            "comments" => $commentsList
        ]);
    }

    public function toggle(): void
    {
        $comment = $this->commentRepository->getUniqueById((int) $_POST['commentId']);

        $toggle = $comment->getActive() ? false : true;

        $comment->setActive($toggle);
        $this->commentManager->save($comment);
        $this->fillMessage('success', 'Le comment est mis à jour !');
        header('Location:/admin-comment/list');
    }

    public function delete(int $commentId): void
    {
        if ($this->commentRepository->getUniqueById($commentId)) {
            if ($this->commentManager->delete($commentId)) {
                $this->fillMessage('success', 'Le commentaire n° ' . $commentId . ' a été supprimé');
            } else {
                $this->fillMessage('success', 'Impossible de supprimer le commentaire n° ' . $commentId . ' !');
            }
        }
        header('Location:/admin-comment/list');
    }
}
