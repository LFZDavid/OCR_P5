<?php

namespace App\Model\Validator;

abstract class Validator
{
    protected array $errorMessages = [];

    protected function fillMessage(string $inputName, string $message)
    {
        if ($message != '') {
            $this->errorMessages[(string)$inputName] = $message;
        }
    }
}
