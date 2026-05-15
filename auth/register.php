<?php
session_start();
include("../config/database.php");

$message = "";
$success = "";

if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu khớp nhau
    if ($password !== $confirm_password) {
        $message = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $message = "Email này đã được sử dụng!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (fullname, email, phone, password, role) VALUES ('$fullname', '$email', '$phone', '$hashed_password', 'user')";
            
            if (mysqli_query($conn, $sql)) {
                $success = "Đăng ký thành công! Đang chuyển hướng...";
                header("refresh:2;url=login.php");
            } else {
                $message = "Có lỗi xảy ra, vui lòng thử lại!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký thành viên - Tour Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-blue: #3b82f6;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --input-bg: #0f172a;
        }

        body { 
            background-color: var(--dark-bg); 
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px 0;
        }

        .register-card { 
            background: var(--card-bg);
            border-radius: 24px; 
            border: 1px solid rgba(255, 255, 255, 0.05); 
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-blue);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .custom-input {
            background: var(--input-bg) !important;
            border: 1px solid #334155 !important;
            color: white !important;
            border-radius: 12px;
            padding: 11px 15px;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        }

        .password-field { position: relative; }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: #64748b;
            z-index: 10;
        }

        .btn-register {
            background: var(--primary-blue);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            color: white;
            transition: 0.3s;
            margin-top: 15px;
        }

        .btn-register:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .auth-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .auth-links a:hover { color: var(--primary-blue); }

        .alert { border-radius: 12px; font-size: 0.85rem; border: none; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="brand-logo">
        <i class="bi bi-person-plus-fill"></i>
    </div>
    
    <h3 class="text-center mb-4 fw-bold text-white">ĐĂNG KÝ</h3>

    <div class="message-zone">
        <?php if($message): ?>
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger text-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success bg-success bg-opacity-10 text-success text-center">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
    </div>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="fullname" class="form-control custom-input" placeholder="Lưu Tấn Khoa" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control custom-input" placeholder="khoa@example.com" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control custom-input" placeholder="0915xxxxxx" required>
            </div>
        </div>

        <div class="mb-3 password-field">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control custom-input" placeholder="••••••••" required>
            <i class="fa fa-eye toggle-password" onclick="togglePass('password', this)"></i>
        </div>

        <div class="mb-3 password-field">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control custom-input" placeholder="••••••••" required>
            <i class="fa fa-eye toggle-password" onclick="togglePass('confirm_password', this)"></i>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" name="register" class="btn btn-register">
                TẠO TÀI KHOẢN
            </button>
        </div>
    </form>

    <div class="auth-links text-center mt-4">
        <span>Đã có tài khoản?</span> 
        <a href="login.php" class="ms-1 fw-bold text-primary">Đăng nhập ngay</a>
    </div>
</div>

<script>
    function togglePass(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
</script>

</body>
</html>