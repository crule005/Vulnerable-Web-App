<?php
session_start();
require_once "includes/dbh.inc.php";

$showAdminLink = false;

if (isset($_SESSION['currentUserID'])) {
    $stmt = $pdo->prepare("SELECT admin FROM users WHERE userID = :id");
    $stmt->execute(['id' => $_SESSION['currentUserID']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['admin'] == 1) {
        $showAdminLink = true;
    }
}
?>

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
		
		.menu-btn {
			position: fixed;
			top: 10px;
			left: 10px;
			font-size: 20px;
			padding: 10px;
			cursor: pointer;
			z-index: 1000;
			color: black;
			background: white;
			width: 160px;
		}

		.top-nav {
			position: fixed;
			top: -300px;
			left: 0;
			width: 100%;
			background-color: #333;
			transition: top 0.3s ease;
			text-align: center;
		}

		.top-nav.open {
			top: 0;
		}

		.top-nav a {
			display: block;
			color: white;
			padding: 15px;
			text-decoration: none;
		}

		.top-nav a:hover {
			background-color: #575757;
		}
    </style>
	
	<script>
		function toggleNav() {
		  const nav = document.getElementById("topNav");
		  nav.classList.toggle("open");
		}

		function logout() {
		  localStorage.removeItem("isLoggedIn");
		  window.location.href = "login.php";
		}
	</script>
	
    <body>
		<button class="menu-btn" onclick="toggleNav()">☰ Menu</button>

	<div id="topNav" class="top-nav">
		<a href="searchProducts.php">Search Products</a>
		<a href="searchUsers.php">Search Users</a>

		<?php if ($showAdminLink): ?>
			<a href="addProduct.php">Add Product</a>
			<a href="addUser.php">Add User</a>
		<?php endif; ?>

		<a href="#" onclick="logout()">Logout</a>
	</div>
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