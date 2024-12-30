<?php
session_start();

// Kiểm tra xem session đã tồn tại chưa
if(isset($_SESSION['username'])){
    // Hủy bỏ tất cả các biến session
    session_unset(); 
    
    // Hủy session
    session_destroy(); 
}

// Chuyển hướng về trang login
header("location: login.php");
exit();
?>
