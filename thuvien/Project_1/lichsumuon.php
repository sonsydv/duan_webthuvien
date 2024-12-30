<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử sách đã mượn</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
<div class="container">
    <?php 
          if (isset($_POST['btnSearch']) && isset($_POST['search'])&& !empty($_POST['search'])) {
            $key = $_POST['search'];
            $id=$key;
            $con = mysqli_connect("localhost", "root", "", "library");
        
         
            $query = "SELECT * FROM borrowed_books JOIN books 
            ON borrowed_books.book_id=books.id WHERE user_id='$id'";
        
           $data =mysqli_query($con, $query);
        }
        ?>
      <form method="post" action="">

       <div class="search-bar">
      
      <div class="input-group">
          <input type="text" class="form-control" placeholder="Lịch sử mượn..." name="search" id="searchInput" value=<?php if( isset($key)&& !empty($key)) echo $key;?>>
          <button class="btn btn-outline-success" type="submit" name="btnSearch">
              Tìm kiếm
          </button>
      </div>
 
    </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sách</th>
                <th>Tác giả</th>
                <th>Image</th>
                <th>Ngày mượn</th>       
                <th>Ngày trả</th>
                <th>Trạng thái</th>               
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($data) && mysqli_num_rows($data) >= 0) {
            $i = 0;
            while ($row = mysqli_fetch_assoc($data)) {
                ?>
                <tr>
                    <td><?php echo (++$i) ?></td>
                    <td><?php echo $row['title'] ?></td>
                     <td><?php echo $row['author'] ?></td>
                     <td> <img src=<?php echo $row['image'] ?> width="150px" height="200px" alt="ảnh">
                    <td><?php echo $row['borrow_date'] ?></td>
                    <td><?php echo $row['return_date'] ?></td>
                    <td><?php 
                      if($row['returned']==0){
                        echo 'Đang mượn';
                    }else{
                        echo 'Đã trả';
                    }
                    ?></td>                
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</form>
</body>
</html>
