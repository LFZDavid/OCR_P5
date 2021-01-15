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
                $userController->getResetPwdForm($_GET['id_user'], $_GET['hash']);
            } else {
                header('Location:index.php?user=login');
            }
        }
    } elseif (key_exists('comment', $_GET)) {
        $commentController = new CommentController($twig, $commentManager);
        $request = $_GET['comment'];
        if ($request == 'add') {
            $commentController->postProcess($_POST, $_GET['id_post']);
        }
    } elseif (key_exists('admin-post', $_GET)) {
        $adminPostController = new AdminPostController($twig, $postRepository, $categoryRepository, $userRepository, $postManager);
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
        } elseif ($request == 'toggle') {
            $adminCommentController->toggle();
        } elseif (
            $request == 'delete'
            && key_exists('_method', $_POST)
            && key_exists('id_comment', $_GET)
            && ($_POST['_method'] == "DELETE")
        ) {
            $adminCommentController->delete($_GET['id_comment']);
        }
    } else {
        $homeController = new HomeController($twig, $userRepository);
        $homeController->homePage($config['admin_email']);
    }
    /**DEBUG **************************/
    echo '<div style="background-color:black;color:green;">';
    echo 'SESSION';
    echo '<br>';
    foreach ($_SESSION as $key => $value) {
        print_r($key);
        echo '=>';
        print_r($value);
        echo '<br>';
    }
    echo '<br>';
    echo 'POST';
    echo '<br>';
    foreach ($_POST as $key => $value) {
        print_r($key);
        echo '=>';
        print_r($value);
        echo '<br>';
    }
    echo '<br>';
    echo 'GET';
    echo '<br>';
    foreach ($_GET as $key => $value) {
        print_r($key);
        echo '=>';
        print_r($value);
        echo '<br>';
    }
    echo '<br>';

    echo '</div>';
    /** **************************/
} catch (\PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
