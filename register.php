<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <div style="max-width: 400px; margin: auto; padding: 20px; border: 1px solid black; border-radius: 5px;">
        <h2>Register New User</h2>
        <?php
        session_start();
        if (isset($_SESSION['register_error'])) {
            echo "<p style='color:red;'>" . htmlspecialchars($_SESSION['register_error']) . "</p>";
            unset($_SESSION['register_error']);
        }
        ?>
        <form action="registerProcess.php" method="POST">
            <div>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required>
            </div>
            <br>
            <div>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required>
            </div>
            <br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>