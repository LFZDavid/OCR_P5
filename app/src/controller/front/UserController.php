<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;

class UserController extends Controller
{



    /**
     *
     * @param integer $id_user
     * @return void
     */
    public function getForm(int $id_user)
    {

        if ($id_user == 0) {
            $user = new User();
            $title = "Inscription";
        } else {
            $user = $this->repository->getUniqueById($id_user);
            $title = "Modification";
        }

        if (!empty($_POST)) {
            $this->postProcess($_POST, $user);
        }

        $inputs = [

            'name' => [
                'label' => 'Pseudo',
                'type' => 'text',
                'value' => ""
            ],
            'email' => [
                'label' => 'Email',
                'type' => 'email',
                'value' => ""
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
            "message" => $this->message
        ]);
    }


    public function postProcess(array $data, User $user = null)
    {

        $user_data = [];
        // Validation
        foreach ($data as $key => $value) {
            if ($key == 'name') {
                if (strlen($value) > 4) {
                    if ($this->isNameAvailable($value)) {
                        $user_data['name'] = $this->checkInput($value);
                    } else {
                        $this->fillMessage('error', 'Ce pseudo n\'est pas disponible!');
                    }
                } else {
                    $this->fillMessage('error', 'Pseudo trop court : 5 caractère minimum.');
                }
            } elseif ($key == 'email') {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    if ($this->isEmailAvailable($value)) {
                        $user_data['email'] = $this->checkInput($value);
                    } else {
                        $this->fillMessage('error', 'Cet email est déjà utilisé !');
                    }
                } else {
                    $this->fillMessage('error', "L'adresse email '$value' n'est pas valide.");
                }
            } elseif ($key == 'pwd') {
                if (strlen($value) >= 6) {
                    $user_data['pwd'] = password_hash($value, PASSWORD_DEFAULT);
                    //check with password_verify($_POST['pwd'], $user->pwd()) : bool;
                } else {
                    $this->fillMessage('error', 'Mot de passe trop court : 6 caractère minimum');
                }
            } elseif ($key == 'confirm') {
                if ($value !== $data['pwd']) {
                    $this->fillMessage('error', 'La confirmation et le mot de passe ne correspondent pas !');
                }
            } else {
                $this->fillMessage('error', 'Le champ ' . $key . ' est inconnu !');
            }
        }

        if (
            !in_array('error', $this->message)
            && $user_data != []
        ) {
            $user->setName($user_data['name'])
                ->setEmail($user_data['email'])
                ->setPwd($user_data['pwd'])
                ->setRole('user');

            $persisted_id = $this->manager->save($user);

            $this->fillMessage('success', 'Utilisateur enregistré !');
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
}
