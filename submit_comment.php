<?php
session_start();

$servername = 'localhost';
$dbname = 'basic_blog_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
        $post_id = $_POST['post_id'] ?? null;
        $user_id = $_SESSION['user_id'];
        $comment_content = $_POST['comment_content'] ?? '';

        if (!empty($post_id) && !empty($comment_content)) {
            $sql = "INSERT INTO comments (post_id, user_id, comment_content) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
            $stmt->bindParam(3, $comment_content, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['message'] = "✅ Comment added successfully!";
        } else {
            $_SESSION['error'] = "❌ Comment content cannot be empty.";
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "❌ You must be logged in to comment.";
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: dashboard.php");
    exit();
}