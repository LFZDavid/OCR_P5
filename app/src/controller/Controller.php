<?php

namespace App\Controller;

use Twig\Environment;

abstract class Controller
{
    protected Environment $twig;
    protected array $messages = [];
    protected string $required_role = "";

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->twig->addGlobal('session', $_SESSION);
        $_SESSION['messages'] = $this->messages;
        $this->redirectIfNotAllowed();
    }

    protected function fillMessage(string $type, string $content): void
    {
        $message = [
            "type" => $type,
            "content" => $content
        ];

        array_push($this->messages, $message);
        $_SESSION['messages'] = $this->messages;
    }

    public function redirectIfNotAllowed(): void
    {
        if (
            !empty($_SESSION)
            && $this->required_role != ""
            && $_SESSION['role_user'] != $this->required_role
        ) {
            header('Location: index.php');
        }
    }
}
