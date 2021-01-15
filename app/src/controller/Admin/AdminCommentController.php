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
        $comments_complete_list = $this->commentRepository->getCompleteList();
        echo $this->twig->render('/admin/comment/index.html.twig', [
            "title" => "Administration des commentaires",
            "comments" => $comments_complete_list,
            "messages" => $this->messages
        ]);
    }

    public function toggle(): void
    {
        $comment = $this->commentRepository->getUniqueById($_POST['id_comment']);

        $toggle = $comment->getActive() ? false : true;

        $comment->setActive($toggle);
        $this->commentManager->save($comment);
        $this->fillMessage('success', 'Le comment est mis à jour !');
        header('Location:index.php?admin-comment=list');
    }

    public function delete(int $id_comment): void
    {
        if ($this->commentRepository->getUniqueById($id_comment)) {
            if ($this->commentManager->delete($id_comment)) {
                $this->fillMessage('success', 'Le commentaire n° ' . $id_comment . ' a été supprimé');
            } else {
                $this->fillMessage('success', 'Impossible de supprimer le commentaire n° ' . $id_comment . ' !');
            }
        }
        header('Location:index.php?admin-comment=list');
    }
}
