<?php
require_once 'connect/connect.php'; // Kết nối CSDL

if (isset($_POST["submit"])) {
    $book_id = $_GET['id']; // Lấy ID sách từ URL
    $target_dir = "upload_anh.php"; // Thư mục lưu ảnh (trong htdocs)
    $imageName = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $imageName; // Tạo đường dẫn đầy đủ

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra file có phải là ảnh thật không
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $error_message = "File không phải là ảnh.";
        $uploadOk = 0;
    }

     // Đổi tên file để tránh trùng lặp (quan trọng)
    $uniqueFilename = uniqid() . "." . $imageFileType;
    $target_file = $target_dir . $uniqueFilename;
    $imagePath = $target_dir . $uniqueFilename;

    // Kiểm tra file đã tồn tại chưa
    if (file_exists($target_file)) {
        $error_message = "Xin lỗi, file đã tồn tại.";
        $uploadOk = 0;
    }

    // Kiểm tra kích thước file (ví dụ: 5MB)
    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
        $error_message = "Xin lỗi, file quá lớn (tối đa 5MB).";
        $uploadOk = 0;
    }

    // Chỉ cho phép một số định dạng file
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        $error_message = "Xin lỗi, chỉ cho phép file JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo $error_message;
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
          try{
             $sql = "UPDATE books SET image = '$imagePath' WHERE id ='$book_id'";
             if ($conn->query($sql) === TRUE) {
               header("Location: quan_ly_sach.php");
             } else {
               $error_message= "Lỗi cập nhật CSDL: " . $conn->error;
              echo $error_message;
             }
           }catch (Exception $e) {
              $error_message = "Lỗi: " . $e->getMessage();
              echo $error_message;
           }finally {
              if (isset($conn)) {
                $conn->close();
              }
           }

        } else {
            $error_message = "Có lỗi xảy ra khi upload file.";
           echo $error_message;
        }
    }
}
?>