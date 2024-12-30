<?php
require_once 'connect/connect.php';

$message = "";

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "<div class='success'>Xóa sách thành công</div>";
    } else {
        $message = "<div class='error'>Lỗi: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

$sql = "SELECT * FROM books";
$result = $conn->query($sql);
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Sách</title>
    <link rel="stylesheet" href="css/quan_ly_sach.css">
</head>
<body>
 
    <nav>
        <ul>
        <li><a href="trang_chu.php">TRANG CHỦ</a></li>
            <li><a href="quan_ly_sach.php">QUẢN LÝ SÁCH</a></li>
            <li><a href="muonsach.php">MƯỢN SÁCH</a></li>
            <li><a href="tra_sach.php">TRẢ SÁCH</a></li>
            <li><a href="user/index.php">TÀI KHOẢN </a></li>
            <li><a href="lichsumuon.php">LỊCH SỬ</a></li>
        </ul>
    </nav>
    <a class="add-button" href="add_sach.php">Thêm sách</a>
    <?php echo $message; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Ảnh</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['id']); ?></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><img class="book-image" src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>"></td>
                    <td><?php echo htmlspecialchars($book['description']); ?></td>
                    <td>
                        <a class="edit-button" href="edit_sach.php?id=<?php echo htmlspecialchars($book['id']); ?>">Sửa</a>
                        <a class="delete-button" href="quan_ly_sach.php?delete_id=<?php echo htmlspecialchars($book['id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>