-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 30, 2024 lúc 10:43 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `library`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publish_year` int(11) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `available` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publisher`, `publish_year`, `isbn`, `genre`, `description`, `image`, `quantity`, `available`) VALUES
(1, 'Đắc Nhân Tâm', 'Dale Carnegie', NULL, NULL, NULL, NULL, 'Sách về kỹ năng giao tiếp.', 'image/67707445d359a.jpg', 10, 5),
(2, 'Ngữ Văn', 'Nhà xuất bản giáo dục', NULL, NULL, NULL, NULL, 'Hướng dẫn lập trình PHP.', 'image/677074da67a10.png', 5, 5),
(3, 'toán học', 'G.S Bùi Văn Sơn', NULL, NULL, NULL, NULL, 'Kết nối đam mê toán học', 'image/677039c1c4036.jpg', 0, 3),
(4, 'toán lớp 9', 'giáo sư bùi sơn', NULL, NULL, NULL, NULL, 'sách bản quyền', 'image67707e019ca2a.jpg', 0, 0),
(5, 'Đắc Nhân Tâm', 'Dale Carnegie', NULL, NULL, NULL, NULL, 'Sách về kỹ năng giao tiếp.', 'image/67707445d359a.jpg', 10, 5),
(6, 'Ngữ Văn', 'Nhà xuất bản giáo dục', NULL, NULL, NULL, NULL, 'Hướng dẫn lập trình PHP.', 'image/677074da67a10.png', 5, 5),
(7, 'toán học', 'G.S Bùi Văn Sơn', NULL, NULL, NULL, NULL, 'Kết nối đam mê toán học', 'image/677039c1c4036.jpg', 0, 3),
(8, 'toán lớp 9', 'giáo sư bùi sơn', NULL, NULL, NULL, NULL, 'sách bản quyền', 'image67707e019ca2a.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime DEFAULT current_timestamp(),
  `due_date` date NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `returned` tinyint(1) DEFAULT 0,
  `fine` decimal(10,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `returned`, `fine`, `note`, `user_name`, `phone_number`) VALUES
(1, 0, 1, '2024-12-28 23:00:16', '2025-01-04', NULL, 0, 0.00, NULL, 'sơn 123', '03928321'),
(2, 0, 1, '2024-12-28 23:10:52', '2025-01-10', NULL, 0, 0.00, NULL, 'hoàng', '03928321'),
(3, 0, 2, '2024-12-28 23:11:44', '2025-01-04', NULL, 0, 0.00, NULL, 'tuấn', '113'),
(4, 0, 1, '2024-12-28 23:11:51', '2025-01-04', NULL, 0, 0.00, NULL, 'sỹ', '03876916722'),
(5, 0, 1, '2024-12-28 23:30:32', '2025-01-08', NULL, 0, 0.00, NULL, 'sơn 123', '03928321'),
(6, 0, 1, '2024-12-28 23:00:16', '2025-01-04', NULL, 0, 0.00, NULL, 'sơn 123', '03928321'),
(7, 0, 1, '2024-12-28 23:10:52', '2025-01-10', NULL, 0, 0.00, NULL, 'hoàng', '03928321'),
(8, 0, 2, '2024-12-28 23:11:44', '2025-01-04', NULL, 0, 0.00, NULL, 'tuấn', '113'),
(9, 0, 1, '2024-12-28 23:11:51', '2025-01-04', NULL, 0, 0.00, NULL, 'sỹ', '03876916722'),
(10, 0, 1, '2024-12-28 23:30:32', '2025-01-08', NULL, 0, 0.00, NULL, 'sơn 123', '03928321');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `username`, `email`, `password`, `phoneNumber`, `address`) VALUES
(1, 'bùi', 'văn', 'sơn', 'sonbui195304@gmail.com', 'son123', NULL, NULL),
(2, 'bùi', 'văn', '123', 's@gmail.com', '123', '1233', ''),
(3, 'dsf', 's', 's', 'son1234@gmail.com', 'sssss', 'sss', ''),
(4, 'bùi', 'văn', 'sơn 12', 'son@gmail.com', 'son123', '113', ''),
(5, 'bùi', 'văn', 'an', 'sonbui195305@gmail.com', '$2y$10$FgXuRn1TZ5eVVvM5p2MZxedRYPn1JlRA022Q1tbOJj22zzxt9F54i', NULL, NULL),
(6, 'bùi', 'văn', 'Nhất', 'thom.v@some.com', '$2y$10$OMhY4SK0Aq7STOOxG32kn.N..2WPLnfGCiaXb2Hc/Oquy9OBSYnLO', NULL, NULL),
(9, 'Sỹ', 'Đỗ Văn Sơn', 'sonsy1902', 'sonsy1902@gmail.com', '123456789', NULL, NULL),
(10, 'abc', 'cba', 'sonsy2004', 'narutotruyenky1@gmail.com', '1234', '1234567', '');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Chỉ mục cho bảng `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
