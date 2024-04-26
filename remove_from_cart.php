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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    if (isset($_POST['cart_id'])) {
        try {
            $cart_id = $_POST['cart_id'];

            // Delete the item from the cart
            $stmt = $pdo->prepare("DELETE FROM user_carts WHERE cart_id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $_SESSION['user_id']]);

            // Redirect back to the cart page
            header("Location: view_cart.php");
            exit();
        } catch (PDOException $e) {
            die("Error deleting item from cart: " . $e->getMessage());
        }
    } else {
        header("Location: view_cart.php");
        exit();
    }
} else {
    header("Location: view_cart.php");
    exit();
}
?>
