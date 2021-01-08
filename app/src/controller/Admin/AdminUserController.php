<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Entity\User;

class AdminUserController extends Controller
{
    /**
     * 
     * Build and display user BO
     */
    public function index($message = null)
    {
        $message ?? "";
        $users = $this->repository->getList();

        echo $this->twig->render('/admin/user/index.html.twig', [
            "title" => "Administration des utilisateurs",
            "users" => $users,
            "message" => $message
        ]);
    }

    public function changeRole()
    {
        $id_user = $this->checkInput($_POST['id_user']);
        $role = $this->checkInput($_POST['role']);

        $user = $this->repository->getUniqueById($id_user);
        $user->setRole((string) $role);
        $this->manager->save($user);


        $this->fillMessage('success', 'l\'utilisateur n° x a été passé au niveau y !');
        $this->index();
    }

    /**
     * @param int $id_user
     */
    public function delete($id_user)
    {
        if ($this->repository->getUniqueById((int)$id_user)) {
            if ($this->manager->delete((int)$id_user)) {
                $message = 'L\'utilisateur n° ' . $id_user . ' a été supprimé';
            } else {
                $message = 'Impossible de supprimer l\'utilisateur n° ' . $id_user . ' !';
            }
        }
        $this->index($message);
    }
}
