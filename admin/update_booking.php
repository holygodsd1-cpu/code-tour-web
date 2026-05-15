<?php
session_start();
include("../config/database.php");

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['action'])){

    $id = $_GET['id'];
    $action = $_GET['action'];

    // Mặc định trạng thái ban đầu để tránh lỗi biến không xác định
    $status = "";

    if ($action == "confirm") {
        $status = "Confirmed";
    } elseif ($action == "cancel") {
        $status = "Cancelled";
    }

    // Thực hiện cập nhật nếu trạng thái hợp lệ
    if ($status != "") {
        // Thêm nháy đơn quanh $id để bảo mật hơn một chút
        mysqli_query($conn, "UPDATE bookings SET status='$status' WHERE id='$id'");
    }
}

// SỬA LỖI TẠI ĐÂY: Đổi manage_bookings.php thành manage_booking.php
header("Location: manage_booking.php");
exit();
?>