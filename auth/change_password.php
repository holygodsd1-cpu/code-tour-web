<?php
include("../layout/header.php");
include("../config/database.php");

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

// 2. Xử lý đổi mật khẩu
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Lấy mật khẩu hiện tại từ database để đối chiếu
    $sql = mysqli_query($conn, "SELECT password FROM users WHERE id = '$user_id'");
    $user = mysqli_fetch_assoc($sql);

    // Kiểm tra mật khẩu cũ (sử dụng password_verify cho bảo mật)
    if (password_verify($old_pass, $user['password'])) {
        if ($new_pass === $confirm_pass) {
            // Mã hóa mật khẩu mới trước khi lưu
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE id='$user_id'");
            
            if ($update) {
                $message = "Đổi mật khẩu thành công!";
            } else {
                $error = "Có lỗi xảy ra trong quá trình cập nhật.";
            }
        } else {
            $error = "Xác nhận mật khẩu mới không khớp!";
        }
    } else {
        $error = "Mật khẩu cũ không chính xác!";
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .password-wrapper { position: relative; }
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 38px;
        cursor: pointer;
        color: #666;
        transition: 0.2s;
    }
    .toggle-password:hover { color: #1e3c72; }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary text-center">
                        <i class="fa fa-shield-alt me-2"></i>ĐỔI MẬT KHẨU
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if($message): ?>
                        <div class="alert alert-success shadow-sm"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if($error): ?>
                        <div class="alert alert-danger shadow-sm"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3 password-wrapper">
                            <label class="form-label fw-bold small text-uppercase">Mật khẩu hiện tại</label>
                            <input type="password" name="old_password" class="form-control pass-input" required placeholder="••••••••">
                            <i class="fa fa-eye toggle-password"></i>
                        </div>

                        <hr>

                        <div class="mb-3 password-wrapper">
                            <label class="form-label fw-bold small text-uppercase">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control pass-input" required placeholder="••••••••">
                            <i class="fa fa-eye toggle-password"></i>
                        </div>

                        <div class="mb-4 password-wrapper">
                            <label class="form-label fw-bold small text-uppercase">Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" class="form-control pass-input" required placeholder="••••••••">
                            <i class="fa fa-eye toggle-password"></i>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="change_password" class="btn btn-primary py-2 fw-bold">
                                XÁC NHẬN THAY ĐỔI
                            </button>
                            <a href="profile.php" class="btn btn-outline-secondary py-2">
                                Quay lại hồ sơ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Xử lý ẩn/hiện mật khẩu cho tất cả các ô input
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.onclick = function() {
            // Tìm ô input nằm cùng cấp với icon được click
            const input = this.parentElement.querySelector('.pass-input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        };
    });
</script>

<?php include("../layout/footer.php"); ?>