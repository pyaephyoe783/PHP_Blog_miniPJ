<?php
session_start();

$servername = 'localhost';
$dbname = 'basic_blog_db';
$username_db = 'root'; 
$password_db = '';     

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database Connection Error: " . htmlspecialchars($e->getMessage());
    exit();
}

$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

if (isset($_SESSION['message'])) {
    echo "<div class='max-w-6xl mx-auto p-4'><div class=\"text-green-800 bg-green-100 border border-green-200 rounded p-3\">" . htmlspecialchars($_SESSION['message']) . "</div></div>";
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='max-w-6xl mx-auto p-4'><div class=\"text-red-800 bg-red-100 border border-red-200 rounded p-3\">" . htmlspecialchars($_SESSION['error']) . "</div></div>";
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">
    <div class="max-w-6xl mx-auto p-4">

        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md shadow-sm rounded-b-md p-3 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <a href="index.php" class="text-2xl font-semibold">My Blog</a>
            </div>

            <div class="flex items-center gap-2">
                <?php if ($is_admin) { ?>
                    <a href="./post.php" class="inline-block px-4 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700">Create Post</a>
                <?php } ?>

                <?php if (isset($_SESSION['user_id'])) { ?>
                    <a href="login.php" class="inline-block px-4 py-2 rounded-md bg-red-600 text-white text-sm hover:bg-red-700">Logout</a>
                <?php } else { ?>
                    <a href="login.php" class="inline-block px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700">Login</a>
                    <a href="register.php" class="inline-block px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700">Register</a>
                <?php } ?>
            </div>
        </header>


        <main class="mt-6">
            <div id="postOutput" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $post_query = "SELECT * FROM posts ORDER BY created_at DESC";
                $stmt = $conn->prepare($post_query);
                $stmt->execute();
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($posts) {
                    foreach ($posts as $post) {
                        ?>
                        <article class="bg-white rounded-2xl shadow p-4 flex flex-col h-full">
                            <h3 class="text-lg md:text-xl font-bold mb-2 line-clamp-2"><?php echo htmlspecialchars($post['title']); ?></h3>

                            <p class="text-sm md:text-base text-gray-700 mb-3 overflow-auto h-24"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

                            <?php if ($is_admin) { ?>
                                <div class="flex justify-end gap-3 mb-2">
                                    <a href="edit_post.php?id=<?php echo htmlspecialchars($post['id']); ?>" class="px-3 py-1 text-sm rounded-md border border-gray-200 hover:bg-gray-100">Edit</a>
                                    <a href="delete_post.php?id=<?php echo htmlspecialchars($post['id']); ?>" class="px-3 py-1 text-sm rounded-md bg-red-500 text-white hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                                </div>
                            <?php } ?>

                            <div class="comments-area mt-2 pt-2 border-t border-gray-200 flex-1 flex flex-col">
                                <h4 class="text-sm font-semibold mb-2">Comments</h4>
                                <div class="space-y-3 overflow-auto max-h-40">
                                <?php
                                $sql_comments = "SELECT c.comment_content, u.username, c.created_at
                                                 FROM comments c
                                                 JOIN users u ON c.user_id = u.id
                                                 WHERE c.post_id = :post_id
                                                 ORDER BY c.created_at ASC";
                                $stmt_comments = $conn->prepare($sql_comments);
                                $stmt_comments->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
                                $stmt_comments->execute();
                                $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

                                if ($comments) {
                                    foreach ($comments as $comment) {
                                        ?>
                                        <div class="bg-gray-50 rounded-md p-2">
                                            <p class="text-xs text-gray-600"><strong><?php echo htmlspecialchars($comment['username']); ?></strong> <span class="text-gray-400">(<?php echo htmlspecialchars($comment['created_at']); ?>)</span></p>
                                            <p class="text-sm text-gray-800 mt-1"><?php echo nl2br(htmlspecialchars($comment['comment_content'])); ?></p>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<p class='text-sm text-gray-500'>No comments yet.</p>";
                                }
                                ?>
                                </div>

                                <div class="mt-3">
                                    <h5 class="text-sm font-medium mb-2">Add a Comment:</h5>
                                    <form action="submit_comment.php" method="POST" class="space-y-2">
                                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">

                                        <?php if (isset($_SESSION['user_id'])) { ?>
                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                            <p class="text-xs text-gray-600">Commenting as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                                            <textarea name="comment_content" placeholder="Your Comment" rows="3" class="w-full rounded-md border border-gray-200 p-2 text-sm resize-vertical" required></textarea>
                                            <div class="flex justify-end">
                                                <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700">Submit Comment</button>
                                            </div>
                                        <?php } else { ?>
                                            <p class="text-sm text-gray-600">Please <a href="login.php" class="text-blue-600 underline">log in</a> to leave a comment.</p>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                } else {
                    echo "<p class='text-center text-gray-500 col-span-full'>No posts found.</p>";
                }
                ?>
            </div>
        </main>
    </div>

    <script src="./dashboard.js"></script>
</body>
</html>