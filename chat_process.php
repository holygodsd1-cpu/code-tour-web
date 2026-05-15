<?php
session_start();

// Thiết lập tiêu đề trả về là JSON
header('Content-Type: application/json');

// Lấy dữ liệu từ AJAX gửi lên
$data = json_decode(file_get_contents("php://input"), true);
$user_msg = $data['message'] ?? '';

if (empty($user_msg)) {
    echo json_encode(['reply' => 'Bạn muốn hỏi gì về tour du lịch ạ?']);
    exit;
}

// --- PHẦN KẾT NỐI API AI (Ví dụ đơn giản hoặc dùng Logic If/Else nếu chưa có API Key) ---
// Nếu Khoa có API Key của Gemini/OpenAI thì điền vào đây. 
// Tạm thời mình làm logic "Bot thông minh" tự động phản hồi theo từ khóa cho cậu test:

$reply = "Xin lỗi, mình chưa hiểu ý bạn. Bạn cần tư vấn về Tour miền Bắc, Trung hay Nam?";

if (str_contains(strtolower($user_msg), 'xin chào') || str_contains(strtolower($user_msg), 'hi')) {
    $reply = "Chào " . ($_SESSION['fullname'] ?? 'bạn') . "! Chúc bạn một ngày tốt lành. Bạn đang quan tâm đến chuyến đi nào?";
} elseif (str_contains(strtolower($user_msg), 'giá') || str_contains(strtolower($user_msg), 'bao nhiêu')) {
    $reply = "Giá tour bên mình rất đa dạng, dao động từ 1.000.000đ đến 20.000.000đ. Bạn có thể dùng bộ lọc ở trang Tours để xem chi tiết nhé!";
} elseif (str_contains(strtolower($user_msg), 'liên hệ') || str_contains(strtolower($user_msg), 'sdt')) {
    $reply = "Bạn có thể gọi hotline: 0123.456.789 để được hỗ trợ gấp ạ.";
}

echo json_encode(['reply' => $reply]);