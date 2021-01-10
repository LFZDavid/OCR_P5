<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../config.php';

use App\Controller\Admin\AdminPostController;
use App\Controller\Front\HomeController;
use App\Controller\Front\PostController;
use App\Model\Repository\PostRepository;

$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader, [
    'cache' => $config['env'] == 'prod' ? __DIR__ . '/app/tmp' : false,
    'debug' => $config['env'] == 'dev' ? true : false,
]);
$config['env'] == 'dev' ? $twig->addExtension(new \Twig\Extension\DebugExtension()) : "";


try {
    $pdo = new \PDO("mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8", $config['db_user'], $config['db_pwd']);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $postRepository = new PostRepository($pdo);

    // Front
    if (isset($_GET['post'])) {
        $postController = new PostController($twig, $postRepository);
        if ($_GET['post'] <= 0) {
            $postController->index();
        } else {
            $postController->show($_GET['post']);
        }

        // Back
    } elseif (isset($_GET['admin-post'])) {
        $adminPostController = new AdminPostController($twig, $postRepository);
        if ($_GET['admin-post'] <= 0) {
            $adminPostController->index();
        } else {
            // $adminPostController->form($_GET['admin-post']);
        }
    } else {
        $homeController = new HomeController($twig);
        $homeController->homePage();
    }
} catch (\PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
