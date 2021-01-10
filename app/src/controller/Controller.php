<?php

namespace App\Controller;

use Twig\Environment;

abstract class Controller
{
    protected Environment $twig;
    protected $repository;
    protected $manager;

    public function __construct(Environment $twig, $repository = null, $manager = null)
    {
        $this->twig = $twig;
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
