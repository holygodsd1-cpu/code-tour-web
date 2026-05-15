<?php
include("../layout/header.php");
include("../config/database.php");

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user'; // Lấy role từ session
$message = "";
$error = "";

// 2. Lấy thông tin người dùng
$sql = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($sql);

// 3. THỐNG KÊ CHI TIÊU (Chỉ chạy truy vấn nếu KHÔNG PHẢI admin)
$total_tours = 0;
$total_spent = 0;
$progress = 0;
$member_rank = "Thành viên";
$rank_class = "text-secondary";

if ($user_role !== 'admin') {
    $sql_stats = mysqli_query($conn, "SELECT COUNT(*) as count, SUM(total_price) as total 
                                      FROM bookings 
                                      WHERE user_id = '$user_id' 
                                      AND (status LIKE '%xác nhận%' OR status = 'Confirmed' OR status = 'Đã xác nhận')");

    $row_stats = mysqli_fetch_assoc($sql_stats);
    $total_tours = $row_stats['count'] ?? 0;
    $total_spent = (float)($row_stats['total'] ?? 0);

    // Tính toán hạng thành viên
    $max_goal = 20000000;
    $progress = ($total_spent > 0) ? ($total_spent / $max_goal) * 100 : 0;
    $member_rank = "Thành viên Đồng";
    
    if ($total_spent >= $max_goal) {
        $member_rank = "Thành viên Vàng";
        $rank_class = "text-warning";
        $progress = 100;
    } elseif ($total_spent >= 10000000) {
        $member_rank = "Thành viên Bạc";
        $rank_class = "text-info";
    }
}

// 4. Xử lý cập nhật hồ sơ
if (isset($_POST['update_profile'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    mysqli_query($conn, "UPDATE users SET fullname='$fullname', phone='$phone', address='$address' WHERE id='$user_id'");
    $_SESSION['fullname'] = $fullname;

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $allowed = array("jpg", "jpeg", "png");
        if (in_array($file_ext, $allowed)) {
            $new_file_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_dir = "../uploads/";
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $new_file_name)) {
                if ($user['avatar'] != 'default_avatar.png' && !empty($user['avatar']) && file_exists($upload_dir . $user['avatar'])) {
                    @unlink($upload_dir . $user['avatar']);
                }
                mysqli_query($conn, "UPDATE users SET avatar='$new_file_name' WHERE id='$user_id'");
                $user['avatar'] = $new_file_name; 
            }
        }
    }
    $message = "Cập nhật hồ sơ thành công!";
    $user['fullname'] = $fullname; $user['phone'] = $phone; $user['address'] = $address;
}

// 5. Xử lý đổi mật khẩu
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (password_verify($old_pass, $user['password'])) {
        if ($new_pass === $confirm_pass) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE id='$user_id'");
            $message = "Đổi mật khẩu thành công!";
        } else { $error = "Xác nhận mật khẩu không khớp!"; }
    } else { $error = "Mật khẩu cũ không đúng!"; }
}
?>

