<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Xử lý trả sách
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book'])) {
    $record_id = $_POST['record_id'];

    // Lấy thông tin sách từ bản ghi mượn
    $sql_get_book = "SELECT book_id FROM borrowed_books WHERE id = ?";
    $stmt_get_book = $conn->prepare($sql_get_book);
    $stmt_get_book->bind_param("i", $record_id);
    $stmt_get_book->execute();
    $result_get_book = $stmt_get_book->get_result();
    $borrowed_book = $result_get_book->fetch_assoc();

    // Trả sách
    $sql_delete = "DELETE FROM borrowed_books WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $record_id);

    if ($stmt_delete->execute()) {
        // Cập nhật số lượng sách khả dụng
        $sql_update = "UPDATE books SET available = available + 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $borrowed_book['book_id']);
        $stmt_update->execute();

        $message = "<p style='color:green;'>Trả sách thành công!</p>";
    } else {
        $message = "<p style='color:red;'>Lỗi khi trả sách.</p>";
    }
}

// Xử lý tìm kiếm
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Lấy danh sách sách đã mượn có lọc theo tìm kiếm (theo ID người dùng hoặc Tên sách)
$sql_borrowed = "
    SELECT bb.id, bb.user_name, bb.phone_number, b.title, bb.borrow_date, bb.due_date, bb.return_date
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    WHERE bb.user_name LIKE ? OR b.title LIKE ?
    ORDER BY bb.borrow_date DESC
";
$stmt_borrowed = $conn->prepare($sql_borrowed);
$search_param = "%" . $search_query . "%";
$stmt_borrowed->bind_param("ss", $search_param, $search_param);
$stmt_borrowed->execute();
$result_borrowed = $stmt_borrowed->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Trả Sách</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
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

    <h2>Quản Lý Trả Sách</h2>
    <?php echo $message; ?>

    <!-- Tìm kiếm -->
    <form method="GET">
        <label for="search">Tìm kiếm:</label>
        <input type="text" id="search" name="search" placeholder="Nhập tên người dùng hoặc tên sách" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Danh sách sách đã mượn -->
    <h2>Danh Sách Sách Đã Mượn</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Số Điện Thoại</th>
                <th>Tên Sách</th>
                <th>Ngày Mượn</th>
                <th>Hạn Trả</th>
                <th>Ngày Trả</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_borrowed->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['return_date'] ?? 'Chưa trả'); ?></td>
                    <td>
                        <?php if (!$row['return_date']): ?>
                            <form method="POST">
                                <input type="hidden" name="record_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="return_book">Trả sách</button>
                            </form>
                        <?php else: ?>
                            <span>Đã trả</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
