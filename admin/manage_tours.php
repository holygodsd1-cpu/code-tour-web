<?php
session_start();
include("../config/database.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include("../layout/header.php");

$result = mysqli_query($conn, "SELECT * FROM tours");
?>

<style>
/* --- TỔNG THỂ DARK THEME CHO ADMIN --- */
body { 
    background-color: #121212; 
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
}

/* --- KHUNG BAO BỌC BẢNG --- */
.admin-wrapper {
    background: #1e1e1e;
    border-radius: 30px;
    padding: 40px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    border: 1px solid rgba(255,255,255,0.05);
}

.page-title {
    color: #ffffff;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-left: 5px solid #0d6efd; /* Đổi màu viền sang xanh dương cho khác biệt phần User */
    padding-left: 15px;
}

/* --- NÚT BẤM (BUTTONS) --- */
.btn-action-top {
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
}
.btn-action-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}
.btn-add-tour {
    background: linear-gradient(45deg, #198754, #20c997);
    color: white;
}
.btn-add-schedule {
    background: linear-gradient(45deg, #0d6efd, #0dcaf0);
    color: white;
}

/* --- TÙY CHỈNH BẢNG (CARD TABLE) --- */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 15px;
}

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

.custom-table tbody tr {
    background: #181818;
    transition: all 0.3s ease;
}

.custom-table tbody tr:hover {
    transform: translateY(-5px);
    background: #222;
    box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15); /* Shadow xanh dương nhẹ */
}

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
    color: #0dcaf0; /* Màu cyan cho giá tiền ở trang Admin */
    font-weight: 800;
    font-size: 1.05rem;
}

.id-badge {
    background: rgba(255,255,255,0.1);
    color: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.9rem;
}

/* Nút Sửa / Xóa trong bảng */
.btn-table-action {
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: 0.3s;
}
</style>

<div class="container mt-5 mb-5">
    <div class="admin-wrapper">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <h2 class="page-title mb-0">Quản Lý Tour</h2>
            <div>
                <a href="add_tour.php" class="btn btn-action-top btn-add-tour me-2">
                    <i class="bi bi-plus-circle me-1"></i> Thêm Tour
                </a>
                <a href="add_schedule.php" class="btn btn-action-top btn-add-schedule">
                    <i class="bi bi-calendar-plus me-1"></i> Thêm Lịch Trình
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Ảnh</th>
                        <th>Tên Tour</th>
                        <th>Địa điểm</th>
                        <th>Giá</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td class="text-center">
                            <span class="id-badge">#<?= $row['id'] ?></span>
                        </td>
                        <td style="width: 130px;">
                            <img src="../uploads/<?php echo $row['image']; ?>" class="tour-img" width="100" height="70" alt="Ảnh Tour">
                        </td>
                        <td>
                            <div class="tour-name"><?= $row['tour_name'] ?></div>
                        </td>
                        <td class="text-white-50">
                            <i class="bi bi-geo-alt-fill me-1 text-danger"></i> <?= $row['location'] ?>
                        </td>
                        <td class="price-text">
                            <?= number_format($row['price']) ?> VNĐ
                        </td>
                        <td class="text-center">
                            <a href="edit_tour.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-table-action me-1 shadow-sm">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <a href="delete_tour.php?id=<?= $row['id'] ?>" 
                               class="btn btn-danger btn-sm btn-table-action shadow-sm"
                               onclick="return confirm('Bạn có chắc muốn xoá tour này khỏi hệ thống không?')">
                                <i class="bi bi-trash"></i> Xoá
                            </a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-5 text-white-50 fs-5'>Chưa có tour nào trong hệ thống.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include("../layout/footer.php"); ?>