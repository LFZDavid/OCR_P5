<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use Twig\Environment;

class HomeController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(Environment $twig, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct($twig);
    }

    public function homePage(string $email_dest, PostRepository $postRepository, $commentRepository)
    {
        $last_posts = $postRepository->getList(3);
        $last_comments = $commentRepository->getCompleteList(true, 3);

        echo $this->twig->render('front/home.html.twig', [
            "title" => "Accueil",
            "messages" => $this->messages,
            "last_posts" => $last_posts,
            "last_comments" => $last_comments,
            "contactForm" => $this->getContactForm($email_dest)
        ]);
    }

    protected function getContactForm(string $admin_email)
    {
        $title = "Me contacter";
        $userId = $_SESSION['id_user'] ?? false;
        $user = $userId ? $this->userRepository->getUniqueById($userId) : null;

        if (!empty($_POST)) {
            $this->postProcess($_POST, $user, $admin_email);
        }

        $inputs = [
            'name' => [
                'label' => 'Pseudo',
                'name' => 'name',
                'type' => 'text',
                'value' => $user != null ? $user->getName() : '',
                'placeholder' => 'Votre pseudo'
            ],
            'email' => [
                'label' => 'email',
                'name' => 'email',
                'type' => 'text',
                'value' => $user != null ? $user->getEmail() : '',
                'placeholder' => 'Votre e-mail'
            ],
            'content' => [
                'label' => 'content',
                'name' => 'content',
                'type' => 'text',
                'placeholder' => 'Votre message...'
            ]
        ];


        return [
            "title" => $title,
            "inputs" => $inputs,
            "user_logged" => $userId > 0,
            "messages" => $this->messages
        ];
    }

    protected function postProcess(array $postData, User $user = null, string $admin_email)
    {
        $name = $user ? $user->getName() : $postData['name'];
        $email = $user ? $user->getEmail() : $postData['email'];

        $headers = 'From:' . $email . "\r\n" .
            'Reply-To: ' . $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($admin_email, 'Un message de la part de ' . $name, $postData['content'], $headers);
    }
}
