<?php
    session_start();

    $servername = 'localhost';
    $dbname = 'basic_blog_db';
    $username = 'root';
    $password = '';

    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username,$password);

    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // $post = "CREATE TABLE `posts` (
    //         `id` int(11) NOT NULL AUTO_INCREMENT,
    //         `title` varchar(255) NOT NULL,
    //         `content` text NOT NULL,
    //         `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    //         PRIMARY KEY (`id`)
    //         )";

    // $strm = $conn->prepare($post);
    // $strm->execute();

    // $conn->query("DROP TABLE posts");
    

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $text = $_POST['text'];
    $content = $_POST['content'];
    if(!empty($text) && !empty($content)){
        $insert_post = "INSERT INTO posts (title,content)
    values (:text,:content)";

    $stmt = $conn->prepare(($insert_post));

    $stmt->bindParam(':text',$text);
    $stmt->bindParam(':content',$content);

    try{
        $stmt->execute();
        // $_SESSION['message'] = 'Post created Successfully';
        header("Location: dashboard.php");

    }catch (PDOException $e) {
        echo "Error";
    }
    }
    }else{
        echo " please fill the blank";
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link rel="stylesheet" href="./output.css">
    
</head>
<body>

       <div class="bg-blue-200 mx-auto w-fit p-3 mt-35">
     <form action="" method="POST"  >
        <label for="title">Header</label>
        <br>
        <input type="text" class="p-1 border-2 mb-2 bg-white w-[200px]" name='text' require><br>
        <label for="conternt">Content</label>
        <br>
        <textarea name="content" id="content" class="p-1 border-2 mb-2 bg-white h-[100px] w-[200px]"></textarea><br>
        <input type="submit" value="Post" class="block mx-auto border-2 p-1 w-[75px] bg-blue-700">
        

    </form>
       <a href="./dashboard.php">Dashboard</a>
    
   </div>



    
</body>
</html>


