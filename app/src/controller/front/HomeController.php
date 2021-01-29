<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;
use Twig\Environment;

class HomeController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(Environment $twig, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct($twig);
    }

    public function homePage(string $email_dest, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $last_posts = $postRepository->getList(3);
        $last_comments = $commentRepository->getCompleteList(true, 3);

        echo $this->twig->render('front/home.html.twig', [
            "title" => "Accueil",
            "last_posts" => $last_posts,
            "last_comments" => $last_comments,
            "contactForm" => $this->getContactForm($email_dest)
        ]);
    }

    protected function getContactForm(string $adminEmail)
    {
        $title = "Me contacter";
        $user = $this->getUser();

        if (!empty($_POST)) {
            $this->postProcess($_POST, $user, $adminEmail);
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
            "inputs" => $inputs
        ];
    }

    protected function postProcess(array $postData, User $user = null, string $admin_email)
    {

        foreach ($postData as $key => $value) {
            if (trim($value) == '') {
                $this->fillMessage('error', 'Le champ ' . $key . ' ne doit pas être vide');
                $success = false;
            } else {
                $success = true;
            }
        }

        if (!$success) {
            return;
        }

        $name = $user ? $user->getName() : $postData['name'];
        $email = $user ? $user->getEmail() : $postData['email'];

        $headers = 'From:' . $email . "\r\n" .
            'Reply-To: ' . $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($admin_email, 'Un message de la part de ' . $name, $postData['content'], $headers);
    }
}
