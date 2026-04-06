<?php
session_start();
require_once "includes/dbh.inc.php";

$showAdminLink = false;
if (isset($_SESSION['currentUseradmin'])) {
    if ($_SESSION['currentUseradmin'] == 1) {
        $showAdminLink = true;
    }
}
else {
    header('Location: /login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>User Database</title>

        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #4e73df, #1cc850);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .container {
                background: white;
                padding: 30px;
                border-radius: 10px;
                width: 800px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            }

            h1 {
                text-align: center;
            }

            .search-box {
                margin-top: 15px;
            }

            .search-box input {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .table-wrapper {
                max-height: 400px;
                overflow-y: auto;
                border: 1px solid #ccc;
            }

            th, td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }

            th {
                position: sticky;
                top: 0;
                background: #f5f5f5;
            }

            button {
                padding: 6px 12px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .delete-btn {
                background: #e74a3b;
                color: white;
            }

            .delete-btn:hover {
                background: #c0392b;
            }

            .empty {
                text-align: center;
                color: #777;
            }
			
			button:hover {
				background-color: #224abe;
			}
			.menu-btn {
				position: fixed;
				top: 10px;
				left: 10px;
				font-size: 20px;
				padding: 10px;
				cursor: pointer;
				z-index: 1000;
				width: 160px;
			}

			.top-nav {
				position: fixed;
				top: -300px;
				left: 0;
				width: 100%;
				background-color: #333;
				overflow: hidden;
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
    </head>

	<script>
		function toggleNav() {
			const nav = document.getElementById("topNav");
			nav.classList.toggle("open");
		}

		function logout() {
			localStorage.removeItem("isLoggedIn");
			window.location.href = "includes/logout.php";
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

            <h1>User Database</h1>
            <form method="POST" action="">
                <input type="text" name="searchInput" placeholder="Search Users: " required>
                <button type="submit">Search...</button>
            </form>

            <?php
                if (isset($_POST["searchInput"])) {
                    $searchInput = $_POST["searchInput"];
                    try {
                        $query = "SELECT * FROM users WHERE username LIKE ?;";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$searchInput]);
                        //$stmt = $pdo->query("SELECT * FROM users WHERE username LIKE '$searchInput'");
                        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "<p>Table queried</p>";
                        if (empty($searchResults)) {
                            echo "<p>There were no results for that search!</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p>Error: " . $e->getMessage() . "</p>";
                    }
                } else {
                    try {
                        $stmt = $pdo->query("SELECT * FROM users");
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo "<p>Table queried</p>";
                        if (empty($results)) {
                            echo "<p>There were no results for that search!</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p>Error: " . $e->getMessage() . "</p>";
                    }
                }
            ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Date Created</th>
                            <th>Admin</th>
                        </tr>
                    </thead>

                    <tbody id="userBody">
                        <?php
                        if (isset($_POST["searchInput"]) && !empty($searchResults)) {
                            foreach ($searchResults as $row) {
                                echo "<tr>";
                                echo "<td>" . $row["userID"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["pwd"] . "</td>";
                                echo "<td>" . $row["created_at"] . "</td>";
                                echo "<td>" . $row["admin"] . "</td>";
                                echo "</tr>";
                            }
                        } elseif (isset($_POST["searchInput"]) && empty($searchResults)) {
                            echo "<tr class='empty'><td colspan='5'>No users in database</td></tr>";
                        }
                        else {
                            foreach ($results as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["userID"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["pwd"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "<td>" . $row["admin"] . "</td>";
                            echo "</tr>";
                            }
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </body>
</html>