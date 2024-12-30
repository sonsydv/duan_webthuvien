<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
        exit();
    }
    $username = $_SESSION['username'];
    include "../connect/connect.php"
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/user.css">
    
    <title>Thông tin Người Dùng</title>
    
    
</head>
<body>
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5>Xin chào, <b><?php echo htmlspecialchars($username); ?></b></h5>
            <a href="logout.php" class="btn btn-danger">Đăng xuất</a>
        </div>
    </div>
    <div class="container mt-5">
    <a href="add_user.php" class="btn btn-primary">
		Thêm Mới
	</a>
    </div>
    <table class="table table-bordered border-primary">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
                <th scope="col">Phone Number</th>
          
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include "../connect/connect.php";
                $sql = "SELECT * FROM users";
                
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)){ 
            ?>
                <tr>
                    <td><?php echo $row['id']?></td>
                    <td><?php echo $row['firstName']?></td>
                    <td><?php echo $row['lastName']?></td>
                    <td><?php echo $row['username']?></td>
                    <td><?php echo $row['email']?></td>
                    <td><?php echo $row['password']?></td>
                    <td><?php echo $row['phoneNumber']?></td>
                    
                    <td>
                        <a href="edit_user.php?this_id=<?php echo $row['id']?>" class="btn btn-success">Sửa</a>
                        <a href="delete_user.php?this_id=<?php echo $row['id']?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                    </td>
                </tr>
                
            <?php }          
            ?>
        </tbody>
    </table>
</body>
</html>