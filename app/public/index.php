<?php

require_once __DIR__.'/../../vendor/autoload.php';
require '../../config.php';

use App\Controller\Front\HomeController;

$loader = new \Twig\Loader\FilesystemLoader('../templates/');

if($config['env'] = 'dev'){
    $twig = new \Twig\Environment($loader, [
        'cache' => false, // 'cache' => __DIR__.'/app/tmp',
        ]);
}elseif($config['env'] = 'prod'){
    $twig = new \Twig\Environment($loader, [
        'cache' => __DIR__.'/app/tmp',
        ]);
}

try{
    $pdo = new \PDO("mysql:host=" .$config['db_host']. ";dbname=" .$config['db_name'].";charset=utf8",$config['db_user'],$config['db_pwd']);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $homeController = new HomeController($twig);
    $homeController->homePage();
    

}
catch (\PDOException $e){
    echo 'Erreur : ' .$e->getMessage();
}
   


