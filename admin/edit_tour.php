<?php
session_start();
include("../config/database.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM tours WHERE id=$id");
$tour = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['tour_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $image = $_FILES['image']['name'];

    if ($image != "") {
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/" . $image);
        mysqli_query($conn, "UPDATE tours SET tour_name='$name', description='$description', price='$price', location='$location', duration='$duration', image='$image' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE tours SET tour_name='$name', description='$description', price='$price', location='$location', duration='$duration' WHERE id=$id");
    }
    header("Location: manage_tours.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Tour | Admin System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center; /* Căn giữa theo chiều dọc */
            justify-content: center; /* Căn giữa theo chiều ngang */
            padding: 20px;
        }

        .edit-card {
            background: #1e1e1e;
            width: 100%;
            max-width: 700px; /* Độ rộng tối đa của form */
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .page-title {
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 35px;
            border-bottom: 2px solid #ffc107;
            display: inline-block;
            width: 100%;
            padding-bottom: 10px;
        }

        /* Tùy chỉnh các ô Input */
        .form-label {
            color: #ffc107;
            font-weight: 600;
            font-size: 0.9rem;
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
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ffc107 !important;
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.2) !important;
        }

        .current-img-preview {
            border-radius: 15px;
            border: 2px solid #333;
            margin: 15px 0;
            object-fit: cover;
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }

        /* Nút bấm */
        .btn-group-custom {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-update {
            background: linear-gradient(45deg, #ffc107, #ff9800);
            border: none;
            color: #000;
            font-weight: 700;
            border-radius: 15px;
            padding: 12px;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.3);
        }

        .btn-cancel {
            background: #333;
            border: none;
            color: #bbb;
            font-weight: 600;
            border-radius: 15px;
            padding: 12px;
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
        }

        .btn-cancel:hover {
            background: #444;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="edit-card">
    <h2 class="page-title"><i class="bi bi-pencil-square me-2"></i>Cập Nhật Tour</h2>

    <form method="POST" enctype="multipart/form-data">
        
        <div class="row">
            <div class="col-md-4 text-center">
                <label class="form-label justify-content-center">Ảnh hiện tại</label>
                <img src="../uploads/<?= $tour['image'] ?>" width="100%" class="current-img-preview">
            </div>
            <div class="col-md-8">
                <label class="form-label"><i class="bi bi-image"></i> Thay đổi ảnh (Nếu có)</label>
                <input type="file" name="image" class="form-control mb-3">
                
                <label class="form-label"><i class="bi bi-tag-fill"></i> Tên Tour</label>
                <input type="text" name="tour_name" value="<?= htmlspecialchars($tour['tour_name']) ?>" class="form-control mb-3" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-chat-left-text-fill"></i> Mô tả Tour</label>
            <textarea name="description" rows="4" class="form-control" required><?= htmlspecialchars($tour['description']) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-cash-stack"></i> Giá Tour (VNĐ)</label>
                <input type="number" name="price" value="<?= $tour['price'] ?>" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Địa điểm</label>
                <input type="text" name="location" value="<?= htmlspecialchars($tour['location']) ?>" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-clock-fill"></i> Thời gian (Ví dụ: 2 Ngày 1 Đêm)</label>
            <input type="text" name="duration" value="<?= htmlspecialchars($tour['duration']) ?>" class="form-control" required>
        </div>

        <div class="btn-group-custom">
            <button type="submit" class="btn-update shadow">Lưu thay đổi</button>
            <a href="manage_tours.php" class="btn btn-cancel">Quay lại</a>
        </div>
    </form>
</div>

</body>
</html>