<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
        exit();
    }
    include "../connect/connect.php";

    $message = ""; // Biến để chứa thông báo

    if(isset($_POST['btn'])){
        $id ="";  // Tạo ID rỗng, có thể thay bằng auto_increment trong CSDL
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];

        // Kiểm tra email hợp lệ
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "<div class='alert alert-danger' role='alert'>Email không hợp lệ!</div>";
        } else {
            // SQL để thêm người dùng
            $sql = "INSERT INTO users (id, firstName, lastName, username, email, password, phoneNumber, address) 
            VALUES ('$id','$firstName','$lastName', '$username', '$email', '$password', '$phoneNumber', '$address')";

            // Chạy câu lệnh SQL
            if (mysqli_query($conn, $sql)) {
                $message = "<div class='alert alert-success' role='alert'>Người dùng đã được thêm thành công!</div>";
            } else {
                $message = "<div class='alert alert-danger' role='alert'>Có lỗi xảy ra khi thêm người dùng.</div>";
            }
        }

        header("Location: index.php?message=" . urlencode($message)); // Chuyển hướng đến trang index với thông báo
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Thêm Mới Người Dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        nav {
            background-color: #007bff;
            padding: 10px;
            margin-bottom: 20px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav li {
            display: inline;
            margin: 0 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="trang_chu.php">TRANG CHỦ</a></li>
            <li><a href="quan_ly_sach.php">QUẢN LÝ SÁCH</a></li>
            <li><a href="muonsach.php">MƯỢN SÁCH</a></li>
            <li><a href="tra_sach.php">TRẢ SÁCH</a></li>
            <li><a href="user/index.php">TÀI KHOẢN</a></li>
            <li><a href="lichsumuon.php">LỊCH SỬ</a></li>
        </ul>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Thêm Người Dùng</h2>

        <!-- Hiển thị thông báo nếu có -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name: </label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name: </label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username: </label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email: </label>
                <input class="form-control" type="email" id="email" name="email" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Vui lòng nhập một địa chỉ email hợp lệ">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password: </label>
                <input class="form-control" type="password" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number: </label>
                <input class="form-control" type="text" id="phoneNumber" name="phoneNumber" required>
            </div>

            <button type="submit" class="btn btn-primary" name="btn">Thêm Người Dùng</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
