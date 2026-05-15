<?php include("../layout/header.php"); ?>

<style>
    /* Xóa bỏ các thuộc tính flex trên body nếu có để tránh xung đột với header */
    body {
        background-color: #0f172a !important;
        margin: 0;
        padding: 0;
    }

    /* Container bao quanh để tạo không gian */
    .page-wrapper {
        position: relative;
        min-height: calc(100vh - 80px); /* Trừ đi chiều cao ước tính của header */
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .success-card {
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 50px 40px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        text-align: center;
        /* Đảm bảo nội dung bên trong card luôn thẳng hàng */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .success-icon {
        width: 70px;
        height: 70px;
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 2rem;
    }

    .btn-home {
        background: #3b82f6;
        color: white !important;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none !important;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .btn-home:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .countdown-box {
        color: #94a3b8;
        font-size: 0.85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 20px;
        width: 100%;
    }

    #seconds {
        color: #3b82f6;
        font-weight: 700;
    }
</style>

<div class="page-wrapper">
    <div class="success-card">
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <h2 class="text-white fw-bold mb-3">Đặt Tour Thành Công!</h2>
        
        <p class="text-muted mb-4">
            Cảm ơn Khoa. Yêu cầu của bạn đã được hệ thống ghi nhận. 
            Chúng mình sẽ liên hệ sớm nhất qua SĐT hoặc Email.
        </p>

        <a href="../index.php" class="btn-home">
            <i class="bi bi-house-door"></i> Về trang chủ
        </a>

        <div class="countdown-box">
            Tự động chuyển hướng sau <span id="seconds">5</span> giây
        </div>
    </div>
</div>

<script>
    let timeLeft = 5;
    const timer = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(timer);
            window.location.href = "../index.php";
        } else {
            const secElem = document.getElementById("seconds");
            if(secElem) secElem.innerHTML = timeLeft;
        }
        timeLeft -= 1;
    }, 1000);
</script>

<?php include("../layout/footer.php"); ?>