<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use Twig\Environment;
use App\Model\Manager\UserManager;
use App\Model\Repository\UserRepository;

class AdminUserController extends Controller
{

    protected string $required_role = "admin";
    private UserRepository $userRepository;
    private UserManager $userManager;

    public function __construct(
        Environment $twig,
        UserRepository $userRepository,
        UserManager $userManager
    ) {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;

        parent::__construct($twig);
    }



    public function index(): void
    {
        echo $this->twig->render('/admin/user/index.html.twig', [
            "title" => "Administration des utilisateurs",
            "users" => $this->userRepository->getList(),
        ]);
    }

    public function changeRole(): void
    {
        if (isset($_POST['id_user']) && isset($_POST['role'])) {

            $user = $this->userRepository->getUniqueById((int) $_POST['id_user']);
            $user->setRole($_POST['role']);
            $this->userManager->save($user);
            $this->fillMessage('success', 'l\'utilisateur n°' . $user->getId() . ' a été passé au niveau ' . $user->getRole() . ' !');
        }
        header('Location:index.php?admin-user=list');
    }

    public function delete(int $id_user): void
    {
        if ($this->userRepository->getUniqueById($id_user)) {
            if ($this->userManager->delete($id_user)) {
                $this->fillMessage('success', 'Le post n° ' . $id_user . ' a été supprimé');
            } else {
                $this->fillMessage('error', 'Impossible de supprimer le post n° ' . $id_user . ' !');
            }
        }
        header('Location:index.php?admin-user=list');
    }
}
