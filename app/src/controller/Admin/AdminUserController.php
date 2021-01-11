<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\Entity\User;

class AdminUserController extends Controller
{

    protected string $required_role = "admin";
    /**
     * 
     * Build and display user BO
     */
    public function index()
    {
        $users = $this->repository->getList();

        echo $this->twig->render('/admin/user/index.html.twig', [
            "title" => "Administration des utilisateurs",
            "users" => $users,
            "messages" => $this->messages
        ]);
    }

    public function changeRole()
    {
        $id_user = $this->checkInput($_POST['id_user']);
        $role = $this->checkInput($_POST['role']);

        $user = $this->repository->getUniqueById((int) $id_user);
        $user->setRole($role);
        $this->manager->save($user);

        $this->fillMessage('success', 'l\'utilisateur n°' . $user->getId() . ' a été passé au niveau ' . $user->getRole() . ' !');
        header('Location:index.php?admin-user=list');
    }

    /**
     * @param int $id_user
     */
    public function delete(int $id_user)
    {
        if ($this->repository->getUniqueById($id_user)) {
            if ($this->manager->delete($id_user)) {
                $message = 'Le post n° ' . $id_user . ' a été supprimé';
            } else {
                $message = 'Impossible de supprimer le post n° ' . $id_user . ' !';
            }
        }
        header('Location:index.php?admin-user=list');
    }
}
