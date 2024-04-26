<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           require_once "database.php"; 

           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           $userType = $_POST["user_type"]; 

           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
               $errors[] = "All fields are required";
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               $errors[] = "Email is not valid";
           }
           if (strlen($password) < 8) {
               $errors[] = "Password must be at least 8 characters long";
           }
           if ($password !== $passwordRepeat) {
               $errors[] = "Passwords do not match";
           }

           $sql = "SELECT * FROM users WHERE email = ?";
           $stmt = mysqli_prepare($conn, $sql);
           mysqli_stmt_bind_param($stmt, "s", $email);
           mysqli_stmt_execute($stmt);
           $result = mysqli_stmt_get_result($stmt);
           if (mysqli_num_rows($result) > 0) {
               $errors[] = "Email already exists!";
           }

           if (empty($errors)) {
               $sql = "INSERT INTO users (full_name, email, password, user_type) VALUES (?, ?, ?, ?)";
               $stmt = mysqli_prepare($conn, $sql);
               mysqli_stmt_bind_param($stmt, "ssss", $fullName, $email, $passwordHash, $userType);
               if (mysqli_stmt_execute($stmt)) {
                   echo "<div class='alert alert-success'>You are registered successfully.</div>";
               } else {
                   echo "<div class='alert alert-danger'>Registration failed. Please try again later.</div>";
               }
           } else {
               foreach ($errors as $error) {
                   echo "<div class='alert alert-danger'>$error</div>";
               }
           }
        }
        ?>
        <form action="rege.php" method="post">
    <div class="form-group">
        <input type="text" class="form-control" name="fullname" placeholder="Full Name">
    </div>
    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Email">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Password">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
    </div>
    <div class="form-group">
        <label for="user_type">Select User Type:</label>
        <select name="user_type" id="user_type" class="form-control">
            <option value="Admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>
    <div class="form-btn">
        <input type="submit" class="btn btn-primary" value="Register" name="submit">
    </div>
</form>

        <div>
            <p>Already Registered? <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>
