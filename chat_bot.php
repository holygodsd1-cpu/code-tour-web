<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$message_lower = mb_strtolower($message, 'UTF-8'); // Chuyển về chữ thường để so sánh

if (!empty($message)) {
    $reply = "";

    // 1. Kiểm tra xem có phải là lời chào không (Tránh việc "hi" lôi tour ra)
    $hello_keywords = ['hi', 'hello', 'chào', 'xin chào', 'ê', 'hey'];
    
    // Nếu tin nhắn chỉ có 1-2 từ và nằm trong danh sách chào hỏi
    if (in_array($message_lower, $hello_keywords) || strlen($message) <= 3) {
        $reply = "Chào <strong>" . ($_SESSION['fullname'] ?? 'bạn') . "</strong>! Travela đang nghe đây. Bạn muốn tìm tour đi đâu hay cần hỏi giá nè?";
    } 
    else {
        // 2. Nếu không phải chào hỏi thì mới bắt đầu lục tung Database
        try {
            $sql = "SELECT tour_name, price FROM tours WHERE tour_name LIKE ? OR description LIKE ? LIMIT 3";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%$message%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $reply = "Travela tìm thấy vài tour đúng ý bạn đây: <br><br>";
                while ($row = $result->fetch_assoc()) {
                    $reply .= "📍 <strong>" . htmlspecialchars($row['tour_name']) . "</strong><br>";
                    $reply .= "💰 Giá: " . number_format($row['price'], 0, ',', '.') . " VNĐ<br><hr style='margin: 5px 0; border-color: rgba(255,255,255,0.1);'>";
                }
                $reply .= "Bạn muốn đặt tour nào trong số này không?";
            } else {
                // 3. Nếu tìm không thấy tour thì "tám" chuyện linh tinh
                if (stripos($message, 'giá') !== false) {
                    $reply = "Giá tour bên mình rất đa dạng, từ bình dân đến cao cấp đều có. Cậu định chi khoảng bao nhiêu để mình lọc cho?";
                } else {
                    $reply = "Hì, câu này khó quá. Cậu thử gõ tên địa danh (vd: Đà Lạt, Nha Trang) để mình tìm tour cho chuẩn nhé!";
                }
            }
            $stmt->close();
        } catch (Exception $e) {
            $reply = "Lỗi hệ thống: " . $e->getMessage();
        }
    }

    echo $reply;
}
$conn->close();
?>