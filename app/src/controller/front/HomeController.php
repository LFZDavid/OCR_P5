<?php

namespace App\Controller\Front;
use App\Controller\Front\Controller;

class HomeController extends Controller
{
    public function homePage()
    {
        echo $this->twig->render('front/home.html.twig', [
            "title" => "Accueil"
        ]);

    }
}