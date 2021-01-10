<?php

namespace App\Controller;

use Twig\Environment;

abstract class Controller
{
    protected Environment $twig;
    protected $repository;

    public function __construct(Environment $twig, $repository = null)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }
}
