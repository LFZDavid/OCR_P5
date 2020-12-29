<?php

namespace App\Controller\Front;

abstract class Controller
{
    protected $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader('../views/templates/');
        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => false, // 'cache' => __DIR__.'/app/tmp',
        ]);
    }
    
}