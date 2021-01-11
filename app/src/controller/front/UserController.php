<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;

class UserController extends Controller
{
    /**
     *
     * @return void
     */
    public function getForm()
    {
        $id_user = $_SESSION['id_user'] ?? false;

        if (!$id_user) {
            // Create user
            $user = new User();
            $title = "Inscription";
            $edit = false;
        } else {
            // Edit user
            $user = $this->repository->getUniqueById($id_user);
            $title = "Modification";
            $edit = true;
            $change_pwd_link = 'index.php?user=reset-pwd&hash=' . $user->getPwd() . '&id_user=' . $user->getId();
        }

        if (!empty($_POST)) {
            $this->postProcess($edit, $_POST, $user);
        }

        if (!$edit) {

            $inputs = [

                'name' => [
                    'label' => 'Pseudo',
                    'type' => 'text',
                    'value' => $edit ? $user->getName() : ""
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => $edit ? $user->getEmail() : ""
                ],
                'pwd' => [
                    'label' => 'Mot de passe',
                    'type' => 'password',
                    'value' => ""
                ],
                'confirm' => [
                    'label' => 'Confirmation',
                    'type' => 'password',
                    'value' => ""
                ]
            ];
        } else {
            $inputs = [
                'name' => [
                    'label' => 'Pseudo',
                    'type' => 'text',
                    'value' => $edit ? $user->getName() : ""
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => $edit ? $user->getEmail() : ""
                ]
            ];
        }

        echo $this->twig->render('/front/user/form.html.twig', [
            "title" => $title,
            "inputs" => $inputs,
            "edit" => $edit,
            "id_user" => $user->getId(),
            "change_pwd_link" => $edit ? $change_pwd_link : "#",
            "messages" => $this->messages
        ]);
    }


    /**
     * Form data treatment
     *
     * @param array $data
     * @param User $user
     * @return void
     */
    public function postProcess(bool $edit, array $data, User $user = null)
    {
        $user_data = [];
        $success = true;
        // Validation
        foreach ($data as $key => $value) {
            if ($key == 'name') {
                if (strlen($value) > 4) {
                    if (!$edit || $user->getName() != $value) {
                        if ($this->isNameAvailable($value)) {
                            $user_data['name'] = $this->checkInput($value);
                        } else {
                            $this->fillMessage('error', 'Ce pseudo n\'est pas disponible!');
                            $success = false;
                        }
                    } else {
                        $user_data['name'] = $user->getName();
                    }
                } else {
                    $this->fillMessage('error', 'Pseudo trop court : 5 caractère minimum.');
                    $success = false;
                }
            } elseif ($key == 'email') {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    if (!$edit || $user->getEmail() != $value) {
                        if ($this->isEmailAvailable($value)) {
                            $user_data['email'] = $this->checkInput($value);
                        } else {
                            $this->fillMessage('error', 'Cet email est déjà utilisé !');
                            $success = false;
                        }
                    } else {
                        $user_data['email'] = $user->getEmail();
                    }
                } else {
                    $this->fillMessage('error', "L'adresse email '$value' n'est pas valide.");
                    $success = false;
                }
            } elseif ($key == 'pwd' && !$edit) {
                if (strlen($value) >= 5) {
                    $user_data['pwd'] = password_hash($value, PASSWORD_DEFAULT);
                } else {
                    $this->fillMessage('error', 'Mot de passe trop court : 5 caractère minimum');
                    $success = false;
                }
            } elseif ($key == 'confirm' && !$edit) {
                if ($value !== $data['pwd']) {
                    $this->fillMessage('error', 'La confirmation et le mot de passe ne correspondent pas !');
                    $success = false;
                }
            } elseif (!$edit) {
                $this->fillMessage('error', 'Le champ ' . $key . ' est inconnu !');
                $success = false;
            }
        }

