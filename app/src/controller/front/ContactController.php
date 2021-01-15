<?php

namespace App\Controller\Front;

use App\Controller\Controller;
use App\Model\Entity\User;

class ContactController extends Controller
{
    /**
     * Get contact form
     *
     * @param string $admin_email
     * @return void
     */
    public function getContactForm(string $admin_email)
    {
        $title = "Me contacter";
        $id_user = $_SESSION['id_user'] ?? false;
        $user = $id_user ? $this->repository->getUniqueById($id_user) : null;

        if (!empty($_POST)) {
            $this->postProcess($_POST, $user, $admin_email);
        }

        $inputs = [
            'name' => [
                'label' => 'Pseudo',
                'name' => 'name',
                'type' => 'text',
                'value' => $user != null ? $user->getName() : '',
                'placeholder' => 'Votre pseudo'
            ],
            'email' => [
                'label' => 'email',
                'name' => 'email',
                'type' => 'text',
                'value' => $user != null ? $user->getEmail() : '',
                'placeholder' => 'Votre e-mail'
            ],
            'content' => [
                'label' => 'content',
                'name' => 'content',
                'type' => 'text',
                'placeholder' => 'Votre message...'
            ]
        ];


        return [
            "title" => $title,
            "inputs" => $inputs,
            "user_logged" => $id_user > 0,
            "messages" => $this->messages
        ];
    }

    public function postProcess(array $post_data, User $user = null, string $admin_email)
    {
        $success = true;

        foreach ($post_data as $key => $value) {
            if (empty($post_data[$key])) {
                $this->fillMessage('error', 'Le champ ' . ucfirst($key) . ' ne doit pas être vide !');
                $success = false;
            } else {
                if ($user != null && $key != 'content') {
                    $method = 'get' . ucfirst($key);
                    $mail_data[(string)$key] = $user->$method($value);
                } else {
                    $mail_data[(string)$key] = $this->checkInput($value);
                }
            }
        }

        $headers = 'From:' . $mail_data['email'] . "\r\n" .
            'Reply-To: ' . $mail_data['email'] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        if ($success) {
            mail($admin_email, 'Un message de la part de ' . $mail_data['name'], $mail_data['content'], $headers);
        }
    }
}
