<?php

namespace App\Model\Entity;

use \App\Model\Entity\Entity;

class Category extends Entity
{
    protected string $name;

    //GETTERS
    public function getName()
    {
        return $this->name;
    }

    //SETTERS
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
}
