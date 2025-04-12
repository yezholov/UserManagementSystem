<?php

namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;
use App\Services\DataBase;
class RegularUser extends AbstractUser implements AuthInterface
{
    private $conn;
    public function __construct(DataBase $db)
    {
        $this -> conn = $db;
    }
    public function userRole()
    {
        return "Regular User";
    }
    public function login($name, $password)
    {
        $this -> conn -> loginUser($name, $password);
    }
    public function logout()
    {
        return "User logged out.";
    }
}
