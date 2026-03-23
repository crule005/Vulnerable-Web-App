<?php require_once "includes/dbh.inc.php"; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add a Product</title>
    </head>

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc850);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            text-align: center;
            color: white;
        }

        .container {
            padding: 40px 20px;
        }

        form {
            background: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            margin: auto;
        }

        input {
            width: 80%;
            padding: 8px;
            margin: 8px 0;
        }

        button {
            padding: 10px;
            background-color: #2e59d9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 40%;
        }

        button:hover {
            background-color: #224abe;
        }
    </style>

    <body>

        <div class="container">
            <h1>Add a Product: </h1>

            <form method="POST" action="">
                <input type="text" name="prodName" placeholder="Product Name: " required>
                <input type="number" name="prodPrice" placeholder="Product Price: " required>
                <input type="number" name="prodStock" placeholder="Product Stock: " required>
                <div style ="display: flex; align-items: center; width: 40%">
                    <p>Released?: </p>
                    <input type="checkbox" name="released">
                </div>
                <button type="submit">Add Product</button>
            </form>
        </div>

        <?php
        if (isset($_POST["prodName"])){
            $prodName = $_POST["prodName"];
            $prodPrice = $_POST["prodPrice"];
            $prodStock = $_POST["prodStock"];
            $released = isset($_POST["released"]) ? 1 : 0;

            $stmt = $pdo->query("INSERT INTO products (prodName, prodPrice, prodStock, released) VALUES ('$prodName', '$prodPrice', '$prodStock', '$released')");
            echo "<p>Product record created!</p>";
        }
        ?>
    </body>
</html>
