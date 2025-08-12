<?php
    $servername = 'localhost';
    $dbname = 'basic_blog_db';
    $username = 'root';
    $password = '';

    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    require "./header.php"; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        try {
      
            $sql = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            header("Location: dashboard.php");
            exit();

        } catch (PDOException $e) {
        
            echo "Error: " . $e->getMessage();
        }
    }
?>