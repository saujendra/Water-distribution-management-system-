<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}


if (!empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
} else {
    
    header("Location: user_dashboard.php");
    exit();
}


function calculateTotalPrice($cart_items) {
    $total_price = 0;
    foreach ($cart_items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total_price += $subtotal;
    }
    return $total_price;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'product_name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    );


    header("Location: view_cart.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    
    $index = $_POST['index'];

    
    unset($_SESSION['cart'][$index]);

    
    header("Location: view_cart.php");
    exit();
}

$total_price = calculateTotalPrice($cart_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
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

.cart-container {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tfoot td {
    font-weight: bold;
}

button {
    background-color: #f44336;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #d32f2f;
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
</style>
</head>
<body>
    <h1>Your Cart</h1>
    
    
    <div class="cart-container">
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $index => $item): ?>
                    <tr>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price'] * $item['quantity']; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit" name="remove_from_cart">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td><?php echo $total_price; ?></td>
                    <td>
                        <form action="confirmation.php" method="post">
                            <input type="submit" name="confirm_purchase" value="Confirm Purchase">
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    
    <a href="user_dashboard.php">Back to Dashboard</a>
</body>
</html>
