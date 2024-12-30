<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
        exit();
    }
    include "../connect/connect.php";
    $this_id = $_GET['this_id'];
    //Xóa 
    $sql = " DELETE FROM users WHERE id='$this_id' ";
    //chạy
    mysqli_query($conn, $sql);
    //quay lại 
    header("location: index.php");
    
?>