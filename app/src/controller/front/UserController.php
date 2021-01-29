<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;
use Twig\Environment;
use App\Model\Repository\UserRepository;
use App\Model\Manager\UserManager;
use App\Model\Validator\UserValidator;

class UserController extends Controller
{

    private UserRepository $userRepository;
    private UserManager $userManager;
    private UserValidator $userValidator;

    public function __construct(Environment $twig, UserRepository $userRepository, UserManager $userManager, UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->userValidator = $userValidator;

        parent::__construct($twig);
    }

    public function getForm(): void
    {

        $user = $this->getUser();

        if (!$user) {
            $user = new User();
            $title = "Inscription";
            $edit = false;
        } else {
            $title = "Modification";
            $edit = true;
            $change_pwd_link = '/user/reset-pwd/hash/' . $user->getPwd() . '/id_user/' . $user->getId();
        }

        if (!empty($_POST)) {
            $data = $_POST;
            $errors = $this->postProcess($edit, $data, $user);
        }

        echo $this->twig->render('/front/user/form.html.twig', [
            "title" => $title,
            "edit" => $edit,
            "user" => $user,
            "errors" => $errors ?? [],
            "change_pwd_link" => $change_pwd_link ?? "#"
        ]);
    }

    private function postProcess(bool $edit, array $data, User $user = null): array
    {
        $errors = $this->userValidator->validFormData($user, $data);
        if (!empty($errors)) {
            return $errors;
        }
        $user->setName($this->cleanValue($data['name']))
            ->setEmail($this->cleanValue($data['email']));
        if (!$edit) {
            $hashedPwd = password_hash($data['pwd'], PASSWORD_DEFAULT);
            $user->setPwd($hashedPwd);
        }
        $this->userManager->save($user);
        $_SESSION['app.user'] = $user;

        $this->fillMessage('success', 'Utilisateur enregistré !');
        header('Location: /');
    }

    public function getLogInForm(): void
    {
        if ($this->getUser()) {
            header('Location: /');
        }

        if (!empty($_POST)) {
            $data = $_POST;
            $errors = $this->logIn($data);
        }

        $lost_pwd_form = [
            'name' => 'email_user',
            'label' => 'Email',
            'type' => 'email',
            'value' => ""
        ];

        echo $this->twig->render('/front/user/login-form.html.twig', [
            "title" => "Connexion",
            "errors" => $errors ?? [],
            "lost_pwd_form" => $lost_pwd_form
        ]);
    }

    protected function logIn(array $post_data, bool $force = false): ?array
    {

        $validationReturns = $this->userValidator->validLoginForm($post_data, $force);

        if (isset($validationReturns['errors'])) {
            var_dump($validationReturns);
            return $validationReturns['errors'];
        }

        $this->fillMessage('success', 'Vous êtes connecté !');
        $_SESSION['app.user'] = $validationReturns['user'];

        header('Location: /');
    }

    public function logOut(): void
    {
        session_destroy();
        header('Location: /');
    }

    public function lostPwdProcess(): void
    {
        if (isset($_POST['email_user'])) {
            $email_user = $_POST['email_user'];
            if ($user = $this->userRepository->getUniqueByEmail($email_user)) {
                $this->sendResetPwdEmail($user);
                $this->fillMessage('success', 'Un email vient de vous être envoyé !');
            } else {
                $this->fillMessage('error', 'Email incorrect !');
            }
        } else {
            $this->fillMessage('error', 'C\'est pas un email ça ?!');
        }
        header('Location: /user/login');
    }

    public function getResetPwdForm(int $id_user, string $hash): void
    {
        if ($id_user > 0 && $hash != "") {
            if ($user = $this->userRepository->getUniqueById($id_user)) {
                if ($user->getPwd() == $hash) {
                    $access = true;
                } else {
                    $this->fillMessage('error', 'Lien corrompu !');
                    $access = false;
                    header('Location: /');
                }
            } else {
                $this->fillMessage('error', 'Utilisateur introuvable !');
                $access = false;
            }
        } else {
            $this->fillMessage('error', 'Un problème est survenue !');
            $access = false;
        }

        if ($access && !empty($_POST)) {
            $this->resetPwdpostProcess($user, $_POST);
        } elseif ($access) {
            $inputs = [
                'pwd' => [
                    'label' => 'Nouveau mot de passe',
                    'type' => 'password',
                    'value' => ""
                ],
                'confirm' => [
                    'label' => 'Confirmation',
                    'type' => 'password',
                    'value' => ""
                ]
            ];
            echo $this->twig->render('/front/user/reset-pwd-form.html.twig', [
                "title" => "Réinitialisation du mot de passe",
                "inputs" => $inputs
            ]);
        }
    }

    protected function resetPwdpostProcess(User $user, array $post_data): void
    {
        $success = true;
        if (!empty($post_data['pwd'])) {
            if (strlen($post_data['pwd']) >= 5) {
                $new_pwd = password_hash($post_data['pwd'], PASSWORD_DEFAULT);
            } else {
                $this->fillMessage('error', 'Mot de passe trop court : 5 caractère minimum');
                $success = false;
            }
            if ($post_data['confirm'] !== $post_data['pwd']) {
                $this->fillMessage('error', 'La confirmation et le mot de passe ne correspondent pas !');
                $success = false;
            }
        } else {
            $this->fillMessage('error', 'Le champ Mot de passe ne peut pas être vide !');
            $success = false;
        }

        if ($success) {
            $user->setPwd($new_pwd);
            $this->userManager->save($user);
            $this->fillMessage('success', 'Nouveau mot de passe enregistré !');

            header('Location: /user/login');
        } else {
            header('Refresh:0');
        }
    }






    protected function sendResetPwdEmail(User $user): bool
    {
        $to      = $user->getEmail();
        $subject = 'Réinitialisation de votre mot de passe';
        $message = 'Bonjour,' . "\r\n" . 'Cliquez sur le lien ci-dessous pour réinitilaiser votre mot de passe' . "\r\n" . $_SERVER['SERVER_NAME'] . '/user/reset-pwd/hash/' . $user->getPwd() . '/id_user/' . $user->getId();
        $headers = 'From: no-reply@sitez-vous.com' . "\r\n" .
            'Reply-To: contact@sitez-vous.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }
}
