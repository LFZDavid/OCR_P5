<?php

namespace App\Controller;

use Twig\Environment;
use App\Model\Entity\User;

abstract class Controller
{
    protected Environment $twig;
    protected string $requiredRole;

    public function __construct(Environment $twig, string $requiredRole = "")
    {
        $this->twig = $twig;
        $this->twig->addGlobal('app_user', $this->getUser());
        $this->twig->addGlobal('app_messages', isset($_SESSION['messages']) ? $_SESSION['messages'] : []);
        $this->twig->addGlobal('last_url', isset($_SESSION['last_url']) ? $_SESSION['last_url'] : '/');
        $_SESSION['messages'] = [];
        $this->requiredRole = $requiredRole;
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
            $this->requiredRole != ""
            && (
                $this->getUser() == null
                || $this->getUser()->getRole() != $this->requiredRole
            )
        ) {
            $this->displayError(403);
        }
    }

    protected function saveLastUrl():void
    {
        $_SESSION['last_url'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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

    protected function displayError(int $errorCode): void
    {
        http_response_code($errorCode);
        echo $this->twig->render('front/'.$errorCode.'.html.twig', []);
        exit;
    }
}
