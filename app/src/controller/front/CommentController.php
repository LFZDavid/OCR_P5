<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\Comment;

class CommentController extends Controller
{

    public function postProcess(array $post_data, int $id_post)
    {
        $success = true;

        if (empty($_SESSION) || empty($_POST) || $id_post < 1) {
            $this->fillMessage('error', 'Un problème est survenu!');
            $success = false;
        } elseif (empty($post_data['comment_content'])) {
            $this->fillMessage('error', 'Votre commentaire ne peut pas être vide !');
            $success = false;
        }


        if ($success) {
            $comment = new Comment();
            $comment->setContent($this->checkInput($post_data['comment_content']))
                ->setIdAuthor($_SESSION['id_user'])
                ->setIdPost($id_post);

            if ($this->manager->save($comment)) {
                $this->fillMessage('success', 'Votre commentaire est enregistré, il sera soumis à validation avant d\'être visible.');
            }
        }

        header('Location: index.php?post=' . $id_post);
    }
}
