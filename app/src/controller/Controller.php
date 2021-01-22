<?php

namespace App\Controller;

use Twig\Environment;
use App\Model\Entity\User;

abstract class Controller
{
    protected Environment $twig;
    protected string $required_role = "";

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        // $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('app.user', $this->getUser());

        $this->redirectIfNotAllowed();
        $twig->addGlobal('app.messages', $_SESSION['messages']);
        $_SESSION['messages'] = [];
    }

    protected function fillMessage(string $type, string $content): void
    {
        $message = [
            "type" => $type,
            "content" => $content
        ];

        $_SESSION['messages'][] = $message;
    }

    protected function redirectIfNotAllowed(): void
    {
        if (
            $this->getUser() == null
            && $this->required_role != ""
            && $this->getUser()->getRole() != $this->required_role
        ) {
            header('Location: index.php');
        }
    }

    protected function getUser(): ?User
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    protected function getCurrentUrl(): string
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https";
        } else {
            $url = "http";
        }
        $url .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        return $url;
    }
}
