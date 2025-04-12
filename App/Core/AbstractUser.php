<?php

namespace App\Core;

abstract class AbstractUser
{
    protected $name;
    protected $password;
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_DEFAULT); //Hashing password
    }
    public function getName()
    {
        return $this->name;
    }

    // Force child classes to implement userRole()
    abstract public function userRole();
}
