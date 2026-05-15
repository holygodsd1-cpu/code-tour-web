<?php
include("../config/database.php");
// Gọi header ở đầu trang
include("../layout/header.php");

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location='/tour_web/index.php';</script>";
    exit();
}

// 2. Xử lý xóa (Chỉ cho phép xóa user, không xóa admin để bảo mật)
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id' AND role != 'admin'");
    echo "<script>window.location='manage_users.php';</script>";
}

// 3. Lấy danh sách (Lọc bỏ admin bằng điều kiện WHERE role != 'admin')
$sql = mysqli_query($conn, "SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC");
$total_users = mysqli_num_rows($sql);
?>

<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* --- TỔNG THỂ DARK THEME --- */
body { 
    background-color: #121212 !important; 
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
}

.user-section {
    padding: 60px 0;
}

/* --- KHUNG BAO BỌC BẢNG (Giống booking-wrapper) --- */
.user-wrapper {
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
    border-left: 5px solid #3b82f6; /* Viền xanh dương cho trang User */
    padding-left: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* --- TÙY CHỈNH BẢNG (CARD TABLE) --- */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 15px; /* Tách các dòng ra 15px */
    color: #e0e0e0;
    margin-bottom: 0;
}

/* Tiêu đề cột - Ép trong suốt để mất dải trắng */
.custom-table th {
    background: transparent !important;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 10px 20px;
    border: none !important;
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
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15); /* Bóng đổ xanh dương */
}

/* Ô dữ liệu (Cell) */
.custom-table td {
    padding: 20px;
    vertical-align: middle;
    border-top: 1px solid rgba(255,255,255,0.02);
    border-bottom: 1px solid rgba(255,255,255,0.02);
    border-left: none;
    border-right: none;
    background: transparent !important;
    color: #fff;
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

/* Custom Text */
.text-highlight {
    font-weight: 700;
    font-size: 1.05rem;
    color: #fff;
}

.text-sub {
    color: #888;
    font-size: 0.85rem;
    margin-top: 4px;
}

/* Badge cho User */
.badge-user {
    background: rgba(59, 130, 246, 0.15);
    color: #60a5fa;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.btn-delete {
    color: #64748b;
    font-size: 1.3rem;
    transition: 0.3s;
    cursor: pointer;
    border: none;
    background: none;
    padding: 5px;
}

.btn-delete:hover {
    color: #ef4444;
    transform: scale(1.15);
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #64748b;
    background: #181818;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.02);
}
</style>

<div class="user-section">
    <div class="container">
        <div class="user-wrapper">
            <div class="page-title">
                <span>Quản lý tài khoản khách hàng</span>
                <span class="badge bg-primary rounded-pill py-2 px-3 fw-normal" style="font-size: 0.9rem; letter-spacing: 0;">
                    <?= $total_users ?> Thành viên
                </span>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thành viên</th>
                            <th>Liên hệ</th>
                            <th>Địa chỉ</th>
                            <th>Phân quyền</th>
                            <th class="text-center">Gỡ bỏ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($total_users > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($sql)): ?>
                            <tr>
                                <td style="width: 80px;">
                                    <span class="text-sub">#<?= $row['id'] ?></span>
                                </td>
                                <td>
                                    <div class="text-highlight"><?= htmlspecialchars($row['fullname'] ?? 'Chưa đặt tên') ?></div>
                                </td>
                                <td>
                                    <div class="text-highlight" style="font-size: 0.95rem; font-weight: 500;"><?= htmlspecialchars($row['email']) ?></div>
                                    <div class="text-sub"><?= $row['phone'] ?: 'Chưa có SĐT' ?></div>
                                </td>
                                <td>
                                    <span class="text-sub"><?= $row['address'] ?: 'Chưa cập nhật' ?></span>
                                </td>
                                <td>
                                    <span class="badge-user">Khách hàng</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn-delete" 
                                            onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['fullname'] ?? $row['email']) ?>')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-0">
                                    <div class="empty-state mt-3">
                                        <i class="bi bi-people mb-2 d-block" style="font-size: 2.5rem; color: #475569;"></i>
                                        <span class="fs-5">Chưa có tài khoản khách hàng nào.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Xác nhận xóa?',
        html: `Bạn đang chuẩn bị xóa tài khoản: <b>${userName}</b><br>Hành động này không thể hoàn tác!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', 
        cancelButtonColor: '#2b2b2b', 
        confirmButtonText: 'Xác nhận xóa',
        cancelButtonText: 'Hủy bỏ',
        background: '#1e1e1e', 
        color: '#e0e0e0',
        borderRadius: '20px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'manage_users.php?delete_id=' + userId;
        }
    })
}
</script>

<?php include("../layout/footer.php"); ?>