<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION['show_welcome'])): ?>
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center border-0 shadow-lg" style="border-radius: 20px; background: #1a1a1a; color: #fff;">
      <div class="modal-body py-5">
        <div class="mb-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>
        <h3 class="fw-bold text-white">Chào mừng trở lại!</h3>
        <p class="text-secondary">Chào <strong><?php echo $_SESSION['fullname']; ?></strong>, chúc bạn một ngày tốt lành!</p>
        <button type="button" class="btn btn-success px-5 py-2 fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">
            Bắt đầu khám phá
        </button>
      </div>
    </div>
  </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
        welcomeModal.show();
    });
</script>
<?php unset($_SESSION['show_welcome']); endif; ?>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content text-center border-0 shadow-lg" style="border-radius: 20px; background: #1a1a1a; color: #fff; border: 1px solid rgba(220, 53, 69, 0.2) !important;">
      <div class="modal-body py-4">
        <div class="mb-3">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
        </div>
        <h4 class="fw-bold text-white">Đăng xuất?</h4>
        <p class="text-secondary small">Khoa ơi, bạn có chắc chắn muốn rời khỏi phiên làm việc này không?</p>
        <div class="d-flex gap-2 justify-content-center mt-4">
            <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Hủy</button>
            <a href="/tour_web/auth/logout.php" class="btn btn-danger btn-sm px-3 rounded-pill">Đăng xuất ngay</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tour Web - Khám Phá Thế Giới</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
    /* --- RESET & BASE --- */
    body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background-color: #0a0a0a; color: #e0e0e0; }

    /* --- NAVBAR PILL STYLE --- */
    .navbar-custom { position: absolute; width: 100%; top: 25px; z-index: 1050; background: transparent !important; }
    .navbar-pill-container {
        background: rgba(25, 25, 25, 0.75); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
        border-radius: 100px; padding: 8px 30px; border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex; align-items: center; width: fit-content; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .navbar-brand { color: #fff !important; font-weight: 800; font-size: 1.2rem; letter-spacing: 1px; margin-right: 30px; text-decoration: none; }
    .nav-link { color: rgba(255, 255, 255, 0.8) !important; font-weight: 500; font-size: 0.95rem; padding: 10px 20px !important; margin: 0 2px; border-radius: 50px; transition: all 0.3s ease; }
    .nav-link:hover, .nav-link.active { background-color: #27ae60 !important; color: #fff !important; box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3); }

    /* Tùy chỉnh Dropdown Admin */
    .dropdown-menu-dark { background-color: #1a1a1a; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-top: 10px; }
    .dropdown-item { color: #e0e0e0; padding: 10px 20px; font-size: 0.9rem; transition: 0.2s; }
    .dropdown-item:hover { background-color: #27ae60; color: #fff; }

    /* --- CHATBOT UI --- */
    #chat-circle-toggle { 
        position: fixed; bottom: 25px; right: 25px; width: 60px; height: 60px; border-radius: 50%; 
        background-color: #27ae60 !important; display: flex !important; align-items: center; justify-content: center; 
        font-size: 26px; color: white; cursor: pointer; z-index: 10000; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.6);
        transition: transform 0.3s;
    }
    .chat-box-main { 
        position: fixed; bottom: 95px; right: 25px; width: 330px; background: #1a1a1a; border-radius: 15px; 
        overflow: hidden; z-index: 10000; border: 1px solid rgba(39, 174, 96, 0.4);
        display: none; flex-direction: column; box-shadow: 0 10px 40px rgba(0,0,0,0.8);
    }
    .chat-header { background: #27ae60; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
    #chat-content-display { height: 380px; overflow-y: auto; background: #121212; padding: 15px; display: flex; flex-direction: column; gap: 12px; }
    .msg-ai-bubble { background: #2c2c2c; color: #ffffff !important; padding: 10px 14px; border-radius: 15px 15px 15px 2px; font-size: 0.85rem; max-width: 85%; border: 1px solid rgba(255,255,255,0.1); }
    .msg-user-bubble { background: #27ae60; color: #ffffff !important; padding: 10px 14px; border-radius: 15px 15px 2px 15px; font-size: 0.85rem; max-width: 85%; align-self: flex-end; }
    .chat-footer { background: #1a1a1a; border-top: 1px solid #333; padding: 10px; }
    #chat-input-field { background: #2c2c2c; border: none; color: white; font-size: 0.85rem; }
    #chat-input-field:focus { background: #333; color: white; box-shadow: none; }
    #chat-input-field::placeholder { color: #888; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container justify-content-center">
        <div class="navbar-pill-container">
            <a class="navbar-brand" href="/tour_web/">TRAVELA</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="/tour_web/">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="/tour_web/tours/list.php">Tours</a></li>
                    <li class="nav-item"><a class="nav-link" href="/tour_web/about.php">Giới thiệu</a></li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="/tour_web/user/my_bookings.php">Đã đặt</a></li>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-info fw-bold" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-shield-lock me-1"></i> Admin Panel
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="/tour_web/admin/manage_tours.php"><i class="bi bi-map me-2 text-success"></i>Quản lý Tour</a></li>
                                <li><a class="dropdown-item" href="/tour_web/admin/manage_users.php"><i class="bi bi-people me-2 text-primary"></i>Thành viên</a></li>
                                <li><a class="dropdown-item" href="/tour_web/admin/manage_booking.php"><i class="bi bi-ticket-perforated me-2 text-info"></i>Quản lý đơn đặt</a></li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                                <li><a class="dropdown-item" href="/tour_web/admin/revenue_report.php"><i class="bi bi-bar-chart-line me-2 text-warning"></i>Báo cáo doanh thu</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <div class="ms-lg-3 d-flex align-items-center gap-2">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="/tour_web/user/profile.php" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            Hi, <?php echo explode(' ', $_SESSION['fullname'])[0]; ?>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger rounded-circle" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="bi bi-power"></i>
                        </button>
                    <?php else: ?>
                        <a href="/tour_web/auth/login.php" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold text-dark">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<div id="chat-circle-toggle"><i class="bi bi-chat-dots-fill"></i></div>
<div id="chat-box-wrapper" class="chat-box-main">
    <div class="chat-header">
        <span class="fw-bold small text-uppercase text-white"><i class="bi bi-robot me-2"></i>Hỗ trợ Travela</span>
        <button type="button" class="btn-close btn-close-white" id="chat-box-close"></button>
    </div>
    <div class="chat-body" id="chat-content-display">
        <div class="msg-ai-bubble">
            Chào <strong><?php echo $_SESSION['fullname'] ?? 'bạn'; ?></strong>! Chúc bạn một ngày tốt lành! Mình có thể giúp gì được cho bạn nè?
        </div>
    </div>
    <div class="chat-footer">
        <div class="input-group">
            <input type="text" id="chat-input-field" class="form-control form-control-sm shadow-none" placeholder="Nhập tin nhắn...">
            <button class="btn btn-success btn-sm" id="chat-btn-send"><i class="bi bi-send-fill text-white"></i></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const circleBtn = document.getElementById('chat-circle-toggle');
    const chatWrapper = document.getElementById('chat-box-wrapper');
    const closeBtn = document.getElementById('chat-box-close');
    const inputField = document.getElementById('chat-input-field');
    const sendBtn = document.getElementById('chat-btn-send');
    const contentDisplay = document.getElementById('chat-content-display');

    circleBtn.onclick = () => { chatWrapper.style.display = 'flex'; circleBtn.style.display = 'none'; inputField.focus(); };
    closeBtn.onclick = () => { chatWrapper.style.display = 'none'; circleBtn.style.display = 'flex'; };

    async function handleSendMessage() {
        const msg = inputField.value.trim();
        if (!msg) return;
        contentDisplay.insertAdjacentHTML('beforeend', `<div class="msg-user-bubble text-white">${msg}</div>`);
        inputField.value = "";
        contentDisplay.scrollTop = contentDisplay.scrollHeight;
        
        const loadingId = 'load-' + Date.now();
        contentDisplay.insertAdjacentHTML('beforeend', `<div class="msg-ai-bubble text-white" id="${loadingId}"><span class="spinner-border spinner-border-sm text-success"></span></div>`);
        
        try {
            const res = await fetch('/tour_web/chat_bot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(msg)
            });
            document.getElementById(loadingId).innerHTML = await res.text();
            contentDisplay.scrollTop = contentDisplay.scrollHeight;
        } catch (e) { document.getElementById(loadingId).innerText = "Lỗi kết nối!"; }
    }
    
    sendBtn.onclick = handleSendMessage;
    inputField.onkeydown = (e) => { if(e.key === 'Enter') handleSendMessage(); };
});
</script>

<div class="container" style="margin-top: 120px;">