<?php
include("../config/database.php");

// Lấy danh sách tour để đổ vào select
$tours = mysqli_query($conn,"SELECT * FROM tours");

if(isset($_POST['add'])){
    $tour_id = $_POST['tour_id'];
    $date = $_POST['departure_date'];
    $price = $_POST['price'];

    mysqli_query($conn,"INSERT INTO tour_schedule (tour_id, departure_date, price) VALUES ('$tour_id', '$date', '$price')");

    echo "<script>alert('Đã thêm lịch khởi hành thành công!'); window.location.href='manage_tours.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Lịch Khởi Hành | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .schedule-card {
            background: #1e1e1e;
            width: 100%;
            max-width: 550px; /* Bóp gọn form lại cho đẹp */
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }

        .btn-back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-back-link:hover { color: #fff; }

        .page-title {
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        .form-label {
            color: #0d6efd; /* Màu xanh dương làm điểm nhấn */
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control, .form-select {
            background: #121212 !important;
            border: 1px solid #333 !important;
            color: white !important;
            border-radius: 12px;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.2) !important;
        }

        /* Nút submit */
        .btn-add-schedule {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            margin-top: 20px;
            transition: 0.3s;
        }

        .btn-add-schedule:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }

        /* Tùy chỉnh icon lịch của input date trên Chrome/Edge */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="schedule-card">
    <a href="javascript:history.back()" class="btn-back-link">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>

    <h2 class="page-title">Thêm Lịch Khởi Hành</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-map"></i> Chọn Tour Du Lịch</label>
            <select name="tour_id" class="form-select" required>
                <option value="" selected disabled>-- Chọn một tour từ danh sách --</option>
                <?php while($t = mysqli_fetch_assoc($tours)){ ?>
                    <option value="<?php echo $t['id']; ?>">
                        <?php echo $t['tour_name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-calendar-event"></i> Ngày khởi hành</label>
            <input type="date" name="departure_date" class="form-control" required>
        </div>

        <div class="mb-4">
            <label class="form-label"><i class="bi bi-currency-exchange"></i> Giá ưu đãi ngày này (VNĐ)</label>
            <input type="number" name="price" class="form-control" placeholder="Ví dụ: 4500000" required>
            <small class="text-white-50 mt-1 d-block" style="font-size: 0.75rem;">
                * Để trống nếu giữ nguyên giá gốc của tour.
            </small>
        </div>

        <button type="submit" name="add" class="btn btn-add-schedule shadow">
            <i class="bi bi-plus-lg me-2"></i> Xác nhận thêm lịch
        </button>
    </form>
</div>

</body>
</html>