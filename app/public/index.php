<?php

require_once __DIR__.'/../../vendor/autoload.php';

use App\Controller\Front\HomeController;

$homeController = new HomeController();
$homeController->homePage();

