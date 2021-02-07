<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require '../../config.php';

use App\Controller\Admin\AdminCommentController;
use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\Front\CommentController;
use App\Controller\Front\HomeController;
use App\Controller\Front\PostController;
use App\Controller\Front\UserController;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;
use App\Model\Manager\PostManager;
use App\Model\Manager\UserManager;
use App\Model\Manager\CommentManager;
use App\Model\Repository\CategoryRepository;
use App\Model\Validator\UserValidator;

$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader, [
    'cache' => $config['env'] == 'prod' ? __DIR__ . '/app/tmp' : false,
    'debug' => $config['env'] == 'dev' ? true : false,
]);

$config['env'] == 'dev' ? $twig->addExtension(new \Twig\Extension\DebugExtension()) : "";

try {
    $pdo = new \PDO("mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8", $config['db_user'], $config['db_pwd']);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $userRepository = new UserRepository($pdo);
    $commentRepository = new CommentRepository($pdo);
    $categoryRepository = new CategoryRepository($pdo);
    $postRepository = new PostRepository($pdo, $categoryRepository, $userRepository);
    $postManager = new PostManager($pdo);
    $userManager = new UserManager($pdo);
    $commentManager = new CommentManager($pdo);
    
    if (key_exists('post', $_GET)) {
        $postController = new PostController($twig, $postRepository, $commentRepository);
        if (($id_post = (int) $_GET['post']) <= 0) {
            $postController->index();
        } else {
            $postController->show($id_post);
        }
    } elseif (key_exists('user', $_GET)) {
        $userValidator = new UserValidator($userRepository);
        $userController = new UserController($twig, $userRepository, $userManager, $userValidator);
        $request = htmlspecialchars($_GET['user']);
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
            && key_exists('userId', $_GET)
            && key_exists('hash', $_GET)
        ) {
            if (($userId = $_GET['userId']) > 0) {
                $userController->getResetPwdForm($userId, $_GET['hash']);
            } else {
                header('Location:/user/login');
            }
        } else {
            header('Location: /');
        }
    } elseif (key_exists('comment', $_GET)) {
        $commentController = new CommentController($twig, $commentManager);
        $request = $_GET['comment'];
        if ($request == 'add') {
            $commentController->postProcess($_POST, (int) $_GET['postId']);
        }
    } elseif (key_exists('admin-post', $_GET)) {
        $adminPostController = new AdminPostController($twig, $postRepository, $categoryRepository, $userRepository, $postManager, $commentManager);
        $request = $_GET['admin-post'];
        if ($request == 'list') {
            $adminPostController->index();
        } elseif ($request == 'form') {
            if (key_exists('postId', $_GET)) {
                $postId = (int) $_GET['postId'];
            } else {
                $postId = 0;
            }
            $adminPostController->getForm($postId, $categoryRepository);
        } elseif (
            $request == 'delete'
            && key_exists('_method', $_POST)
            && key_exists('postId', $_GET)
            && ($_POST['_method'] == "DELETE")
        ) {
            $adminPostController->delete((int) $_GET['postId']);
        }
    } elseif (key_exists('admin-user', $_GET)) {
        $adminUserController = new AdminUserController($twig, $userRepository, $userManager);
        $request = $_GET['admin-user'];
        if ($request == 'list') {
            $adminUserController->index();
        } elseif ($request == 'role') {
            $adminUserController->changeRole();
        } elseif ($request == 'delete' && key_exists('userId', $_GET)) {
            $adminUserController->delete((int) $_GET['userId']);
        }
    } elseif (key_exists('admin-comment', $_GET)) {
        $adminCommentController = new AdminCommentController($twig, $commentRepository, $commentManager);
        $request = $_GET['admin-comment'];
        if ($request == 'list') {
            $adminCommentController->index();
        } elseif ($request == 'toggle') {
            $adminCommentController->toggle();
        } elseif (
            $request == 'delete'
            && key_exists('_method', $_POST)
            && key_exists('commentId', $_GET)
            && ($_POST['_method'] == "DELETE")
        ) {
            $adminCommentController->delete($_GET['commentId']);
        }
    } else {
        $homeController = new HomeController($twig, $userRepository);
        $homeController->homePage($config['admin_email'], $postRepository, $commentRepository);
    }
} catch (\PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
