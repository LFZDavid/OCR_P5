<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

class AdminCommentController extends Controller
{

    protected string $required_role = "admin";

    /**
     * Build and display comments BO
     *
     * @return void
     */
    public function index()
    {
        $comments = $this->repository->getList();

        echo $this->twig->render('/admin/comment/index.html.twig', [
            "title" => "Administration des commentaires",
            "comments" => $comments,
            "messages" => $this->message
        ]);
    }

    public function delete(int $id_comment)
    {
        if ($this->repository->getUniqueById($id_comment)) {
            if ($this->manager->delete($id_comment)) {
                $this->fillMessage('success', 'Le commentaire n° ' . $id_comment . ' a été supprimé');
            } else {
                $this->fillMessage('success', 'Impossible de supprimer le commentaire n° ' . $id_comment . ' !');
            }
        }
        header('Location:index.php?admin-comment=list');
    }
}
