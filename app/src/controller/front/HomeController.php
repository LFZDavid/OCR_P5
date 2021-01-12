<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Repository\UserRepository;
use Twig\Environment;

class HomeController extends Controller
{

    public function homePage($contactController, $email_dest, $userRepository)
    {
        $id_user = $_SESSION['id_user'] ?? false;
        if (!empty($_POST)) {
            $contactController->postProcess($_POST, $userRepository->getUniqueById($id_user), $email_dest);
        }
        echo $this->twig->render('front/home.html.twig', [
            "title" => "Accueil",
            "messages" => $this->messages,
            "contact_form" => $contactController->getContactForm($email_dest)
        ]);
    }
}
