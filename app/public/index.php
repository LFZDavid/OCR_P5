<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../config.php';

use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\Front\HomeController;
use App\Controller\Front\PostController;
use App\Controller\Front\UserController;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Manager\PostManager;
use App\Model\Manager\UserManager;
use App\Model\Repository\CategoryRepository;

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
    $postManager = new PostManager($pdo);
    $userRepository = new UserRepository($pdo);
    $userManager = new UserManager($pdo);
    $CategoryRepository = new CategoryRepository($pdo);


    // Front
    //Post
    if (isset($_GET['post'])) {
        $postController = new PostController($twig, $postRepository);
        if ($_GET['post'] <= 0) {
            $postController->index();
        } else {
            $postController->show($_GET['post']);
        }
        //User
    } elseif (isset($_GET['user-form'])) {
        $userController = new UserController($twig, $userRepository, $userManager);
        if ($_GET['user-form'] == 0) {
            // TODO (get id_user in session/cookie for edit profile root)
            $userController->getForm(0);
        }
        // Back
        // Post
    } elseif (isset($_GET['admin-post'])) {
        $adminPostController = new AdminPostController($twig, $postRepository, $postManager);
        if ($_GET['admin-post'] == 'list') {
            $adminPostController->index();
        }
    } elseif (isset($_GET['admin-post-form'])) {
        $adminPostController = new AdminPostController($twig, $postRepository, $postManager);
        $adminPostController->getForm($_GET['admin-post-form'], $CategoryRepository);
    } elseif (
        isset($_GET['admin-post-delete'])
        && (isset($_POST['_method']))
        && ($_POST['_method'] == "DELETE")
    ) {
        $adminPostController = new AdminPostController($twig, $postRepository, $postManager);
        $adminPostController->delete($_GET['admin-post-delete']);

        // User
    } elseif (isset($_GET['admin-user'])) {
        $adminUserController = new AdminUserController($twig, $userRepository, $userManager);
        if ($_GET['admin-user'] == 'list') {
            $adminUserController->index();
        } elseif ($_GET['admin-user'] == 'role') {
            $adminUserController->changeRole();
        }
        // Home (default)
    } else {
        $homeController = new HomeController($twig);
        $homeController->homePage();
    }
} catch (\PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
