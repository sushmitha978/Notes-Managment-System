<?php
// Start session
session_start();

// Include config file
require_once "system/config.php";

// Redirect to login if not logged in
//if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//    header("location: access.php?login=true");
//    exit;
//}

// Initialize variables
$username = $email = $password = $confirm_password = $login_username = $login_password = "";
$login_err = $register_err = "";

// Process registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_err = "Please fill out all fields.";
    } elseif ($password != $confirm_password) {
        $register_err = "Passwords do not match.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);  // Secure password hashing
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                // Redirect to login
                header("location: access.php?login=true");
            } else {
                $register_err = "Something went wrong. Please try again.";
            }
            $stmt->close();
        }
    }
}

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $login_username = trim($_POST["login_username"]);
    $login_password = trim($_POST["login_password"]);

    if (empty($login_username) || empty($login_password)) {
        $login_err = "Please enter your username and password.";
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $login_username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password);
                $stmt->fetch();
                if (password_verify($login_password, $hashed_password)) {
                    session_start();
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    header("location: index.php");
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                $login_err = "Invalid username or password.";
            }
            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes Management System - Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            max-width: 450px;
            padding: 2rem;
            margin-top: 50px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<header class="bg-dark text-white text-center py-3">
    <h1>Notes Management System</h1>
</header>

<div class="container">
    <div class="form-header">
        <h2><?php echo isset($_GET['login']) ? "Login" : "Register"; ?></h2>
    </div>

    <?php if (!empty($login_err) || !empty($register_err)): ?>
        <div class="alert alert-danger">
            <?php echo $login_err ?: $register_err; ?>
        </div>
    <?php endif; ?>

    <?php if (!isset($_GET['login'])): ?>
        <form action="access.php" method="post">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="register" class="btn btn-primary">Register</button>
            </div>
            <div class="mt-3 text-center">
                <a href="access.php?login=true">Already have an account? Login here</a>
            </div>
        </form>
    <?php else: ?>
        <form action="access.php" method="post">
            <div class="mb-3">
                <input type="text" name="login_username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="login_password" class="form-control" placeholder="Password" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
            <div class="mt-3 text-center">
                <a href="access.php">Don’t have an account? Register here</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; <?php echo date('Y'); ?> Notes Management System. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
