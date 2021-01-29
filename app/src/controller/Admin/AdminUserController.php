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
        if (isset($_POST['userId']) && isset($_POST['role'])) {

            $user = $this->userRepository->getUniqueById((int) $_POST['userId']);
            $user->setRole($_POST['role']);
            $this->userManager->save($user);
            $this->fillMessage('success', 'l\'utilisateur n°' . $user->getId() . ' a été passé au niveau ' . $user->getRole() . ' !');
        }
        header('Location: /admin-user/list');
    }

    public function delete(int $userId): void
    {
        if ($this->userRepository->getUniqueById($userId)) {
            if ($this->userManager->delete($userId)) {
                $this->fillMessage('success', 'Le post n° ' . $userId . ' a été supprimé');
            } else {
                $this->fillMessage('error', 'Impossible de supprimer le post n° ' . $userId . ' !');
            }
        }
        header('Location: /admin-user/list');
    }
}