<style>
    :root { 
        --primary-blue: #3b82f6; 
        --dark-bg: #0f172a;
        --card-bg: #1e293b;
    }
    body { background-color: var(--dark-bg); color: #e2e8f0; font-family: 'Inter', sans-serif; }
    
    .main-card { 
        background: var(--card-bg); 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        border-radius: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }

    .custom-input { 
        background: #0f172a !important; 
        border: 1px solid #334155 !important; 
        color: white !important; 
        border-radius: 12px; 
        padding: 12px 15px; 
        transition: 0.3s; 
    }
    .custom-input:focus { 
        border-color: var(--primary-blue) !important; 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important; 
    }
    .custom-input[readonly] { 
        background-color: #1a2236 !important; 
        color: #64748b !important; 
        cursor: not-allowed; 
        border-style: dashed !important; 
    }

    .profile-img { 
        width: 130px; 
        height: 130px; 
        object-fit: cover; 
        border-radius: 50%; 
        border: 4px solid var(--primary-blue);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
    }

    .label-small { font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    
    .input-group-password { position: relative; }
    
    .toggle-password { 
        position: absolute; 
        right: 15px; 
        top: 50%; 
        transform: translateY(-50%); 
        cursor: pointer; 
        color: #64748b; 
        transition: 0.3s;
        z-index: 10;
    }
    .toggle-password:hover { color: var(--primary-blue); }

    .btn-save {
        background: var(--primary-blue);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        transition: 0.3s;
    }
    .btn-save:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }
</style>

<div class="container py-5">
    <?php if($message): ?>
        <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm bg-success bg-opacity-10 text-success">
            <i class="bi bi-check-circle-fill me-2"></i><?= $message ?>
        </div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm bg-danger bg-opacity-10 text-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="<?= ($user_role === 'admin') ? 'col-lg-12' : 'col-lg-8' ?>">
            <div class="main-card p-4 p-md-5">
                <form method="POST" enctype="multipart/form-data">
                    <h4 class="mb-5 fw-bold"><i class="bi bi-person-vcard me-2 text-primary"></i>Hồ Sơ Cá Nhân (<?= ucfirst($user_role) ?>)</h4>
                    
                    <div class="text-center mb-5">
                        <div class="position-relative d-inline-block">
                            <?php 
                                $avatar_path = "../uploads/" . ($user['avatar'] ?: 'default_avatar.png');
                                $avatar_url = (!file_exists($avatar_path) || empty($user['avatar'])) 
                                    ? "https://ui-avatars.com/api/?name=" . urlencode($user['fullname']) . "&background=0D6EFD&color=fff" 
                                    : $avatar_path;
                            ?>
                            <img id="preview" src="<?= $avatar_url ?>" class="profile-img">
                            <label for="imgInput" class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 shadow-sm" style="cursor: pointer; width: 35px; height: 35px;">
                                <i class="bi bi-camera-fill text-white"></i>
                            </label>
                            <input type="file" name="avatar" id="imgInput" hidden accept="image/*">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <label class="label-small mb-2 d-block">Họ và Tên</label>
                            <input type="text" name="fullname" class="form-control custom-input" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="label-small mb-2 d-block">Địa chỉ Email</label>
                            <input type="email" class="form-control custom-input" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="label-small mb-2 d-block">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control custom-input" value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>

                        <div class="col-12 mb-4">
                            <label class="label-small mb-2 d-block">Địa chỉ liên hệ</label>
                            <input type="text" name="address" class="form-control custom-input" value="<?= htmlspecialchars($user['address']) ?>" placeholder="Nhập địa chỉ của bạn...">
                        </div>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary btn-save w-100 shadow-sm">CẬP NHẬT THÔNG TIN</button>
                </form>

                <div class="mt-5 pt-5 border-top border-secondary border-opacity-30">
                    <h5 class="mb-4 text-danger fw-bold"><i class="bi bi-shield-lock me-2"></i>Bảo mật tài khoản</h5>
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <label class="label-small mb-2 d-block">Mật khẩu cũ</label>
                                <div class="input-group-password">
                                    <input type="password" name="old_password" class="form-control custom-input pass-input" required>
                                    <i class="bi bi-eye-slash toggle-password"></i>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="label-small mb-2 d-block">Mật khẩu mới</label>
                                <div class="input-group-password">
                                    <input type="password" name="new_password" class="form-control custom-input pass-input" required>
                                    <i class="bi bi-eye-slash toggle-password"></i>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="label-small mb-2 d-block">Xác nhận lại</label>
                                <div class="input-group-password">
                                    <input type="password" name="confirm_password" class="form-control custom-input pass-input" required>
                                    <i class="bi bi-eye-slash toggle-password"></i>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-outline-danger w-100 fw-bold mt-2 py-3 border-2" style="border-radius: 12px;">XÁC NHẬN THAY ĐỔI MẬT KHẨU</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($user_role !== 'admin'): ?>
        <div class="col-lg-4">
            <div class="main-card p-4 mb-4" style="border-top: 5px solid var(--primary-blue);">
                <h5 class="fw-bold mb-4 text-white"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Thống Kê Chi Tiêu</h5>
                <div class="mb-4">
                    <p class="label-small mb-1">Tổng tiền đã thanh toán</p>
                    <h3 class="fw-bold text-white"><?= number_format($total_spent, 0, ',', '.') ?> <span class="fs-6 text-muted">VNĐ</span></h3>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-white bg-opacity-5 text-center border border-secondary border-opacity-10">
                            <p class="text-muted mb-1 small">Tour đã đi</p>
                            <h5 class="mb-0 text-primary fw-bold"><?= $total_tours ?></h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-white bg-opacity-5 text-center border border-secondary border-opacity-10">
                            <p class="text-muted mb-1 small">Hạng bậc</p>
                            <h5 class="mb-0 text-success fw-bold"><?= ($total_spent >= 10000000) ? 'Bạc' : 'Đồng' ?></h5>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-4 bg-dark bg-opacity-50 border border-secondary border-opacity-20">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small fw-bold <?= $rank_class ?> text-uppercase"><?= $member_rank ?></span>
                        <span class="small text-muted"><?= round(min($progress, 100)) ?>%</span>
                    </div>
                    <div class="progress bg-secondary bg-opacity-20" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar bg-primary shadow-sm progress-bar-striped progress-bar-animated" style="width: <?= $progress ?>%"></div>
                    </div>
                    <?php if($total_spent < 20000000): ?>
                        <p class="text-muted mt-3 mb-0" style="font-size: 11px; line-height: 1.4;">
                            <i class="bi bi-info-circle me-1"></i> Cố lên! Bạn cần thêm <b><?= number_format(20000000 - $total_spent, 0, ',', '.') ?> VNĐ</b> để nâng cấp lên hạng <b>Vàng</b>.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Preview ảnh khi chọn file
    document.getElementById('imgInput').onchange = e => {
        const [file] = e.target.files;
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    }

    // Xử lý ẩn hiện mật khẩu
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.pass-input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                this.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    });
</script>

<?php include("../layout/footer.php"); ?>