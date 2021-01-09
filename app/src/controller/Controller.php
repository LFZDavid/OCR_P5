<?php

namespace App\Controller;

use Twig\Environment;

abstract class Controller
{
    protected Environment $twig;
    protected $repository;
    protected $manager;
    protected array $messages = [];
    protected int $isLogged;

    public function __construct(Environment $twig, $repository = null, $manager = null)
    {
        $this->twig = $twig;
        $this->repository = $repository;
        $this->manager = $manager;
        $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
        $this->isLogged = isset($_SESSION) ? true : false;
    }

    public function checkInput($data)
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
}
