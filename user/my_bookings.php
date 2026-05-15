<?php
session_start();
include("../config/database.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT 
        b.id,
        t.tour_name,
        t.image,
        b.quantity,
        b.total_price,
        b.booking_date,
        b.status
        FROM bookings b
        JOIN tours t ON b.tour_id = t.id
        WHERE b.user_id = $user_id
        ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);
?>

<?php include("../layout/header.php"); ?>

<style>
/* --- TỔNG THỂ DARK THEME --- */
body { 
    background-color: #121212; 
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
}

/* --- KHUNG BAO BỌC BẢNG --- */
.booking-wrapper {
    background: #1e1e1e;
    border-radius: 30px;
    padding: 40px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    border: 1px solid rgba(255,255,255,0.05);
}

.page-title {
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-left: 5px solid #27ae60;
    padding-left: 15px;
}

/* --- TÙY CHỈNH BẢNG (CARD TABLE) --- */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 15px; /* Tạo khoảng cách giữa các dòng */
    color: #e0e0e0;
}

/* Tiêu đề cột */
.custom-table th {
    background: transparent;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 10px 20px;
    border: none;
    letter-spacing: 1px;
}

/* Hàng dữ liệu (Row) */
.custom-table tbody tr {
    background: #181818;
    transition: all 0.3s ease;
}

.custom-table tbody tr:hover {
    transform: translateY(-5px);
    background: #222;
    box-shadow: 0 10px 25px rgba(39, 174, 96, 0.15);
}

/* Ô dữ liệu (Cell) */
.custom-table td {
    padding: 15px 20px;
    vertical-align: middle;
    border-top: 1px solid rgba(255,255,255,0.02);
    border-bottom: 1px solid rgba(255,255,255,0.02);
}

/* Bo góc cho hàng */
.custom-table td:first-child { 
    border-left: 1px solid rgba(255,255,255,0.02); 
    border-top-left-radius: 15px; 
    border-bottom-left-radius: 15px; 
}
.custom-table td:last-child { 
    border-right: 1px solid rgba(255,255,255,0.02); 
    border-top-right-radius: 15px; 
    border-bottom-right-radius: 15px; 
}

/* --- THÀNH PHẦN BÊN TRONG BẢNG --- */
.tour-img {
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
}

.tour-name {
    font-weight: 700;
    font-size: 1.1rem;
    color: #fff;
}

.price-text {
    color: #27ae60;
    font-weight: 800;
    font-size: 1.05rem;
}

.badge-custom {
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
}
</style>

<div class="container mt-5 mb-5">
    <div class="booking-wrapper">
        <h2 class="page-title">Tour Đã Đặt</h2>
        
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tour</th>
                        <th class="text-center">Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Kiểm tra nếu có dữ liệu mới in ra, tránh lỗi bảng trống
                    if(mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td style="width: 150px;">
                            <img src="../uploads/<?= $row['image'] ?>" class="tour-img" width="120" height="80" alt="Tour Image">
                        </td>
                        <td>
                            <div class="tour-name"><?= $row['tour_name'] ?></div>
                            <small class="text-white-50">Mã booking: #<?= $row['id'] ?></small>
                        </td>
                        <td class="text-center fs-5 fw-bold text-white-50">
                            <?= $row['quantity'] ?>
                        </td>
                        <td class="price-text">
                            <?= number_format($row['total_price']) ?> VNĐ
                        </td>
                        <td class="text-white-50">
                            <?= date('d/m/Y', strtotime($row['booking_date'])) ?>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <span class="badge bg-warning text-dark badge-custom shadow-sm"><i class="bi bi-hourglass-split me-1"></i> Chờ xử lý</span>
                            <?php } elseif ($row['status'] == 'Confirmed') { ?>
                                <span class="badge bg-success badge-custom shadow-sm"><i class="bi bi-check2-circle me-1"></i> Đã xác nhận</span>
                            <?php } else { ?>
                                <span class="badge bg-danger badge-custom shadow-sm"><i class="bi bi-x-circle me-1"></i> Đã huỷ</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        // Nếu user chưa đặt tour nào
                        echo "<tr><td colspan='6' class='text-center py-5 text-white-50 fs-5'>Bạn chưa có chuyến đi nào được đặt.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../layout/footer.php"); ?>