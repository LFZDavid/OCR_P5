<?php

namespace App\Controller\Front;

use Twig\Environment;

abstract class Controller
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        
    }
    
}