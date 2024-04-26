<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php"); 
    exit();
}

// Database connection settings
$host = 'localhost';
$dbname = 'mini';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


$users = [];
if (isset($_POST['view_users'])) {
    try {
        $stmt_users = $pdo->query("SELECT * FROM users WHERE user_type = 'User'");
        $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching user details: " . $e->getMessage());
    }
}


$total_sales = 0;
if (isset($_POST['view_sales'])) {
    try {
        $stmt_sales = $pdo->query("SELECT SUM(price * quantity) AS total_sales FROM user_carts");
        $total_sales_result = $stmt_sales->fetch(PDO::FETCH_ASSOC);
        $total_sales = $total_sales_result['total_sales'];
    } catch (PDOException $e) {
        die("Error fetching total sales: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="stylee.css">
    <style>
        /* Your additional styles */
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

        h2 {
            color: #333;
            margin-top: 30px;
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

        .logout-container {
            text-align: center;
            margin-top: 20px;
        }

        .logout-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <form method="post" action="">
            <button type="submit" name="view_users">View User Information</button>
            <button type="submit" name="view_sales">View Sales Information</button>
        </form>

        <?php if (isset($_POST['view_users'])): ?>
            <h2>User Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['full_name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['user_type']; ?></td>
                            <td><a href="user_details.php?id=<?php echo $user['id']; ?>">View Details</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (isset($_POST['view_sales'])): ?>
            <h2>Total Sales Information</h2>
            <p>Total Sales: <?php echo $total_sales; ?></p>
        <?php endif; ?>

        <div class="logout-container">
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>
</body>
</html>
