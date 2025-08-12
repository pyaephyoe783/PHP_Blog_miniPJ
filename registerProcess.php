<?php
session_start();

$servername = 'localhost';
$dbname = 'basic_blog_db';
$username_db = 'root';
$password_db = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $_SESSION['register_error'] = "Username and Password are required.";
            header("Location: register.php");
            exit();
        }

        
        $check_sql = "SELECT id FROM users WHERE username = :username";
        $stmt = $conn->prepare($check_sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['register_error'] = "Username already exists. Please choose another one.";
            header("Location: register.php");
            exit();
        }

       
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $is_admin = 0;

        
        $sql = "INSERT INTO users (username, password, is_admin) VALUES (:username, :password, :is_admin)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':is_admin', $is_admin, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['message'] = 'Registration Successful!';
        header('Location: login.php');
        exit();

    } else {
        header("Location: register.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['register_error'] = "Database error: " . htmlspecialchars($e->getMessage());
    header("Location: register.php");
    exit();
}
?>