<?php

$servername = 'localhost';
$dbname = 'basic_blog_db';
$username_db = 'root'; 
$password_db = ''; 

try{

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $admin_username = 'admin';
    $admin_password = 'admin';
    $is_admin = 1;
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username,password, is_admin) VALUES (:username, :password, :is_admin)";

    $stmt=$conn->prepare($sql);

    $stmt->bindParam(':username',$admin_password);
    $stmt->bindParam(':password',$hashed_password);
    $stmt->bindParam(':is_admin',$is_admin);

    $stmt->execute();

    echo "Admin user '" . htmlspecialchars($admin_username) . "' created successfully ";
}catch (PDOException $e){
    echo "Error " . htmlspecialchars($e->getMessage());
}

?>

