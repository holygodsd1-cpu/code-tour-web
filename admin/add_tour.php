<?php
session_start();
include("../config/database.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sử dụng mysqli_real_escape_string để tránh lỗi khi nhập dấu ngoặc đơn
    $name = mysqli_real_escape_string($conn, $_POST['tour_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $itinerary = mysqli_real_escape_string($conn, $_POST['itinerary']);
    $include_service = mysqli_real_escape_string($conn, $_POST['include_service']);
    $exclude_service = mysqli_real_escape_string($conn, $_POST['exclude_service']);

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "../uploads/" . $image);

    $sql = "INSERT INTO tours
    (tour_name, description, price, location, duration, itinerary, include_service, exclude_service, image)
    VALUES
    ('$name','$description','$price','$location','$duration','$itinerary','$include_service','$exclude_service','$image')";

    mysqli_query($conn, $sql);
    header("Location: manage_tours.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Tour Mới | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Inter', sans-serif;
            padding: 40px 0;
        }

        .add-card {
            background: #1e1e1e;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.05);
            max-width: 1000px;
            margin: auto;
        }

        .page-title {
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
            border-left: 6px solid #198754;
            padding-left: 20px;
        }

        /* Tùy chỉnh Input & Textarea */
        .form-label {
            color: #20c997;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            background: #121212 !important;
            border: 1px solid #333 !important;
            color: white !important;
            border-radius: 12px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #20c997 !important;
            box-shadow: 0 0 12px rgba(32, 201, 151, 0.2) !important;
        }

        /* Nút bấm gọn gàng */
        .action-bar {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #333;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn-save {
            background: linear-gradient(45deg, #198754, #20c997);
            border: none;
            padding: 12px 35px;
            border-radius: 12px;
            font-weight: 700;
            color: white;
            transition: 0.3s;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(25, 135, 84, 0.3);
        }

        .btn-back {
            background: #333;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            color: #bbb;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-back:hover { background: #444; color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <div class="add-card shadow">
        <h2 class="page-title">Thêm Tour Du Lịch Mới</h2>

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-5">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-image"></i> Ảnh đại diện tour</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-tag"></i> Tên tour</label>
                        <input type="text" name="tour_name" class="form-control" placeholder="Nhập tên tour..." required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-currency-dollar"></i> Giá (VNĐ)</label>
                            <input type="number" name="price" class="form-control" placeholder="5000000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-clock"></i> Thời gian</label>
                            <input type="text" name="duration" class="form-control" placeholder="3 Ngày 2 Đêm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-geo-alt"></i> Địa điểm</label>
                        <input type="text" name="location" class="form-control" placeholder="Ví dụ: Hạ Long, Đà Nẵng...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-chat-dots"></i> Mô tả ngắn</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Tóm tắt về tour..."></textarea>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-calendar3"></i> Lịch trình chi tiết</label>
                        <textarea name="itinerary" class="form-control" rows="6" placeholder="Ngày 1: ... Ngày 2: ..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-plus-circle text-success"></i> Dịch vụ bao gồm</label>
                        <textarea name="include_service" class="form-control" rows="4" placeholder="- Khách sạn 4 sao&#10;- Vé máy bay..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-dash-circle text-danger"></i> Không bao gồm</label>
                        <textarea name="exclude_service" class="form-control" rows="4" placeholder="- Chi phí cá nhân..."></textarea>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <a href="manage_tours.php" class="btn btn-back">Huỷ bỏ</a>
                <button type="submit" class="btn btn-save shadow"><i class="bi bi-cloud-arrow-up me-2"></i>Lưu Tour</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>