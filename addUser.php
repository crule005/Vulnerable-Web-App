<?php require_once "includes/dbh.inc.php"; ?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <title>User Creator</title>
    </head>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4e73df, #1cc850);
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        h2 {
            margin-top: 0;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0 12px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .admin-options {
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #4e73df;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #3a5ccc;
        }

        .user-list {
            margin-top: 15px;
            font-size: 14px;
        }
    </style>

    <body>
        <div class="container">
            <h1>Add a User: </h1>

            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username: " required>
                <input type="text" name="pwd" placeholder="Password: " required>
                <div style ="display: flex; align-items: center; width: 40%">
                    <p>Admin?: </p>
                    <input type="checkbox" name="admin">
                </div>
                <button type="submit">Add User</button>
            </form>
        </div>

        <?php
        if (isset($_POST["username"])){
            $username = $_POST["username"];
            $pwd = $_POST["pwd"];
            $admin = isset($_POST["admin"]) ? 1 : 0;

            $stmt = $pdo->query("INSERT INTO users (username, pwd, admin) VALUES ('$username', '$pwd', '$admin')");
            echo "<p>User record created!</p>";
        }
        ?>
    </body>
</html>