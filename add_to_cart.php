<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['product_name'], $_POST['price'], $_POST['quantity']) && 
        is_numeric($_POST['product_id']) && is_numeric($_POST['price']) && is_numeric($_POST['quantity'])) {
        
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // Clear the session cart before adding a new item
        $_SESSION['cart'] = array();

        // Add the new item to the session cart
        $_SESSION['cart'][] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );

        header("Location: user_dashboard.php");
        exit();
    } else {
        header("Location: user_dashboard.php?error=1");
        exit();
    }
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>
