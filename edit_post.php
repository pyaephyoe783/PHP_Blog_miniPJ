<?php

    $servername = 'localhost';
    $dbname = 'basic_blog_db';
    $username = 'root';
    $password = '';

    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "SELECT * FROM posts WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            header('Location: dashboard.php');
            exit;
        }
    } else {

        header('Location: dashboard.php');
        exit;
    }

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
     <link rel="stylesheet" href="./output.css">
</head>
<body>

    <div class="bg-blue-200 mx-auto w-fit p-3 mt-35">
        <form action="update_post.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']) ?>">
        <label for="title">Header</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']) ?>" class="p-1 border-2 mb-2 bg-white w-[200px]"><br>
        
        <label for="content">Content</label><br>
        <textarea name="content" class="p-1 border-2 mb-2 bg-white h-[100px] w-[200px]"><?php echo htmlspecialchars($post['content']) ?></textarea><br>
        
        <input type="submit" class="block mx-auto border-2 p-1 w-[75px] bg-red-500 text-wrap text-sm" value="Update">
    </form>
    </div>
    
</body>
</html>