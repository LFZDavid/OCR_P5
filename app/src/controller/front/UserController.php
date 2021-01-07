<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;

class UserController extends Controller
{

    public function getForm(int $id_user)
    {
        // if (isset($_POST)) {
        //     $message = $this->postProcess($_POST);
        // }

        if ($id_user == 0) {
            $user = new User();
            $title = "Inscription";
        } else {
            $user = $this->repository->getUniqueById($id_user);
            $title = "Modification";
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
        "id_user" => $user->id() ?? 0,
        "message" => $message??"" // chargé après traitement du formulaire
    ]);

    }

}