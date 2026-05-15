-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 30, 2026 lúc 07:31 PM
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
-- Cơ sở dữ liệu: `tour_website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `departure_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `tour_id`, `booking_date`, `quantity`, `total_price`, `status`, `created_at`, `departure_date`) VALUES
(10, 1, 3, '2026-03-08', 2, 10000000.00, 'Confirmed', '2026-03-08 08:15:36', '2026-03-10'),
(11, 2, 3, '2026-03-08', 2, 10000000.00, 'Confirmed', '2026-03-08 08:31:15', '2026-03-10'),
(12, 1, 3, '2026-03-09', 2, 10000000.00, 'Cancelled', '2026-03-09 02:43:43', '2026-03-10'),
(15, 2, 7, '2026-03-09', 2, 13000000.00, 'Confirmed', '2026-03-09 03:47:57', '2026-03-29'),
(16, 2, 3, '2026-03-09', 1, 5000000.00, 'Cancelled', '2026-03-09 04:17:16', '2026-03-10'),
(17, 2, 3, '2026-03-09', 1, 5000000.00, 'Confirmed', '2026-03-09 13:04:28', '2026-03-10'),
(18, 2, 7, '2026-03-09', 1, 6500000.00, 'Pending', '2026-03-09 13:19:02', '2026-03-29'),
(19, 2, 7, '2026-03-09', 1, 6500000.00, 'Pending', '2026-03-09 13:20:50', '2026-03-29'),
(20, 2, 3, '2026-03-09', 1, 5000000.00, 'Pending', '2026-03-09 13:22:00', '2026-03-10'),
(21, 2, 7, '2026-03-09', 1, 6500000.00, 'Pending', '2026-03-09 13:23:13', '2026-03-29'),
(22, 2, 3, '2026-03-09', 1, 5000000.00, 'Pending', '2026-03-09 13:24:23', '2026-03-10'),
(23, 2, 8, '2026-03-13', 1, 2500000.00, 'Pending', '2026-03-13 14:12:59', '2026-03-28'),
(24, 2, 8, '2026-03-28', 2, 5000000.00, 'Confirmed', '2026-03-28 02:28:49', '2026-03-28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `tour_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 7, 2, 4, 'Rất vuiiii', '2026-03-09 18:29:59'),
(2, 8, 2, 5, 'toi hai long voi tour nay', '2026-03-28 02:28:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `tour_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `itinerary` text DEFAULT NULL,
  `include_service` text DEFAULT NULL,
  `exclude_service` text DEFAULT NULL,
  `highlight` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `tour_name`, `description`, `price`, `image`, `location`, `duration`, `created_at`, `itinerary`, `include_service`, `exclude_service`, `highlight`) VALUES
(3, 'Du Lịch Vịnh Hạ Long', 'Vịnh Hạ Long là một trong những điểm du lịch nổi tiếng nhất Việt Nam, được UNESCO công nhận là Di sản Thiên nhiên Thế giới. Tour du lịch Vịnh Hạ Long mang đến cho du khách cơ hội khám phá hàng nghìn đảo đá vôi kỳ vĩ, làn nước xanh ngọc bích và nhiều hang động tuyệt đẹp.\r\n\r\n✨ Điểm nổi bật của tour\r\n\r\n🚢 Du thuyền tham quan vịnh: Du khách sẽ di chuyển bằng tàu để chiêm ngưỡng hàng nghìn hòn đảo đá vôi lớn nhỏ trên vịnh.\r\n\r\n🏝 Khám phá các đảo nổi tiếng như Hòn Trống Mái – biểu tượng của du lịch Hạ Long.\r\n\r\n🕳 Tham quan hang động kỳ ảo như Hang Sửng Sốt với những nhũ đá tự nhiên độc đáo.\r\n\r\n🚣 Chèo kayak hoặc thuyền nan khám phá các hang nước và làng chài trên vịnh.\r\n\r\n🌅 Ngắm hoàng hôn và bình minh trên vịnh, một trải nghiệm rất được du khách yêu thích.\r\n\r\n🍤 Ẩm thực đặc sắc\r\n\r\nTrong tour, du khách còn được thưởng thức nhiều món hải sản tươi ngon của Quảng Ninh như: tôm, cua, ghẹ, mực và các món đặc sản địa phương.\r\n\r\n📅 Thời gian tour phổ biến\r\n\r\nTour 1 ngày: Tham quan vịnh, hang động và ăn trưa trên tàu.\r\n\r\nTour 2 ngày 1 đêm: Nghỉ đêm trên du thuyền, tham gia nhiều hoạt động như câu mực đêm, chèo kayak.\r\n\r\nTour 3 ngày 2 đêm: Khám phá sâu hơn các đảo và bãi biển đẹp trên vịnh.\r\n\r\n📍 Trải nghiệm đáng nhớ\r\n\r\nTour Vịnh Hạ Long không chỉ mang đến cảnh quan thiên nhiên hùng vĩ mà còn giúp du khách thư giãn, hòa mình vào không gian biển đảo yên bình và khám phá nét văn hóa đặc trưng của vùng vịnh.', 5000000.00, 'Screenshot 2026-03-08 143548.png', 'Vịnh Hạ Long', '2 Ngày 1 Đêm', '2026-03-08 07:39:20', '🗺 Lịch trình tour khám phá Vịnh Hạ Long (2 ngày 1 đêm)\r\n📅 Ngày 1: Hà Nội – Hạ Long – Tham quan vịnh\r\n\r\n08:00 – Xe và hướng dẫn viên đón khách tại khách sạn, khởi hành đi Vịnh Hạ Long.\r\n\r\n11:30 – Đến cảng, làm thủ tục lên tàu du lịch.\r\n\r\n12:00 – Dùng bữa trưa trên tàu với các món hải sản đặc sản.\r\n\r\n13:30 – Tham quan các đảo đá vôi nổi tiếng trên vịnh như Hòn Trống Mái.\r\n\r\n14:30 – Khám phá hang động nổi tiếng Hang Sửng Sốt.\r\n\r\n16:00 – Chèo kayak hoặc đi thuyền nan tham quan khu vực vịnh.\r\n\r\n18:00 – Ngắm hoàng hôn trên vịnh, tham gia tiệc nhẹ trên tàu.\r\n\r\n19:00 – Ăn tối trên tàu.\r\n\r\n21:00 – Tự do nghỉ ngơi, câu mực đêm hoặc thư giãn trên boong tàu.\r\n\r\n📅 Ngày 2: Khám phá vịnh – Trở về\r\n\r\n06:00 – Ngắm bình minh trên vịnh, tập thể dục hoặc chụp ảnh.\r\n\r\n07:00 – Ăn sáng trên tàu.\r\n\r\n08:00 – Tham quan đảo hoặc bãi biển đẹp trong khu vực vịnh.\r\n\r\n09:30 – Quay lại tàu, làm thủ tục trả phòng.\r\n\r\n10:30 – Ăn trưa nhẹ trên tàu.\r\n\r\n11:30 – Tàu cập bến, xe đưa khách trở về Hà Nội.\r\n\r\n15:00 – 16:00 – Về đến Hà Nội, kết thúc chương trình tour.', 'Xe đưa đón\r\n\r\nVé tham quan vịnh\r\n\r\nHướng dẫn viên\r\n\r\nCác bữa ăn theo chương trình\r\n\r\nNghỉ đêm trên tàu', 'Chi phí cá nhân\r\n\r\nĐồ uống ngoài chương trình\r\n\r\nThuế VAT', NULL),
(7, 'Du Lịch Đà Lạt', '🌄 Tour du lịch Đà Lạt\r\n\r\nĐà Lạt được mệnh danh là “Thành phố ngàn hoa”, nổi tiếng với khí hậu mát mẻ quanh năm, cảnh quan thiên nhiên thơ mộng và nhiều điểm tham quan hấp dẫn. Tour Đà Lạt mang đến cho du khách cơ hội khám phá núi rừng Tây Nguyên, thưởng thức đặc sản địa phương và tận hưởng không khí trong lành.', 6500000.00, 'samten-hills-0.jpg', 'Đà Lạt', '3 Ngày 2 Đêm', '2026-03-08 08:38:57', '📅 Ngày 1: Đến Đà Lạt – Tham quan trung tâm\r\n\r\nĐón khách tại sân bay / bến xe và về khách sạn nhận phòng.\r\n\r\nTham quan Hồ Xuân Hương – biểu tượng của thành phố.\r\n\r\nGhé thăm Quảng trường Lâm Viên và chụp hình với công trình nụ hoa atiso khổng lồ.\r\n\r\nBuổi tối tự do khám phá Chợ đêm Đà Lạt và thưởng thức đặc sản địa phương.\r\n\r\n📅 Ngày 2: Khám phá thiên nhiên Đà Lạt\r\n\r\nTham quan Thác Datanla – một trong những thác nước đẹp nhất Đà Lạt.\r\n\r\nKhám phá Thiền viện Trúc Lâm và ngắm cảnh Hồ Tuyền Lâm.\r\n\r\nTham quan vườn hoa hoặc trang trại dâu tây địa phương.\r\n\r\nBuổi tối tham gia giao lưu cồng chiêng Tây Nguyên và thưởng thức đặc sản nướng.\r\n\r\n📅 Ngày 3: Check-in điểm nổi tiếng – Kết thúc tour\r\n\r\nTham quan Nhà thờ Domaine de Marie.\r\n\r\nCheck-in Đồi chè Cầu Đất với khung cảnh xanh mát.\r\n\r\nMua đặc sản Đà Lạt (mứt, trà, cà phê) làm quà.\r\n\r\nXe đưa khách ra sân bay / bến xe, kết thúc chương trình', 'Bao gồm:\r\n\r\nKhách sạn\r\n\r\nXe đưa đón\r\n\r\nVé tham quan\r\n\r\nHướng dẫn viên\r\n\r\nCác bữa ăn theo chương trình', '', NULL),
(8, 'Du Lịch Phan Thiết', '', 2500000.00, 'Mui-Ne-Bay-Phan-Thiet-Vietnam.jpg', 'Phan Thiết', '2 Ngày 1 Đêm', '2026-03-09 18:14:48', 'Chào Khoa! Rất vui được đồng hành cùng bạn. Với tư cách là một freelancer tương lai, chắc hẳn bạn sẽ thích một lịch trình vừa đủ để \"refresh\" năng lượng nhưng cũng có những không gian yên tĩnh để có thể mở laptop làm việc nếu cần.\r\n\r\nDưới đây là lịch trình chi tiết cho chuyến đi Phan Thiết - Mũi Né 3 ngày 2 đêm được thiết kế cân bằng giữa trải nghiệm và nghỉ dưỡng.\r\n\r\n## Lịch Trình Chi Tiết\r\n### Ngày 1: TP.HCM – Phan Thiết – Check-in & Biển Chiều\r\nSáng: Di chuyển từ TP.HCM đi Phan Thiết (khoảng 2.5 - 3 tiếng qua cao tốc).\r\n\r\nTrưa: Đến Phan Thiết, dùng bữa trưa với đặc sản Lẩu Thả (món ăn đặc trưng nhất của vùng biển này). Sau đó về khách sạn/resort nhận phòng nghỉ ngơi.\r\n\r\nChiều: * Tham quan Tháp Chàm Poshanư: Tìm hiểu về kiến trúc và văn hóa Chăm Pa.\r\n\r\nTắm biển tại bãi đá Ông Địa hoặc bãi tắm của resort.\r\n\r\nTối: Ăn hải sản dọc bờ kè Hàm Tiến. Dạo phố Tây Mũi Né về đêm.\r\n\r\n### Ngày 2: Bàu Trắng – Suối Tiên – Ngắm Hoàng Hôn\r\nSáng (4:30 - 5:00): Đi ngắm bình minh tại Đồi Cát Trắng (Bàu Trắng). Đây là \"tiểu sa mạc Sahara\" của Việt Nam. Bạn có thể trải nghiệm xe mô tô địa hình (ATV) chạy trên cát rất phấn khích.\r\n\r\n9:00: Ghé thăm Suối Tiên – lội dòng suối mát lạnh giữa những nhũ đá vôi màu cam đỏ rực rỡ.\r\n\r\nTrưa: Thưởng thức bánh xèo, bánh căn Phan Thiết nổi tiếng.\r\n\r\nChiều: Ghé Làng Chài Mũi Né xem cảnh tấp nập của ngư dân, sau đó đón hoàng hôn tại Hana Beach hoặc các quán cafe chill ven biển.\r\n\r\nTối: Tự do khám phá hoặc thưởng thức show diễn Fishermen Show (nếu có lịch diễn) – show nghệ thuật huyền thoại làng chài.', 'Lưu trú: 2 đêm tại Resort/Khách sạn (tùy chọn tiêu chuẩn từ 3-5 sao).\r\n\r\nVận chuyển: Xe du lịch đời mới máy lạnh suốt tuyến hoặc vé tàu hỏa/xe giường nằm khứ hồi.\r\n\r\nĂn uống: Các bữa sáng tại khách sạn và các bữa chính theo chương trình (thực đơn hải sản, đặc sản địa phương).\r\n\r\nTham quan: Vé vào cổng các điểm du lịch có trong lịch trình (Suối Tiên, Tháp Poshanư, Làng Chài Xưa).\r\n\r\nBảo hiểm: Bảo hiểm du lịch nội địa.\r\n\r\nHướng dẫn viên: (Nếu đi theo tour) Vui vẻ, nhiệt tình, am hiểu kiến thức địa phương.', 'Chi phí cá nhân: Giặt ủi, điện thoại, thức uống trong các bữa ăn.\r\n\r\nTrò chơi tại đồi cát: Thuê xe mô tô địa hình (ATV) hoặc xe Jeep chạy trên đồi cát (thường dao động 400k - 600k/xe).\r\n\r\nShow diễn: Vé xem Fishermen Show.\r\n\r\nThuế VAT: (Nếu bạn cần lấy hóa đơn đỏ cho công ty).\r\n\r\nTiền Tip: Cho tài xế và hướng dẫn viên (nếu có).', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_schedule`
--

CREATE TABLE `tour_schedule` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `available_slots` int(11) DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_schedule`
--

INSERT INTO `tour_schedule` (`id`, `tour_id`, `departure_date`, `price`, `available_slots`) VALUES
(8, 3, '2026-03-10', 5000000.00, 20),
(9, 3, '2026-03-20', 5500000.00, 20),
(10, 3, '2026-03-28', 3500000.00, 20),
(11, 7, '2026-03-29', 3500000.00, 20),
(12, 8, '2026-03-28', 2500000.00, 20),
(13, 3, '2026-03-31', 2500000.00, 20);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default_avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`, `phone`, `address`, `avatar`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$tAhmX98skaRfM6snsdSeQO6vYuJcc38RxR4mq0fh5Q5ZYXQswPnju', 'admin', '2026-03-03 13:25:38', '1234567890', '', 'user_2_1773024145.jpg'),
(2, 'Lưu Tấn Khoa', '123@gmail.com', '$2y$10$ucq1P2e73DyjoROKdT7wP.UMEAu/raW1IV8aAi3pivy5ttm3JU0VO', 'user', '2026-03-03 12:49:01', '0915759253', 'Cà Mau', 'user_1_1773025143.jpg'),
(3, 'Ngô Chí Bảo', 'Bao220022@gmail.com', '$2y$10$/ZSd9Pdxewo5qKIMKBSoHOAu/MJvUmIlmcIJRDv5E5Rwd97X1d3Gm', 'user', '2026-03-09 13:31:13', '0949563210', NULL, 'default_avatar.png'),
(6, 'Ngo Chi Bao', 'Bao123@gmail.com', '$2y$10$KT8Spl4T27KlOsPVO87mh.fKhBIhgF1VpqykJmjc5ufl1NDNKmTR.', 'user', '2026-03-28 02:35:17', '123456', NULL, 'default_avatar.png');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tour_schedule`
--
ALTER TABLE `tour_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `tour_schedule`
--
ALTER TABLE `tour_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_schedule`
--
ALTER TABLE `tour_schedule`
  ADD CONSTRAINT `tour_schedule_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
