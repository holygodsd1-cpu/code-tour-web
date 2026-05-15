<?php
session_start();
include("../config/database.php");

$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($sql);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['show_welcome'] = true;

        header("Location: ../index.php");
        exit();
    } else {
        $message = "Sai email hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Tour Web</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card { 
            background: var(--card-bg);
            border-radius: 24px; 
            border: 1px solid rgba(255, 255, 255, 0.05); 
            padding: 40px;
            width: 100%;
            max-width: 420px;
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
            padding: 12px 15px;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        }

        .password-group { position: relative; }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 42px;
            cursor: pointer;
            color: #64748b;
            z-index: 10;
        }

        .btn-login {
            background: var(--primary-blue);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            color: white;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .auth-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.2s;
        }

        .auth-links a:hover {
            color: var(--primary-blue);
        }

        .alert {
            border-radius: 12px;
            border: none;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo">
        <i class="bi bi-person-lock"></i>
    </div>
    
    <h3 class="text-center mb-4 fw-bold text-white">ĐĂNG NHẬP</h3>

    <div class="message-zone">
        <?php if($message): ?>
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger text-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-warning bg-warning bg-opacity-10 text-warning text-center">
                <i class="bi bi-info-circle-fill me-2"></i> Bạn cần đăng nhập để đặt tour!
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'logout'): ?>
            <div class="alert alert-success bg-success bg-opacity-10 text-success text-center">
                <i class="bi bi-check-circle-fill me-2"></i> Đăng xuất thành công!
            </div>
        <?php endif; ?>
    </div>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required>
        </div>

        <div class="mb-3 password-group">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control custom-input" placeholder="••••••••" required>
            <i class="fa fa-eye toggle-password" id="eyeIcon"></i>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" name="login" class="btn btn-login">
                ĐĂNG NHẬP
            </button>
        </div>
    </form>

    <div class="auth-links d-flex justify-content-between mt-4">
        <a href="register.php">Chưa có tài khoản?</a>
        <a href="forgot_password.php" class="text-danger fw-bold opacity-75">Quên mật khẩu?</a>
    </div>

    <hr class="my-4" style="border-color: rgba(255,255,255,0.05);">

    <div class="text-center">
        <a href="../index.php" class="small text-muted text-decoration-none">
            <i class="fa fa-arrow-left me-1"></i> Quay lại trang chủ
        </a>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    eyeIcon.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>