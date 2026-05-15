<?php
// Kiểm tra session trước khi start để tránh lỗi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tour_id = $_POST['tour_id'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$departure_date = $_POST['departure_date'] ?? '';
$quantity = $_POST['quantity'] ?? '';
$total = $_POST['total_price'] ?? 0;

include("../layout/header.php");
?>

<style>
    :root {
        --primary-blue: #3b82f6;
        --dark-bg: #0f172a;
        --card-bg: #1e293b;
    }

    body {
        background-color: var(--dark-bg) !important;
        color: #e2e8f0 !important;
        font-family: 'Inter', sans-serif;
    }

    .payment-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payment-card {
        background: var(--card-bg);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        padding: 40px;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-label {
        color: #94a3b8;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: #ffffff;
        font-weight: 500;
    }

    .total-section {
        background: rgba(59, 130, 246, 0.1);
        border-radius: 15px;
        padding: 20px;
        margin: 25px 0;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .btn-confirm {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-confirm:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: rgba(59, 130, 246, 0.2);
        color: var(--primary-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.5rem;
    }
</style>

<div class="container payment-container">
    <div class="payment-card animate__animated animate__fadeIn">
        <div class="header-icon">
            <i class="bi bi-shield-check"></i>
        </div>
        
        <h3 class="text-center fw-bold mb-4 text-white">Xác Nhận Thanh Toán</h3>

        <div class="payment-details">
            <div class="info-row">
                <span class="info-label">Khách hàng</span>
                <span class="info-value"><?= htmlspecialchars($fullname) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Số điện thoại</span>
                <span class="info-value"><?= htmlspecialchars($phone) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?= htmlspecialchars($email) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày khởi hành</span>
                <span class="info-value"><?= htmlspecialchars($departure_date) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Số lượng</span>
                <span class="info-value"><?= htmlspecialchars($quantity) ?> người</span>
            </div>

            <div class="total-section text-center">
                <p class="info-label mb-1">Tổng chi phí dự kiến</p>
                <h2 class="text-primary fw-bold mb-0">
                    <?= number_format($total, 0, ',', '.') ?> <small style="font-size: 1rem;">VNĐ</small>
                </h2>
            </div>
        </div>

        <form method="POST" action="process_booking.php">
            <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
            <input type="hidden" name="fullname" value="<?= $fullname ?>">
            <input type="hidden" name="phone" value="<?= $phone ?>">
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="departure_date" value="<?= $departure_date ?>">
            <input type="hidden" name="quantity" value="<?= $quantity ?>">
            <input type="hidden" name="total_price" value="<?= $total ?>">

            <button type="submit" class="btn btn-confirm">
                <i class="bi bi-credit-card-2-front me-2"></i> Tiến hành đặt tour
            </button>
        </form>
        
        <p class="text-center mt-3 mb-0">
            <a href="javascript:history.back()" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Quay lại chỉnh sửa
            </a>
        </p>
    </div>
</div>

<?php include("../layout/footer.php"); ?>