<?php
    // Kết nối cơ sở dữ liêu
    $conn = new mysqli('localhost', 'root', '', 'library');
    //Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>