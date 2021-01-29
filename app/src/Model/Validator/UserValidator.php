<?php

namespace App\Model\Validator;

use App\Model\Validator\Validator;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;

class UserValidator extends Validator
{
    private UserRepository $userRepository;
    private ?User $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validFormData(?User $user, array $formData): array
    {
        $this->user = $user ?? new User();
        foreach ($formData as $inputName => $inputValue) {

            if ($inputName == 'name') {
                $this->validName($inputValue);
            }
            if ($inputName == 'email') {
                $this->validEmail($inputValue);
            }
            if ($inputName == 'pwd') {
                $this->validPwd($inputValue);
            }
            if ($inputName == 'confirm') {
                $this->validConfirm($inputValue, $formData['pwd']);
            }
        }
        return $this->errorMessages;
    }

    private function validName(string $value): void
    {
        $messages = '';
        if (strlen($value) < 5) {
            $messages .= 'Pseudo trop court : 5 caractères minimum. <br>';
        }
        if ($this->user->getName() != $value && !$this->isNameAvailable($value)) {
            $messages .= 'Ce pseudo n\'est pas disponible! <br>';
        }
        $this->fillMessage('name', $messages);
    }

    private function validEmail(string $value): void
    {
        $messages = '';
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $messages .= "L'adresse email '$value' n'est pas valide. <br>";
        }
        if ($this->user->getEmail() != $value && !$this->isEmailAvailable($value)) {
            $messages .= 'Cet email est déjà utilisé ! <br>';
        }
        $this->fillMessage('email', $messages);
    }

    private function validPwd(string $value)
    {
        $messages = '';
        if (strlen($value) < 5) {
            $messages .= 'Mot de passe trop court : 5 caractères minimum. <br>';
        }

        $this->fillMessage('pwd', $messages);
    }

    private function validConfirm(string $value, string $pwd)
    {
        $messages = '';
        if ($value !== $pwd) {
            $messages .= 'La confirmation et le mot de passe ne correspondent pas ! <br>';
        }
        $this->fillMessage('confirm', $messages);
    }

    private function isNameAvailable(string $name): bool
    {
        $user = $this->userRepository->getUniqueByName($name);
        return $user ? false : true;
    }

    protected function isEmailAvailable(string $email): bool
    {
        $user = $this->userRepository->getUniqueByEmail($email);
        return $user ? false : true;
    }
}
