<?php
include("../layout/header.php");
include("../config/database.php");

if (!isset($_SESSION['reset_email'])) {
    echo "<script>window.location.href='forgot_password.php';</script>";
    exit();
}

$email = $_SESSION['reset_email'];
$error = "";
$success = "";

if (isset($_POST['confirm_reset'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET password = '$hashed_pass' WHERE email = '$email'");
        
        if ($update) {
            unset($_SESSION['reset_email']); 
            $success = "Mật khẩu đã được làm mới! Đang chuyển hướng...";
            echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
        }
    } else {
        $error = "Xác nhận mật khẩu không khớp!";
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

    .reset-card { 
        background: var(--card-bg);
        border-radius: 24px; 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        padding: 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .label-small {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .input-group-custom {
        position: relative;
    }

    .custom-input {
        background: #0f172a !important;
        border: 1px solid #334155 !important;
        color: white !important;
        border-radius: 12px;
        padding: 12px 45px 12px 16px; /* Chừa khoảng trống bên phải cho con mắt */
        transition: all 0.3s ease;
        width: 100%;
    }

    .custom-input:focus {
        border-color: var(--primary-blue) !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        outline: none;
    }

    /* CSS cho con mắt */
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        z-index: 10;
        transition: 0.2s;
    }

    .toggle-password:hover {
        color: var(--primary-blue);
    }

    .btn-update {
        background: var(--primary-blue);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        color: white;
        transition: 0.3s;
        width: 100%;
    }

    .btn-update:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
    }

    .alert-custom {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-radius: 12px;
    }
    
    .alert-success-custom {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border-radius: 12px;
    }
</style>

<div class="auth-container">
    <div class="reset-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-white mb-2">Đặt mật khẩu mới</h3>
            <p class="text-muted small">Vui lòng nhập mật khẩu mới và xác nhận lại.</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-custom mb-3 text-center small">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success-custom mb-3 text-center small">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="label-small">MẬT KHẨU MỚI</label>
                <div class="input-group-custom">
                    <input type="password" name="new_password" id="new_password" class="form-control custom-input" placeholder="••••••••" required>
                    <i class="bi bi-eye-slash toggle-password" onclick="togglePass('new_password', this)"></i>
                </div>
            </div>

            <div class="mb-4">
                <label class="label-small">XÁC NHẬN LẠI</label>
                <div class="input-group-custom">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control custom-input" placeholder="••••••••" required>
                    <i class="bi bi-eye-slash toggle-password" onclick="togglePass('confirm_password', this)"></i>
                </div>
            </div>

            <button type="submit" name="confirm_reset" class="btn btn-update">
                CẬP NHẬT MẬT KHẨU
            </button>
        </form>
    </div>
</div>

<script>
function togglePass(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    }
}
</script>

<?php include("../layout/footer.php"); ?>