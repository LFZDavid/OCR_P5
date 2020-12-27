<?php

namespace App\Controller\Front;

class HomeController
{

    public function homePage()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../views/templates/');
        $twig = new \Twig\Environment($loader, [
            // 'cache' => __DIR__.'/app/tmp',
            'cache' => false,
        ]);

        echo $twig->render('front/home.html.twig', [
            "title" => "Accueil"
        ]);

    }
}