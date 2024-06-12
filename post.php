<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .post-container {
            max-width: 800px;
            padding: 30px;
            border-radius: 15px;
            background: linear-gradient(135deg, #ff6b6b, #556270);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        h1, h3 {
            color: #fff;
            text-align: center;
        }

        p {
            color: #f0f0f0;
            line-height: 1.6;
        }

        .loading {
            text-align: center;
            font-style: italic;
            color: #ddd;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .post-details {
            animation: fadeIn 0.5s ease-in-out;
        }

        .back-button {
            background-color: #ff8c00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .back-button:hover {
            background-color: #ff9f33;
        }
    </style>
</head>
<body>
    <div class="post-container">
        <h1>Post Page</h1>
        <div id="postDetails" class="loading">
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
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];

                            $query = "SELECT * FROM `posts` WHERE id = :id";
                            $statement = $pdo->prepare($query);
                            $statement->execute([':id' => $id]);

                            $post = $statement->fetch(PDO::FETCH_ASSOC);

                            if ($post) {
                                echo '<div class="post-details">';
                                echo '<h3>Title: ' . htmlspecialchars($post['title']) . '</h3>';
                                echo '<p>Body: ' . htmlspecialchars($post['body']) . '</p>';
                                echo '</div>';
                            } else {
                                echo "<p>No post found with ID $id!</p>";
                            }
                        } else {
                            echo "<p>No post ID provided!</p>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            ?>
        </div>
        <button class="back-button" onclick="goBack()">Back</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
