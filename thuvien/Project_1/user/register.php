<?php
    include "../connect/connect.php";

    if(isset($_POST['btn'])){
        // Lấy dữ liệu từ form và kiểm tra đầu vào
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Kiểm tra dữ liệu rỗng
        if(empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)){
            echo "Vui lòng điền đầy đủ thông tin!";
            exit();
        }

        // Kiểm tra email hợp lệ
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "Email không hợp lệ!";
            exit();
        }

        // Kiểm tra username đã tồn tại
        $checkUsername = "SELECT id FROM users WHERE username = ?";
        $stmtCheck = mysqli_prepare($conn, $checkUsername);
        mysqli_stmt_bind_param($stmtCheck, "s", $username);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);
        if(mysqli_stmt_num_rows($stmtCheck) > 0){
            echo "Username đã tồn tại. Vui lòng chọn tên khác!";
            exit();
        }
        mysqli_stmt_close($stmtCheck);

        // Kiểm tra email đã tồn tại
        $checkEmail = "SELECT id FROM users WHERE email = ?";
        $stmtCheckEmail = mysqli_prepare($conn, $checkEmail);
        mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
        mysqli_stmt_execute($stmtCheckEmail);
        mysqli_stmt_store_result($stmtCheckEmail);
        if(mysqli_stmt_num_rows($stmtCheckEmail) > 0){
            echo "Email đã tồn tại. Vui lòng chọn email khác!";
            exit();
        }
        mysqli_stmt_close($stmtCheckEmail);

        // **Không mã hóa mật khẩu, lưu mật khẩu gốc**
        // Sử dụng mật khẩu người dùng nhập vào mà không thay đổi
        $plain_password = $password; // Mật khẩu không mã hóa

        // Chuẩn bị câu lệnh SQL
        $sql = "INSERT INTO users (firstName, lastName, username, email, password) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $username, $email, $plain_password);
        $result = mysqli_stmt_execute($stmt);

        if($result){
            // Chuyển hướng về trang đăng nhập
            header("Location: login.php");
            exit();
        } else {
            echo "Đăng ký thất bại! Vui lòng thử lại.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
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
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/register.css">
    <style>
        .form-outline input.form-control {
            padding: 10px;
        }

        .form-outline label {
            transition: 0.2s ease all;
        }

        .form-outline input:focus + label,
        .form-outline input:not(:placeholder-shown) + label {
            transform: translateY(-20px);
            font-size: 12px;
            color: #007bff;
        }
    </style>
    <title>Đăng ký</title>
</head>
<body>
    <form action="register.php" method="post">
        <div class="form-outline mb-4">
            <input type="text" id="firstName" name="firstName" class="form-control" placeholder=" " />
            <label class="form-label" for="firstName">First Name</label>
        </div>
        <div class="form-outline mb-4">
            <input type="text" id="lastName" name="lastName" class="form-control" placeholder=" " />
            <label class="form-label" for="lastName">Last Name</label>
        </div>
        <div class="form-outline mb-4">
            <input type="text" id="username" name="username" class="form-control" placeholder=" " />
            <label class="form-label" for="username">Username</label>
        </div>
        <div class="form-outline mb-4">
            <input type="email" id="email" name="email" class="form-control" placeholder=" " />
            <label class="form-label" for="email">Email</label>
        </div>
        <div class="form-outline mb-4">
            <input type="password" id="password" name="password" class="form-control" placeholder=" " />
            <label class="form-label" for="password">Password</label>
        </div>

        <button type="submit" name="btn" class="btn btn-primary btn-block mb-4">Sign up</button>
    </form>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</html>
