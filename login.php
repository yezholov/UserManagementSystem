<?php
require 'autoload.php';

use App\Services\DataBase;

// Check if login form was submitted
if (isset($_GET['name']) && isset($_GET['password'])) {
    $name = $_GET['name'];
    $password = $_GET['password'];
    
    $db = new DataBase();
    $result = $db->loginUser($name, $password);
    
    if ($result === 'okay') {
        // Successful login
        session_start();
        $_SESSION['user'] = $name;
        header('Location: index.php');
        exit;
    } else {
        // Failed login
        $error_message = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <style>
        body {
            width: 60vw;
            max-width: 450px;
            margin: 0 auto;
            margin-top: 120px;
            font-size: 1.2em;
            font-family: sans-serif;
        }
        p.title{
            text-align: center;
            font-size: 1.5em;
            margin-block: 0;
            margin-bottom: 10px;
        }
        input,
        table {
            width: 100%;
        }

        td.inputTbl {
            width: 100%;
        }

        td.inputTbl input {
            width: calc(100% - 5px);
        }

        input#submitForm {
            width: 100%;
            background-color: white;
            border: 1px solid gray;
            font-size: 1.3em;
            padding: 5px;
            margin-top: 10px;
            border-radius: 5px;
            transition: .15s linear;
        }
        input#submitForm:hover{
            background-color:#459BE6af;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <p class="title">Login</p>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="login.php" method="get">
        <table>
            <tr>
                <td class="label">Name:</td>
                <td class="inputTbl"><input type="text" name="name" id="name" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>"></td>
            </tr>
            <tr>
                <td class="label">Password:</td>
                <td class="inputTbl"><input type="password" name="password" id="password"></td>
            </tr>
        </table>
        <input type="submit" value="Send" id="submitForm">
    </form>
</body>

</html>