        if ($success && !empty($user_data)) {
            $user->setName($user_data['name'])->setEmail($user_data['email']);

            if (!$edit) {
                $user->setPwd($user_data['pwd']);
            }

            $this->manager->save($user);
            $this->fillMessage('success', 'Utilisateur enregistré !');

            if (!$edit) {
                $this->logIn([
                    'name' => $user->getName(),
                    'pwd' => $data['pwd']
                ]);
            } else {
                $this->logIn(['name' => $user->getName()], true);
            }
        }
    }


    /**
     * Display Login form
     *
     * @return void
     */
    public function getLogInForm()
    {
        if (isset($_SESSION['id_user'])) {
            header('Location:index.php');
        }

        if (isset($_POST) && !empty($_POST)) {
            $this->logIn($_POST);
        }

        $inputs = [
            'name' => [
                'label' => 'Pseudo',
                'type' => 'text',
                'value' => ""
            ],
            'pwd' => [
                'label' => 'Mot de passe',
                'type' => 'password',
                'value' => ""
            ]
        ];

        $lost_pwd_form = [
            'name' => 'email_user',
            'label' => 'Email',
            'type' => 'email',
            'value' => ""
        ];


        echo $this->twig->render('/front/user/login-form.html.twig', [
            "title" => "Connexion",
            "inputs" => $inputs,
            "lost_pwd_form" => $lost_pwd_form,
            "messages" => $this->messages
        ]);
    }

    /**
     * LogIn form treatment
     *
     * @param array $post_data
     * @return void
     */
    protected function logIn(array $post_data, $force = false)
    {
        $success = !empty($post_data) ? true : false;

        if ($user = $this->repository->getUniqueByName($this->checkInput($post_data['name']))) {
            if (!password_verify($post_data['pwd'], $user->getPwd()) && !$force) {
                $this->fillMessage('error', 'Pseudo ou mot de passe incorrect !');
                $success = false;
            }
        } else {
            $this->fillMessage('error', 'Pseudo ou mot de passe incorrect !');
            $success = false;
        }

        if ($success) {
            $this->fillMessage('success', 'Vous êtes connecté !');
            $_SESSION['id_user'] = $user->getId();
            $_SESSION['name_user'] = $user->getName();
            $_SESSION['role_user'] = $user->getRole();
            $_SESSION['messages'] = $this->messages;
            header('Location: index.php');
        }
    }

    /**
     * Destroy session
     *
     * @return void
     */
    public function logOut()
    {
        session_destroy();
        header('Location: index.php');
    }

    /**
     * lost password form treatment
     *
     * @return void
     */
    public function lostPwdProcess()
    {

        if (isset($_POST['email_user'])) {
            $email_user = $this->checkInput($_POST['email_user']);
            if ($user = $this->repository->getUniqueByEmail($email_user)) {
                $this->sendResetPwdEmail($user);
                $this->fillMessage('success', 'Un email vient de vous être envoyé !');
            } else {
                $this->fillMessage('error', 'Email incorrect !');
            }
        } else {
            $this->fillMessage('error', 'C\'est pas un email ça ?!');
        }

        header('Location: index.php?user=login');
    }

    /**
     * Display reset password form
     *
     * @param integer $id_user
     * @param string $hash
     * @return void
     */
    public function getResetPwdForm(int $id_user, string $hash)
    {
        if ($id_user > 0 && $hash != "") {
            if ($user = $this->repository->getUniqueById($id_user)) {
                if ($user->getPwd() == $hash) {
                    $access = true;
                } else {
                    $this->fillMessage('error', 'Lien corrompu !');
                    $access = false;
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
                "inputs" => $inputs,
                "messages" => $this->messages
            ]);
        }
    }

    /**
     * Reset password form treatment
     *
     * @param User $user
     * @param array $post_data
     * @return void
     */
    protected function resetPwdpostProcess(User $user, array $post_data)
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
            $this->manager->save($user);
            $this->fillMessage('success', 'Nouveau mot de passe enregistré !');

            $this->logIn([
                'name' => $user->getName(),
                'pwd' => $post_data['pwd']
            ]);
        } else {
            header('Refresh:0');
        }
    }

    /**
     * Test if username is available
     *
     * @param string $name
     * @return boolean
     */
    protected function isNameAvailable(string $name): bool
    {
        $user = $this->repository->getUniqueByName($name);
        return $user ? false : true;
    }

    /**
     * Test if email is available
     *
     * @param string $email
     * @return boolean
     */
    protected function isEmailAvailable(string $email): bool
    {
        $user = $this->repository->getUniqueByEmail($email);
        return $user ? false : true;
    }

    /**
     * Send an email to the user with special link
     *
     * @param User $user
     * @return bool
     */
    protected function sendResetPwdEmail(User $user): bool
    {
        $to      = $user->getEmail();
        $subject = 'Réinitialisation de votre mot de passe';
        $message = 'Bonjour,' . "\r\n" . 'Cliquez sur le lien ci-dessous pour réinitilaiser votre mot de passe' . "\r\n" . $this->getCurrentUrl() . '?user=reset-pwd&hash=' . $user->getPwd() . '&id_user=' . $user->getId();
        $headers = 'From: no-reply@sitez-vous.com' . "\r\n" .
            'Reply-To: contact@sitez-vous.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }

    /**
     *
     * @return string
     */
    protected function getCurrentUrl(): string
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https";
        } else {
            $url = "http";
        }
        $url .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        return $url;
    }
}
