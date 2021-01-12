<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../config.php';

use App\Controller\Admin\AdminCommentController;
use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\Front\HomeController;
use App\Controller\Front\PostController;
use App\Controller\Front\UserController;
use App\Controller\Front\ContactController;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;
use App\Model\Manager\PostManager;
use App\Model\Manager\UserManager;
use App\Model\Manager\CommentManager;
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
    $userRepository = new UserRepository($pdo);
    $commentRepository = new CommentRepository($pdo);
    $postManager = new PostManager($pdo);
    $userManager = new UserManager($pdo);
    $commentManager = new CommentManager($pdo);
    $categoryRepository = new CategoryRepository($pdo);


    if (key_exists('post', $_GET)) {
        $postController = new PostController($twig, $postRepository);
        if (($id_post = $_GET['post']) <= 0) {
            $postController->index();
        } else {
            $postController->show($id_post);
        }
    } elseif (key_exists('user', $_GET)) {
        $userController = new UserController($twig, $userRepository, $userManager);
        $request = $_GET['user'];
        if ($request == 'form') {
            $userController->getForm();
        } elseif ($request == 'login') {
            $userController->getLogInForm();
        } elseif ($request == 'logout') {
            $userController->logOut();
        } elseif ($request == 'lost-pwd') {
            $userController->lostPwdProcess();
        } elseif (
            $request == 'reset-pwd'
            && key_exists('id_user', $_GET)
            && key_exists('hash', $_GET)
        ) {
            if ($id_user = $_GET['id_user'] > 0) {
                $userController->getResetPwdForm($id_user, $_GET['hash']);
            } else {
                header('Location:index.php?user=login');
            }
        }
    } elseif (key_exists('admin-post', $_GET)) {
        $adminPostController = new AdminPostController($twig, $postRepository, $postManager);
        $request = $_GET['admin-post'];
        if ($request == 'list') {
            $adminPostController->index();
        } elseif ($request == 'form') {
            if (key_exists('id_post', $_GET)) {
                $id_post = $_GET['id_post'];
            } else {
                $id_post = 0;
            }
            $adminPostController->getForm($id_post, $categoryRepository);
        } elseif (
            $request == 'delete'
            && key_exists('_method', $_POST)
            && key_exists('id_post', $_GET)
            && ($_POST['_method'] == "DELETE")
        ) {
            $adminPostController = new AdminPostController($twig, $postRepository, $postManager);
            $adminPostController->delete($_GET['id_post']);
        }
    } elseif (key_exists('admin-user', $_GET)) {
        $adminUserController = new AdminUserController($twig, $userRepository, $userManager);
        $request = $_GET['admin-user'];
        if ($request == 'list') {
            $adminUserController->index();
        } elseif ($request == 'role') {
            $adminUserController->changeRole();
        } elseif ($request == 'delete' && key_exists('id_user', $_GET)) {
            $adminUserController->delete($_GET['id_user']);
        }
    } elseif (key_exists('admin-comment', $_GET)) {
        $adminCommentController = new AdminCommentController($twig, $commentRepository, $commentManager);
        $request = $_GET['admin-comment'];
        if ($request == 'list') {
            $adminCommentController->index();
        }
    } else {
        $contactController = new ContactController($twig, $userRepository);

        $homeController = new HomeController($twig);
        $homeController->homePage($contactController, $config['admin_email'], $userRepository);
    }
} catch (\PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
