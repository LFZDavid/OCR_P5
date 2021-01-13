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
        $comments_complete_list = $this->repository->getCompleteList();
        echo $this->twig->render('/admin/comment/index.html.twig', [
            "title" => "Administration des commentaires",
            "comments" => $comments_complete_list,
            "messages" => $this->messages
        ]);
    }

    public function toggle()
    {
        $comment = $this->repository->getUniqueById($_POST['id_comment']);

        $toggle = $comment->getActive() ? false : true;

        $comment->setActive($toggle);
        $this->manager->save($comment);
        $this->fillMessage('success', 'Le comment est mis à jour !');
        header('Location:index.php?admin-comment=list');
    }

    /**
     * @param integer $id_comment
     * @return void
     */
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
