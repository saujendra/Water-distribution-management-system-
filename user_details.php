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


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("User ID is missing or invalid.");
}

//retrive
$user_id = $_GET['id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Error fetching user details: " . $e->getMessage());
}


try {
    $stmt = $pdo->prepare("SELECT product_name, SUM(price * quantity) AS total_cost 
                           FROM user_carts 
                           WHERE user_id = ? 
                           GROUP BY product_name");
    $stmt->execute([$user_id]);
    $total_costs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching total costs: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .back-container {
            text-align: center;
            margin-top: 20px;
        }

        .back-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        <table>
            <tr>
                <th>User ID</th>
                <td><?php echo $user['id']; ?></td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td><?php echo $user['full_name']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $user['email']; ?></td>
            </tr>
            <tr>
                <th>User Type</th>
                <td><?php echo $user['user_type']; ?></td>
            </tr>
        </table>

        <h2>Total Costs by Category</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Total Cost</th>
            </tr>
            <?php foreach ($total_costs as $cost): ?>
                <tr>
                    <td><?php echo $cost['product_name']; ?></td>
                    <td>₹<?php echo number_format($cost['total_cost'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="back-container">
            <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
