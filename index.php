<?php

require 'config.php';

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $password, $options);

    if ($pdo) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $query = "SELECT * FROM `users` WHERE username = :username";
            $statement = $pdo->prepare($query);
            $statement->execute([':username' => $username]);

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ('secret123' === $password) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    header("Location: posts.php");
                    exit;
                } else {
                    echo "Invalid password!";
                }
            } else {
                echo "User not found!";
            }
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
        }

        .login-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
        }

        .login-header {
            background: linear-gradient(to right, #007bff, #0062cc);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .login-header h2 {
            margin: 0;
            font-weight: normal;
        }

        .login-form {
            padding: 20px;
        }

        .form-input {
            margin-bottom: 20px;
            width: 100%;
            border: none;
            border-bottom: 2px solid #007bff;
            outline: none;
            padding: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-bottom: 2px solid #0062cc;
        }

        .form-button {
            width: 100%;
            background: linear-gradient(to right, #007bff, #0062cc);
            color: #fff;
            border: none;
            outline: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Login</h2>
        </div>
        <form class="login-form" id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name = "username" id="username" class="form-input" placeholder="Enter username" required>
            <input type="password" name = "password" id="password" class="form-input" placeholder="Enter password" required>
            <button type="submit" id="submit" class="form-button">Login</button>
        </form>
    </div>
</body>
</html>
