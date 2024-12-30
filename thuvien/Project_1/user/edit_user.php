<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
        exit();
    }
    include "../connect/connect.php";
    $this_id = $_GET['this_id'];
    // Kết nối 
    $sql = "SELECT * FROM users WHERE id='$this_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $message = ''; // Khai báo biến thông báo

    // Bắt sự kiện khi nhấn nút cập nhật
    if(isset($_POST['btn'])){
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];

        $sql = "UPDATE menber SET firstName='$firstName', lastName='$lastName',
        username='$username', email='$email', password='$password', phoneNumber='$phoneNumber',
        address='$address'
        WHERE id=".$this_id;

        // Chạy truy vấn
        if (mysqli_query($conn, $sql)) {
            $message = 'Cập nhật thành công!';
            echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
        } else {
            $message = 'Cập nhật thất bại. Vui lòng thử lại.';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/edit.css">
    <title>Sửa Người Dùng</title>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Sửa Người dùng: <?php echo $row['username'];?></h2>
        
        <!-- Hiển thị thông báo nếu có -->
        <?php if($message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstName" value="<?php echo $row['firstName'];?>">
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastName" value="<?php echo $row['lastName'];?>">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo $row['username'];?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="<?php echo $row['email'];?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" value="<?php echo $row['password'];?>">
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phoneNumber" value="<?php echo $row['phoneNumber'];?>">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo $row['address'];?>">
            </div>
            <button type="submit" class="btn btn-primary" name="btn">Cập nhật</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
