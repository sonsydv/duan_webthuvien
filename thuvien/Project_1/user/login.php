<?php
    include "../connect/connect.php";

    session_start();
    $loginError =  "";

    if(isset($_POST['login'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = mysqli_query($conn, $sql);
        
      if(mysqli_num_rows($result) == 1){
        $_SESSION['username'] = $username;
        header("location: ../trang_chu.php");
      }
      else{
        $loginError = "Tài khoản hoặc mật khẩu sai!";
      }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/login.css">
    
    <title>Đăng nhập</title>
  
    <!-- Login form -->
    <form action="login.php" method="post">
        <h2 class="text-center mb-4">Đăng Nhập</h2>
        <!-- Username input -->
        <div data-mdb-input-init class="form-outline mb-4">
          <input type="text" id="form2Example1" name="username" class="form-control" required/>
          <label class="form-label" for="form2Example1">Username</label>
        </div>
        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-4">
          <input type="password" id="form2Example2" name="password" class="form-control" required/>
          <label class="form-label" for="form2Example2">Password</label>
        </div>
        <!-- Error login form -->
        <?php if ($loginError == true): ?>
        <span class="error"><?php echo $loginError; ?></span>
        <?php endif; ?>
        <!-- Submit button -->
        <button data-mdb-ripple-init type="submit" name="login" class="btn btn-primary btn-block mb-4">Sign in</button>
        <!-- Register buttons -->
        <div class="text-center">
          <p>Not a member? <a href="register.php">Register</a></p>
        </div>
    </form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body>
</html>
