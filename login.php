<?php
session_start();
require_once "includes/dbh.inc.php";

$lockTime = 60;
$maxAttempts = 3;

if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

if (!isset($_SESSION['lock_until'])) {
    $_SESSION['lock_until'] = 0;
}

if (time() < $_SESSION['lock_until']) {
    $remaining = $_SESSION['lock_until'] - time();
    die("Too many failed attempts. Try again in $remaining seconds.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND pwd = :pwd");
    $stmt->execute([
        'username' => $username,
        'pwd' => $pwd
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $_SESSION['failed_attempts'] = 0;
        $_SESSION['lock_until'] = 0;

        $_SESSION['currentUserID'] = $user['userID'];
        $_SESSION['currentUseradmin'] = $user['admin'];

        header("Location: searchProducts.php");
        exit();

    } else {

        $_SESSION['failed_attempts']++;

        if ($_SESSION['failed_attempts'] >= $maxAttempts) {
            $_SESSION['lock_until'] = time() + $lockTime;
            $_SESSION['failed_attempts'] = 0;
            die("Too many failed attempts. Locked for 1 minute.");
        }

        $error = "Login failed. " . ($maxAttempts - $_SESSION['failed_attempts']) . " attempt(s) remaining.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Secure Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 320px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .input-group input:focus {
            border-color: #4e73df;
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #4e73df;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            background-color: #2e59d9;
        }

        .error-message {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>

    <body>
        <div class="login-container">
            <h2>Login</h2>
			
			<?php if (!empty($error)): ?>
				<p class="error-message"><?php echo $error; ?></p>
			<?php endif; ?>

            <form id="loginForm" method="POST" action="">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="pwd" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
        
        
    </body>
</html>
