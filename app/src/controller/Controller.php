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
        $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
        $this->twig->addGlobal('session', $_SESSION);
        $this->redirectIfNotAllowed();
    }

    /**
     * escape suspicius inputs
     * for validate form
     *
     * @param string $data
     * @return string
     */
    public function checkInput(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    protected function fillMessage(string $type, string $content)
    {
        $message = [
            "type" => $type,
            "content" => $content
        ];
        array_push($this->messages, $message);
    }

    public function redirectIfNotAllowed()
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
