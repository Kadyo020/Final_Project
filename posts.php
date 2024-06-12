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
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 20px;
            padding: 20px;
        }

        .post-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .post-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .post-item h3 {
            margin-top: 0;
            font-size: 18px;
            color: #333;
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
        <div class="posts-container" id="postLists">
            <?php
                require 'config.php';

                if (!isset($_SESSION['user_id'])) {
                    header("Location: index.php");
                    exit;
                }

                $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

                try {
                    $pdo = new PDO($dsn, $user, $password, $options);

                    if ($pdo) {
                        $user_id = $_SESSION['user_id'];

                        $query = "SELECT * FROM `posts` WHERE user_id = :id LIMIT 10";
                        $statement = $pdo->prepare($query);
                        $statement->execute([':id' => $user_id]);

                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($rows as $index => $row) {
                            $color = generateUniqueColor($index);
                            echo '<div class="post-item" onclick="window.location.href=\'post.php?id=' . $row['id'] . '\'" style="background-color:' . $color . '">';
                            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                            echo '<p>' . htmlspecialchars($row['body']) . '</p>';
                            echo '</div>';
                        }
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

                function generateUniqueColor($index) {
                    $hue = ($index * 137.508) % 360;
                    return "hsl($hue, 70%, 90%)";
                }
            ?>
        </div>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>
    <script>
        function logout() {
            window.location.href = "logout.php";
        }
    </script>
</body>
</html>
