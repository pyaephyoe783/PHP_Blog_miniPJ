<?php
   session_start();

        $servername = 'localhost';
        $dbname = 'basic_blog_db';
        $username_db = 'root'; 
        $password_db = ''; 

        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT id,username,password,is_admin FROM users WHERE username = :username ";

            $stmt=$conn->prepare($sql);

            $stmt->bindParam(':username',$username);
     
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password'])){
                $_SESSION['user_id'] =$user['id'];
                $_SESSION['username'] =$user ['username'];
                $_SESSION['is_admin'] = ($user['is_admin'] == 1);
                 $logfile = 'loginRecord.txt';
                $logMessage = "User {$user['username']} logged in on " . date('Y-m-d H:i:s') . PHP_EOL;
                file_put_contents($logfile, $logMessage, FILE_APPEND);

                header("Location: dashboard.php");
                exit();
            }else{
                $error = 'Username or password is invalid';
            }

            

        }

        } catch (PDOException $e){
            $error = 'Database Error: ' . htmlspecialchars($e->getMessage());
        }



?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    
  <link href="./login.css" rel="stylesheet">
 
</head>
<body>
    <div class="main-container">
        <div class="left-panel">
            <div class="logo-container">
                <div class="logo">
                    <img class="logoImg" src="./img/ace.jpg" alt="">
                </div>
                <div class="brand-info">
                    <span class="brand-name">AcePlus</span>
                    <span class="brand-slogan">Library</span>
                </div>
            </div>
            <div class="welcome-text">
                  <h1>
                    <span>Hello,</span>
                    <br>
                    <span>Welcome!</span>
                </h1>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-card">
                <h2>Log in</h2>
                <form action="#" method="POST">
                    <div class="form-group">
                       
                        <input type="text" id="username" class="loginInput" name="username" required placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="loginInput"id="password" name="password" required placeholder="password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="login-button">Log in</button>
                        <div class="errorMessage">
                        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>" ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="powered"><p>Powered By BaKhat</p></div>
            <div class=''>
                <a href="./register.php" class="register" style="text-decoration: none; color: blue; font-size: small;">Register Here</a>
            </div>
            
            
        </div>
    </div>


</body>
</html>




