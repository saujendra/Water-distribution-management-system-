<?php
session_start();

$host = 'localhost';
$dbname = 'mini';
$username = 'root';
$password = '';

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_name'], $_POST['price'], $_POST['quantity']) && 
        is_numeric($_POST['price']) && is_numeric($_POST['quantity'])) {
        
        // Retrieve form data
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        
        $_SESSION['cart'][] = array(
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );

        header("Location: user_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    padding: 20px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

p {
    text-align: center;
    margin-bottom: 20px;
}

.water-options {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

.water-option {
    text-align: center;
    margin: 20px;
    width: 200px;
}

.water-option img {
    width: 100%;
    border-radius: 5px;
}

.water-option p {
    margin-top: 10px;
    font-weight: bold;
}

.cart-button {
    background-color: #4caf50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-button:hover {
    background-color: #45a049;
}

.view-cart-button {
    display: block;
    text-align: center;
    margin-top: 20px;
    background-color: #008CBA;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    position:absolute;
    top:15px;
    right:150px;
}

.view-cart-button:hover {
    background-color: #005f79;
}

a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #666;
    text-decoration: none;
}

a:hover {
    color: #333;
}
.logout-button {
    display: block;
    text-align: center;
    margin-top: 20px;
    background-color: red;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    position: absolute;
    top: 15px;
    right: 15px;
}

.logout-button:hover {
    background-color: #005f79;
}


</style>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['full_name']; ?>!</h1>
    
    <p>This is your user dashboard.</p>
    
    
    <div class="water-options">
        <div class="water-option">
            <img src="1-liter-bottle.jpg" alt="1 Liter Bottle">
            <p>1 Liter Bottle - ₹10</p>
            <form action="" method="post">
                <input type="hidden" name="product_name" value="1 Liter Bottle">
                <input type="hidden" name="price" value="10">
                <label for="quantity_1">Quantity:</label>
                <input type="number" id="quantity_1" name="quantity" min="1" value="1">
                <input type="submit" class="cart-button" value="Add to Cart">
            </form>
        </div>
       <div class="water-options">
        <div class="water-option">
            <img src="5-liter-bottle.jpg" alt="5 Liter Bottle">
            <p> 2 Liter Bottle - ₹50</p>
            <form action="" method="post">
                <input type="hidden" name="product_name" value="5 Liter Bottle">
                <input type="hidden" name="price" value="50">
                <label for="quantity_1">Quantity:</label>
                <input type="number" id="quantity_1" name="quantity" min="1" value="1">
                <input type="submit" class="cart-button" value="Add to Cart">
            </form>
        </div>

        <div class="water-options">
        <div class="water-option">
            <img src="10-liter-bottle.jpg" alt="10 Liter Bottle">
            <p> 10 Liter Bottle - ₹80</p>
            <form action="" method="post">
                <input type="hidden" name="product_name" value="10 Liter Bottle">
                <input type="hidden" name="price" value="80">
                <label for="quantity_1">Quantity:</label>
                <input type="number" id="quantity_1" name="quantity" min="1" value="1">
                <input type="submit" class="cart-button" value="Add to Cart">
            </form>
        </div>


        
    </div>
    
    <a href="view_cart.php" class="view-cart-button">View Cart</a>
    <div class="logout-button">
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
