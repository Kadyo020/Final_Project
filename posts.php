<?php
require 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .posts-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Adjusted min-width */
            grid-gap: 20px;
            padding: 20px;
        }

        .post-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff; /* Added background color */
            color: #000; /* Default text color */
        }

        .post-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .post-item h3 {
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 10px; /* Added spacing */
        }

        .post-item a {
            text-decoration: none;
            transition: color 0.3s;
        }

        .logout-btn {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            margin: 20px auto;
            font-size: 16px; /* Increased font size */
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <header>
        <h1>Posts Page</h1>
    </header>
    <div class="container">
        <div class="posts-container">
            <?php
            $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            try {
                $pdo = new PDO($dsn, $user, $password, $options);

                if ($pdo) {
                    $user_id = $_SESSION['user_id'];

                    $query = "SELECT * FROM `posts` WHERE user_id = :id";
                    $statement = $pdo->prepare($query);
                    $statement->execute([':id' => $user_id]);

                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $random_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Generate random color
                        // Calculate the luminance of the background color
                        $luminance = 0.2126 * hexdec(substr($random_color, 1, 2)) + 0.7152 * hexdec(substr($random_color, 3, 2)) + 0.0722 * hexdec(substr($random_color, 5, 2));
                        // Set text color based on luminance
                        $text_color = ($luminance > 128) ? '#000' : '#fff';
                        echo '<div class="post-item" style="background-color: ' . $random_color . '; color: ' . $text_color . '">';
                        echo '<h3><a href="post.php?id=' . $row['id'] . '">' . $row['title'] . '</a></h3>';
                        // You can include more details if needed
                        echo '</div>';
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <script>
        function logout() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>
