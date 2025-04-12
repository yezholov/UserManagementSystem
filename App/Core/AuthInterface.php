<?php

namespace App\Core;

interface AuthInterface
{
    public function login($name, $password);
    public function logout();
}
