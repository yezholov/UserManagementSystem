<?php

namespace App\Services;

class DataBase
{
    private $conn;

    public function __construct()
    {
        try {
            require __DIR__ . '/../config.php';

            if (!isset($address) || !isset($database) || !isset($user) || !isset($pass)) {
                throw new \Exception("Database configuration variables are not properly set");
            }

            $this->conn = new \PDO("mysql:host=$address;dbname=$database", $user, $pass);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function createUser($name, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = ("INSERT INTO users (name, password) VALUES ('" . $name . "', '" . $hash . "')");
        if ($this->conn->query($sql) == TRUE) {
            return 'okay';
        } else {
            return 'error';
        }
    }
    public function loginUser($name, $password)
    {
        if (empty($name) || empty($password)) {
            return 'error: name or password is empty';
        }
        $sql = "SELECT * FROM users WHERE name = '" . $name . "'";
        $result = $this->conn->query($sql);
        
        if ($result && $result->rowCount() > 0) {
            $row = $result->fetch(\PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                return 'okay';
            } else {
                return 'error: Wrong password';
            }
        } else {
            return 'error: User not found';
        }
    }
    public function getUsers()
    {
        $sql = "SELECT `id`, `name` FROM users";
        if ($this->conn->query($sql) == TRUE) {
            $result = $this->conn->query($sql);
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return 'error: Not found user';
        }
    }

    public function updateUser($id, $name, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET name = '" . $name . "', password = '" . $hash . "' WHERE id = " . $id;
        if ($this->conn->query($sql) == TRUE) {
            return 'okay';
        } else {
            return 'error: Not found user';
        }
    }
}
