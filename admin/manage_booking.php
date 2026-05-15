<?php
session_start();
include("../config/database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include("../layout/header.php");

// Sửa b.booking_date thành b.created_at cho khớp với database
$sql = "SELECT 
        b.id,
        u.fullname,
        u.phone,
        u.email,
        t.tour_name,
        b.quantity,
        b.total_price,
        b.created_at, 
        b.status
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN tours t ON b.tour_id = t.id
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);

// Kiểm tra nếu query lỗi (giúp Khoa biết chính xác lỗi ở đâu)
if (!$result) {
    die("Lỗi SQL: " . mysqli_error($conn));
}
?>

<style>
/* --- GIỮ NGUYÊN STYLE CỦA KHOA --- */
body { 
    background-color: #121212; 
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
}

.admin-container {
    background: #1e1e1e;
    border-radius: 25px;
    padding: 35px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    border: 1px solid rgba(255,255,255,0.03);
    margin-top: 30px;
    margin-bottom: 50px;
}

.title-section {
    border-left: 6px solid #f39c12; 
    padding-left: 15px;
    margin-bottom: 30px;
}

.title-section h2 {
    color: #fff;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 1.6rem;
    margin: 0;
}

.custom-dark-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px; 
}

.custom-dark-table th {
    background: transparent;
    color: #777;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    padding: 10px 15px;
    border: none;
}

.custom-dark-table tbody tr {
    background: #252525;
    transition: 0.3s ease;
}

.custom-dark-table tbody tr:hover {
    background: #2d2d2d;
    transform: scale(1.01);
}

.custom-dark-table td {
    padding: 15px;
    vertical-align: middle;
    border: none;
}

.custom-dark-table td:first-child { border-radius: 12px 0 0 12px; }
.custom-dark-table td:last-child { border-radius: 0 12px 12px 0; }

.id-text {
    font-family: 'Courier New', Courier, monospace;
    font-weight: bold;
    color: #f39c12;
}

.user-info .name {
    display: block;
    color: #fff;
    font-weight: 700;
}
.user-info small {
    display: block;
    color: #888;
    font-size: 0.85rem;
}

.tour-title {
    color: #fff;
    font-weight: 600;
}

.price-box {
    color: #2ecc71;
    font-weight: 800;
}

.badge-status {
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

.btn-action {
    border-radius: 8px;
    font-weight: 600;
    padding: 6px 12px;
    font-size: 0.8rem;
    border: none;
    transition: 0.3s;
}

.btn-confirm { background: #27ae60; color: white; text-decoration: none; display: inline-block; }
.btn-confirm:hover { background: #2ecc71; box-shadow: 0 0 15px rgba(46, 204, 113, 0.4); color: white; }

.btn-cancel { background: #c0392b; color: white; text-decoration: none; display: inline-block; }
.btn-cancel:hover { background: #e74c3c; box-shadow: 0 0 15px rgba(231, 76, 60, 0.4); color: white; }

.status-processed {
    font-style: italic;
    color: #555;
    font-size: 0.9rem;
}
</style>

<div class="container">
    <div class="admin-container shadow">
        <div class="title-section d-flex align-items-center">
            <h2>Quản Lý Đơn Đặt</h2>
        </div>

        <div class="table-responsive">
            <table class="custom-dark-table">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Khách Hàng</th>
                        <th>Chuyến Tour</th>
                        <th class="text-center">Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><span class="id-text">#<?= $row['id'] ?></span></td>
                        <td>
                            <div class="user-info">
                                <span class="name"><?= htmlspecialchars($row['fullname']) ?></span>
                                <small><i class="bi bi-telephone-fill me-1"></i><?= $row['phone'] ?></small>
                                <small><i class="bi bi-envelope-at-fill me-1"></i><?= $row['email'] ?></small>
                            </div>
                        </td>
                        <td>
                            <span class="tour-title"><?= htmlspecialchars($row['tour_name']) ?></span>
                        </td>
                        <td class="text-center fw-bold text-white-50">
                            <?= $row['quantity'] ?>
                        </td>
                        <td class="price-box">
                            <?= number_format($row['total_price'], 0, ',', '.') ?> VNĐ
                        </td>
                        <td class="text-white-50">
                            <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status'] == 'Pending' || $row['status'] == 'Chờ xử lý') { ?>
                                <span class="badge bg-warning text-dark badge-status">Chờ xử lý</span>
                            <?php } elseif ($row['status'] == 'Confirmed' || $row['status'] == 'Đã xác nhận') { ?>
                                <span class="badge bg-success badge-status shadow-sm">Đã xác nhận</span>
                            <?php } else { ?>
                                <span class="badge bg-danger badge-status shadow-sm">Đã huỷ</span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status'] == 'Pending' || $row['status'] == 'Chờ xử lý') { ?>
                                <div class="btn-group">
                                    <a href="update_booking.php?id=<?= $row['id'] ?>&action=confirm" 
                                       class="btn btn-confirm btn-action me-2 shadow-sm">
                                       <i class="bi bi-check-lg"></i>
                                    </a>
                                    <a href="update_booking.php?id=<?= $row['id'] ?>&action=cancel" 
                                       class="btn btn-cancel btn-action shadow-sm"
                                       onclick="return confirm('Bạn có chắc muốn huỷ đơn này?')">
                                       <i class="bi bi-x-lg"></i>
                                    </a>
                                </div>
                            <?php } else { ?>
                                <span class="status-processed"><i class="bi bi-shield-check"></i> Đã xử lý</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../layout/footer.php"); ?>