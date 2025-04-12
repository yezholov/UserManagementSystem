<?php
require 'autoload.php';

use App\Models\Admin;
use App\Models\RegularUser;
use App\Services\AuthService;
use App\Services\DataBase;

// Start session to check if user is logged in
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

$db = new DataBase();
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new user
    if (isset($_POST['action']) && $_POST['action'] === 'add' && isset($_POST['new_name']) && isset($_POST['new_password'])) {
        $result = $db->createUser($_POST['new_name'], $_POST['new_password']);
        if ($result === 'okay') {
            $message = 'User added successfully!';
        } else {
            $message = 'Error adding user: ' . $result;
        }
    }
    
    // Edit user
    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['edit_id']) && isset($_POST['edit_name']) && isset($_POST['edit_password'])) {
        $result = $db->updateUser($_POST['edit_id'], $_POST['edit_name'], $_POST['edit_password']);
        if ($result === 'okay') {
            $message = 'User updated successfully!';
        } else {
            $message = 'Error updating user: ' . $result;
        }
    }
    
    // Delete user
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['delete_id'])) {
        // We need to add a deleteUser method to the DataBase class
        // For now, we'll use a direct SQL query
        try {
            $sql = "DELETE FROM users WHERE id = " . intval($_POST['delete_id']);
            $db->getConnection()->exec($sql);
            $message = 'User deleted successfully!';
        } catch (\Exception $e) {
            $message = 'Error deleting user: ' . $e->getMessage();
        }
    }
}

$table = $db->getUsers();

// Display welcome message
echo "Welcome, " . htmlspecialchars($_SESSION['user']) . "!<br><br>";

// Display message if any
if (!empty($message)) {
    echo "<div style='color: " . (strpos($message, 'Error') === 0 ? 'red' : 'green') . "; margin-bottom: 10px;'>" . htmlspecialchars($message) . "</div>";
}

// Add new user form
echo "<div style='margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 5px;'>";
echo "<h3>Add New User</h3>";
echo "<form method='post' action='index.php'>";
echo "<input type='hidden' name='action' value='add'>";
echo "<table>";
echo "<tr><td>Name:</td><td><input type='text' name='new_name' required></td></tr>";
echo "<tr><td>Password:</td><td><input type='password' name='new_password' required></td></tr>";
echo "</table>";
echo "<input type='submit' value='Add User' style='margin-top: 10px;'>";
echo "</form>";
echo "</div>";

// Display user list with edit and delete options
echo "<h3>User List</h3>";
echo "<table border='1' cellpadding='5' style='width: 100%; border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Name</th><th>Actions</th></tr>";

for ($i = 0; $i < count($table); $i++) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($table[$i]['id']) . "</td>";
    echo "<td>" . htmlspecialchars($table[$i]['name']) . "</td>";
    echo "<td>";
    
    // Edit button
    echo "<form method='post' action='index.php' style='display: inline;'>";
    echo "<input type='hidden' name='action' value='edit'>";
    echo "<input type='hidden' name='edit_id' value='" . htmlspecialchars($table[$i]['id']) . "'>";
    echo "<input type='text' name='edit_name' value='" . htmlspecialchars($table[$i]['name']) . "' required>";
    echo "<input type='password' name='edit_password' placeholder='New password' required>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
    
    // Delete button
    echo "<form method='post' action='index.php' style='display: inline;' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>";
    echo "<input type='hidden' name='action' value='delete'>";
    echo "<input type='hidden' name='delete_id' value='" . htmlspecialchars($table[$i]['id']) . "'>";
    echo "<input type='submit' value='Delete'>";
    echo "</form>";
    
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<br>";
echo "<a href='logout.php'>Logout</a>";