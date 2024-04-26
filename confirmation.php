<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Check if cart items are stored in the session
if (!empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
} else {
    // Redirect to user dashboard if cart is empty
    header("Location: user_dashboard.php");
    exit();
}

// Connect to the database
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

// Insert cart items into the database
try {
    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO user_carts (user_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $item['product_name'], $item['price'], $item['quantity']]);
    }

    // Clear the session cart after successful insertion
    unset($_SESSION['cart']);

    // Redirect to confirmation page
    header("Location: thank_you.php");
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
