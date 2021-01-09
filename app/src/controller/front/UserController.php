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

        $id_user = false;
        // todo récupérer l'id user dans SESSION

        if (!$id_user) {
            $user = new User();
            $title = "Inscription";
            $edit = false;
        } else {
            $user = $this->repository->getUniqueById($id_user);
            $title = "Modification";
            $edit = true;
        }

        if (!empty($_POST)) {
            $this->postProcess($_POST, $user);
        }

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

        echo $this->twig->render('/front/user/form.html.twig', [
            "title" => $title,
            "inputs" => $inputs,
            "id_user" => $user->getId() ?? 0,
            "messages" => $this->messages
        ]);
    }


    public function postProcess(array $data, User $user = null)
    {

        $user_data = [];
        $success = true;
        // Validation
        foreach ($data as $key => $value) {
            if ($key == 'name') {
                if (strlen($value) > 4) {
                    if ($this->isNameAvailable($value)) {
                        $user_data['name'] = $this->checkInput($value);
                    } else {
                        $this->fillMessage('error', 'Ce pseudo n\'est pas disponible!');
                        $success = false;
                    }
                } else {
                    $this->fillMessage('error', 'Pseudo trop court : 5 caractère minimum.');
                    $success = false;
                }
            } elseif ($key == 'email') {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    if ($this->isEmailAvailable($value)) {
                        $user_data['email'] = $this->checkInput($value);
                    } else {
                        $this->fillMessage('error', 'Cet email est déjà utilisé !');
                        $success = false;
                    }
                } else {
                    $this->fillMessage('error', "L'adresse email '$value' n'est pas valide.");
                    $success = false;
                }
            } elseif ($key == 'pwd') {
                if (strlen($value) >= 5) {
                    $user_data['pwd'] = password_hash($value, PASSWORD_DEFAULT);
                    //check with password_verify($_POST['pwd'], $user->pwd()) : bool;
                } else {
                    $this->fillMessage('error', 'Mot de passe trop court : 5 caractère minimum');
                    $success = false;
                }
            } elseif ($key == 'confirm') {
                if ($value !== $data['pwd']) {
                    $this->fillMessage('error', 'La confirmation et le mot de passe ne correspondent pas !');
                    $success = false;
                }
            } else {
                $this->fillMessage('error', 'Le champ ' . $key . ' est inconnu !');
                $success = false;
            }
        }

        if ($success && !empty($user_data)) {
            $user->setName($user_data['name'])
                ->setEmail($user_data['email'])
                ->setPwd($user_data['pwd'])
                ->setRole('user');

            $persisted_id = $this->manager->save($user);

            $this->fillMessage('success', 'Utilisateur enregistré !');
        }
    }


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

        echo $this->twig->render('/front/user/login-form.html.twig', [
            "title" => "Connexion",
            "inputs" => $inputs,
            "messages" => $this->messages
        ]);
    }

    protected function logIn(array $post_data)
    {
        $success = !empty($post_data) ? true : false;

        if ($user = $this->repository->getUniqueByName($this->checkInput($post_data['name']))) {
            if (!password_verify($post_data['pwd'], $user->getPwd())) {
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
            $_SESSION['username'] = $user->getName();
            $_SESSION['messages'] = $this->messages;
            header('Location: index.php');
        }
    }

    public function logOut()
    {
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
}
