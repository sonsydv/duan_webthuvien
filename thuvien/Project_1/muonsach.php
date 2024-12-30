<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'library');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Xử lý Thêm, Sửa và Xóa thông tin người mượn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm thông tin mượn sách
    if (isset($_POST['add_record'])) {
        $user_name = $_POST['user_name'];
        $phone_number = $_POST['phone_number'];
        $book_id = $_POST['book_id'];
        $borrow_date = date('Y-m-d H:i:s');

        // Lấy ngày hạn trả thủ công nếu có
        $due_date = $_POST['due_date']; // Ngày hạn trả được người dùng nhập vào

        // Thêm bản ghi mượn sách
        $sql = "INSERT INTO borrowed_books (user_name, phone_number, book_id, borrow_date, due_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $user_name, $phone_number, $book_id, $borrow_date, $due_date);

        if ($stmt->execute()) {
            // Cập nhật số lượng sách khả dụng
            $sql_update = "UPDATE books SET available = available - 1 WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $book_id);
            $stmt_update->execute();

            $message = "<div class='message success'>Mượn sách thành công! ID bản ghi: " . $stmt->insert_id . "</div>";
        } else {
            $message = "<div class='message error'>Lỗi: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    // Cập nhật thông tin mượn sách khi người dùng chỉnh sửa
    if (isset($_POST['update_record'])) {
        $id = $_POST['update_record'];
        $user_name = $_POST['user_name'];
        $phone_number = $_POST['phone_number'];
        $book_id = $_POST['book_id'];
        $due_date = $_POST['due_date']; // Ngày hạn trả thủ công

        // Cập nhật thông tin mượn sách
        $sql_update = "UPDATE borrowed_books SET user_name = ?, phone_number = ?, book_id = ?, due_date = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssisi", $user_name, $phone_number, $book_id, $due_date, $id);

        if ($stmt_update->execute()) {
            $message = "<div class='message success'>Cập nhật thông tin mượn sách thành công!</div>";
        } else {
            $message = "<div class='message error'>Lỗi: " . $stmt_update->error . "</div>";
        }

        $stmt_update->close();
    }
}

// Xóa thông tin mượn sách
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];

    // Xóa bản ghi mượn sách
    $sql_delete = "DELETE FROM borrowed_books WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // Cập nhật lại số lượng sách khả dụng
        $sql_book = "SELECT book_id FROM borrowed_books WHERE id = ?";
        $stmt_book = $conn->prepare($sql_book);
        $stmt_book->bind_param("i", $id);
        $stmt_book->execute();
        $result = $stmt_book->get_result();
        $book = $result->fetch_assoc();
        $book_id = $book['book_id'];

        $sql_update = "UPDATE books SET available = available + 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $book_id);
        $stmt_update->execute();

        $message = "<div class='message success'>Đã xóa bản ghi mượn sách thành công!</div>";
    } else {
        $message = "<div class='message error'>Lỗi: " . $stmt_delete->error . "</div>";
    }
    $stmt_delete->close();
}

// Lấy danh sách sách khả dụng
$sql_books = "SELECT id, title FROM books WHERE available > 0";
$result_books = $conn->query($sql_books);

// Lấy danh sách mượn sách, sắp xếp theo thứ tự ID từ bé đến lớn
$sql_borrow = "
    SELECT bb.id, bb.user_name, bb.phone_number, b.title, bb.borrow_date, bb.due_date, bb.return_date
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    ORDER BY bb.id ASC
";
$result_borrow = $conn->query($sql_borrow);

// Kiểm tra nếu có yêu cầu chỉnh sửa
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];

    // Lấy thông tin người mượn sách để chỉnh sửa
    $sql_edit = "SELECT * FROM borrowed_books WHERE id = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $borrow_record = $result_edit->fetch_assoc();

    $stmt_edit->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mượn Sách</title>
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

        /* CSS cho các thông báo */
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* CSS cho các nút chỉnh sửa và xóa */
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .edit-btn {
            background-color: #ffc107;
            color: white;
        }
        .edit-btn:hover {
            background-color: #e0a800;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c82333;
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

    <h2>Mượn Sách</h2>
    <?php echo $message; ?>

    <!-- Form thêm hoặc chỉnh sửa thông tin mượn sách -->
    <form method="POST">
        <label for="user_name">Họ và Tên:</label>
        <input type="text" id="user_name" name="user_name" required value="<?php echo isset($borrow_record) ? $borrow_record['user_name'] : ''; ?>">

        <label for="phone_number">Số Điện Thoại:</label>
        <input type="text" id="phone_number" name="phone_number" required value="<?php echo isset($borrow_record) ? $borrow_record['phone_number'] : ''; ?>">

        <label for="book_id">Sách:</label>
        <select id="book_id" name="book_id" required>
            <option value="">-- Chọn Sách --</option>
            <?php while ($row = $result_books->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo (isset($borrow_record) && $borrow_record['book_id'] == $row['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['title']); ?></option>
            <?php endwhile; ?>
        </select>

        <!-- Thêm trường ngày hạn trả thủ công -->
        <label for="due_date">Ngày Hạn Trả:</label>
        <input type="date" id="due_date" name="due_date" value="<?php echo isset($borrow_record) ? $borrow_record['due_date'] : date('Y-m-d', strtotime('+7 days')); ?>" required>

        <button type="submit" name="add_record" <?php echo isset($borrow_record) ? 'style="display:none;"' : ''; ?>>Lưu Thông Tin</button>
        <button type="submit" name="update_record" value="<?php echo $borrow_record['id']; ?>" <?php echo isset($borrow_record) ? '' : 'style="display:none;"'; ?>>Cập Nhật Thông Tin</button>
    </form>

    <h2>Danh Sách Mượn Sách</h2>

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
            <?php while ($row = $result_borrow->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['return_date'] ?? 'Chưa trả'); ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Sửa</a>
                        <a href="?action=delete&id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
