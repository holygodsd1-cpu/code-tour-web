<?php
include("../layout/header.php");
include("../config/database.php");

$error = "";
$success = "";

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Kiểm tra email có tồn tại không
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['reset_email'] = $email;
        echo "<script>window.location.href='reset_password_new.php';</script>";
        exit();
    } else {
        $error = "Email này không tồn tại trong hệ thống!";
    }
}
?>

<style>
    :root { 
        --primary-blue: #3b82f6;
        --dark-bg: #0f172a;
        --card-bg: #1e293b;
    }

    body { 
        background-color: var(--dark-bg); 
        color: #e2e8f0;
        font-family: 'Inter', sans-serif;
    }

    .auth-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .forgot-card { 
        background: var(--card-bg);
        border-radius: 24px; 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        padding: 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .icon-box {
        width: 64px;
        height: 64px;
        background: rgba(59, 130, 246, 0.1);
        color: var(--primary-blue);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 1.5rem;
    }

    .label-small {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .custom-input {
        background: #0f172a !important;
        border: 1px solid #334155 !important;
        color: white !important;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    .custom-input:focus {
        border-color: var(--primary-blue) !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        outline: none;
    }

    .btn-continue {
        background: var(--primary-blue);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        color: white;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-continue:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
    }

    .btn-back {
        background: transparent;
        color: #94a3b8;
        border: none;
        font-size: 0.9rem;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-back:hover {
        color: white;
    }

    .alert-custom {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-radius: 12px;
        font-size: 0.85rem;
    }
</style>

<div class="auth-container">
    <div class="forgot-card">
        <div class="icon-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        
        <div class="text-center mb-4">
            <h3 class="fw-bold text-white mb-2">Quên mật khẩu?</h3>
            <p class="text-muted small">Nhập email đăng ký để nhận hướng dẫn khôi phục.</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-custom mb-4 text-center">
                <i class="bi bi-exclamation-circle me-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="label-small d-block">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control custom-input" placeholder="example@gmail.com" required>
            </div>
            
            <div class="d-grid gap-3">
                <button type="submit" name="reset_request" class="btn btn-continue">
                    TIẾP TỤC
                </button>
                <div class="text-center">
                    <a href="login.php" class="btn-back">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại Đăng nhập
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("../layout/footer.php"); ?>