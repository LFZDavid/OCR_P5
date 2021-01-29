<?php

namespace App\Controller;

use Twig\Environment;
use App\Model\Entity\User;

abstract class Controller
{
    protected Environment $twig;
    protected string $requiredRole = "";

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->twig->addGlobal('app_user', $this->getUser());
        $this->twig->addGlobal('app_messages', isset($_SESSION['messages']) ? $_SESSION['messages'] : []);
        $_SESSION['messages'] = [];
        $this->redirectIfNotAllowed();
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
            && $this->requiredRole != ""
            && $this->getUser()->getRole() != $this->requiredRole
        ) {
            header('Location: /');
        }
    }

    protected function getUser(): ?User
    {
        return isset($_SESSION['app.user']) ? $_SESSION['app.user'] : null;
    }

    protected function getCurrentUrl(): string
    {
        $url = "http";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url .= "s";
        }
        $url .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        return $url;
    }

    protected function cleanValue(string $value): string
    {
        $result = htmlspecialchars(trim($value));
        return $result;
    }
}
