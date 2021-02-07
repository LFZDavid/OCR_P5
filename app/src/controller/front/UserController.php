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
            $edit = false;
        } else {
            $edit = true;
            $changePwdLink = '/user/reset-pwd/hash/' . $user->getPwd() . '/userId/' . $user->getId();
        }

        if (!empty($_POST)) {
            $data = $_POST;
            $errors = $this->postProcess($edit, $data, $user);
        }

        echo $this->twig->render('/front/user/form.html.twig', [
            "edit" => $edit,
            "user" => $user,
            "errors" => $errors ?? [],
            "change_pwd_link" => $changePwdLink ?? "#"
        ]);
    }

    private function postProcess(bool $edit, array $data, User $user = null): array
    {
        $errors = $this->userValidator->validFormData($user, $data);
        if (!empty($errors)) {
            return $errors;
        }
        $user->setName($this->cleanValue($data['name']))
            ->setEmail($this->cleanValue($data['email']))
            ->setRole('user');
        if (!$edit) {
            $hashedPwd = password_hash($data['pwd'], PASSWORD_DEFAULT);
            $user->setPwd($hashedPwd);
        }
        $persistedId = $this->userManager->save($user);
        $user = $this->userRepository->getUniqueById($persistedId);
        $_SESSION['app.user'] = $user;

        $this->fillMessage('success', 'Utilisateur enregistré !');
        $redirectLocation = isset($data['last_url'])?$data['last_url']:'/';
        header('Location: '.$redirectLocation);
    }

    public function getLogInForm(): void
    {
        if ($this->getUser()) {
            $this->displayError(404);
            return;
        }

        if (!empty($_POST)) {
            $data = $_POST;
            $errors = $this->logIn($data);
        }

        echo $this->twig->render('/front/user/login-form.html.twig', [
            "title" => "Connexion",
            "errors" => $errors ?? []
        ]);
    }

    protected function logIn(array $postData, bool $force = false): ?array
    {
        $validationReturns = $this->userValidator->validLoginForm($postData, $force);
        if (isset($validationReturns['errors'])) {
            return $validationReturns['errors'];
        }
        $this->fillMessage('success', 'Vous êtes connecté !');
        $_SESSION['app.user'] = $validationReturns['user'];

        $redirectLocation = isset($postData['last_url'])?$postData['last_url']:'/';
        header('Location: '.$redirectLocation);
    }

    public function logOut(): void
    {
        session_destroy();
        header('Location: /');
    }

    public function lostPwdProcess(): void
    {
        $userEmail = isset($_POST['email']) ? stripslashes($_POST['email']) : $this->getUser()->getEmail();

        $validationReturns = $this->userValidator->validUserEmail($userEmail);
        if (isset($validationReturns['errors'])) {
            $this->fillMessage('error', $validationReturns['errors']['email']);
        } else {
            $this->sendResetPwdEmail($validationReturns['user']);
            $this->fillMessage('success', 'Un email vient de vous être envoyé !');
        }

        $destination = $this->getUser()?'form':'login';
        header('Location: /user/'. $destination);
    }

    public function getResetPwdForm(int $userId, string $hash): void
    {
        $user = $this->userRepository->getUniqueById($userId);
        if (
            !$user
            || $hash == ''
            || $user->getPwd() !== $hash
        ) {
            $this->fillMessage('error', ' Lien corrompus ! ');
            header('Location: /user/login');
        }

        if (!empty($_POST)) {
            $formData = $_POST;
            $errors = $this->resetPwdpostProcess($user, $formData);
        }

        echo $this->twig->render('/front/user/reset-pwd-form.html.twig', [
            "title" => "Réinitialisation du mot de passe",
            "errors" => $errors ?? []
        ]);
    }

    protected function resetPwdpostProcess(User $user, array $data): ?array
    {
        $errors = $this->userValidator->validFormData($user, $data);
        if (!empty($errors)) {
            return $errors;
        }

        $new_pwd = password_hash($data['pwd'], PASSWORD_DEFAULT);
        $user->setPwd($new_pwd);
        $this->userManager->save($user);
        $this->fillMessage('success', 'Nouveau mot de passe enregistré !');
        header('Location: /user/login');
    }

    protected function sendResetPwdEmail(User $user): bool
    {
        if (!isset($_SERVER['SERVER_NAME'])) {
            return false;
        }
        $to      = $user->getEmail();
        $subject = 'Réinitialisation de votre mot de passe';
        $message = 'Bonjour,' . "\r\n" . 'Rendez-vous à l\'url ci-dessous pour réinitilaiser votre mot de passe' . "\r\n" . $_SERVER['SERVER_NAME'] . '/user/reset-pwd/hash/' . $user->getPwd() . '/userId/' . $user->getId();
        $headers = 'From: no-reply@sitez-vous.com' . "\r\n" .
            'Reply-To: contact@sitez-vous.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }
}
