        <?php
session_start();


require_once "database.php";

if (isset($_POST['submit'])) {
 
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $errors = array();

    
    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required.";
    } else {
       
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            
            if ($row = mysqli_fetch_assoc($result)) {
               
                if (password_verify($password, $row['password'])) {
                    
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['user_type'] = $row['user_type']; 
                    
                    
                    if ($row['user_type'] === 'Admin') {
                        header("Location: Admin_dashboard.php");
                        exit();
                    } elseif ($row['user_type'] === 'user') {
                        header("Location: user_dashboard.php");
                        exit();
                    } else {
                        
                        array_push($errors, "Invalid user type.");
                    }
                } else {
                    $errors[] = "Incorrect password.";
                }
            } else {
                $errors[] = "No user found with the provided email.";
            }
        } else {
            die("Something went wrong with the database query.");
        }
    }

   
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Form</title>
   
    <link rel="stylesheet" href="stylee.css">
    <style>
        .hed{
            font-size:50px;
        }
        </style>
</head>
<body>
    <div id="headerSection">
        <div class="hed">
       <center> <h1>Water</h1></center>
</div>
        <div class="container">
            <h1>Login</h1>
            
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Login" name="submit">
                </div>
            </form>
            <div>
                <p>Don't have an account? <a href="rege.php">Register Here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
