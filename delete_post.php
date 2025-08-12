<?php

    $servername = 'localhost';
    $dbname = 'basic_blog_db';
    $username = 'root';
    $password = '';

    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt=$conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: dashboard.php");

    }