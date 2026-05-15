<?php
session_start();
include("config/database.php"); 
include("layout/header.php");
?>

<style>
/* --- TỔNG THỂ DARK THEME --- */
body { 
    background-color: #0a0a0a; 
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
}

/* =========================================================
   --- BANNER ÁNH SÁNG ĐỘNG (GIỐNG ẢNH 2) --- 
   ========================================================= */
.hero-about {
    height: 550px;
    background-color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-radius: 0 0 80px 80px;
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid rgba(39, 174, 96, 0.2);
}

/* Lớp tạo luồng sáng xanh chạy động */
.hero-about::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: conic-gradient(from 0deg, transparent, #1a3a2a, #27ae60, #1a3a2a, transparent);
    animation: rotateGlow 10s linear infinite;
    opacity: 0.2;
    z-index: 1;
}

/* Lớp phủ đen đọng tạo chiều sâu */
.hero-about::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(circle at center, transparent 20%, #000 90%);
    z-index: 2;
}

@keyframes rotateGlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.hero-content {
    position: relative;
    z-index: 10;
}

.hero-content h1 {
    font-size: 5.5rem;
    font-weight: 900;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: -2px;
    margin-bottom: 10px;
    /* Hiệu ứng Neon phát sáng xanh lá */
    text-shadow: 
        0 0 10px rgba(39, 174, 96, 0.8),
        0 0 25px rgba(39, 174, 96, 0.5),
        0 0 50px rgba(39, 174, 96, 0.3);
}

.hero-content p {
    color: #2ecc71;
    font-weight: 600;
    letter-spacing: 5px;
    text-transform: uppercase;
    font-size: 0.9rem;
    opacity: 0.8;
}

/* =========================================================
   --- GIỮ NGUYÊN CẤU TRÚC CŨ CỦA KHOA --- 
   ========================================================= */
.about-card {
    background: #161616;
    border-radius: 40px;
    padding: 50px;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}

.about-img-wrapper {
    position: relative;
    border-radius: 30px;
    overflow: hidden;
    transform: rotate(-2deg);
    transition: 0.5s;
}
.about-img-wrapper:hover { transform: rotate(0deg) scale(1.02); }

h2, h3 { color: #27ae60; font-weight: 800; }

.stats {
    padding: 80px 0;
    background: #111;
    margin: 50px 0;
    border-radius: 50px;
}

.stat-box h2 {
    font-size: 3.5rem;
    font-weight: 900;
    background: linear-gradient(45deg, #27ae60, #2ecc71);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.feature-box {
    background: #1e1e1e;
    padding: 40px 30px;
    border-radius: 30px;
    transition: 0.4s;
}
.feature-box:hover {
    border: 1px solid #27ae60;
    background: rgba(39, 174, 96, 0.05);
    transform: translateY(-10px);
}

.gallery-img {
    border-radius: 25px;
    margin-bottom: 25px;
    transition: transform 0.4s ease, filter 0.4s ease;
    filter: grayscale(30%);
    cursor: pointer;
    height: 250px; 
    width: 100%; 
    object-fit: cover;
}
.gallery-img:hover { 
    transform: scale(1.08) rotate(2deg); 
    filter: grayscale(0%); 
    box-shadow: 0 15px 30px rgba(39, 174, 96, 0.4) !important; 
}

.process-step {
    background: rgba(255,255,255,0.03);
    border-radius: 30px;
    padding: 40px;
    transition: 0.3s;
}
.process-step:hover { background: rgba(39, 174, 96, 0.1); }
.step-number {
    font-size: 4rem;
    font-weight: 900;
    opacity: 0.1;
    line-height: 1;
}
</style>

<section class="hero-about shadow-lg">
    <div class="hero-content container">
        <h1>Chạm Đến Ước Mơ</h1>
        <p>Hành trình vạn dặm bắt đầu từ một bước chân</p>
    </div>
</section>

<div class="container mt-n5" style="margin-top: -80px; position: relative; z-index: 10;">
    <div class="about-card">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="about-img-wrapper shadow-lg">
                    <img src="assets/about.jpg" class="img-fluid" alt="About">
                </div>
            </div>
            <div class="col-md-6 ps-md-5">
                <h2 class="mb-4">Chúng Tôi Là Ai?</h2>
                <p class="lead">Chúng tôi không chỉ bán tour, chúng tôi kiến tạo những kỷ niệm vô giá.</p>
                <div class="row mt-5">
                    <div class="col-6">
                        <h3>Sứ Mệnh</h3>
                        <p class="small opacity-75">Kết nối trái tim qua những vùng đất mới.</p>
                    </div>
                    <div class="col-6">
                        <h3>Tầm Nhìn</h3>
                        <p class="small opacity-75">Biểu tượng du lịch uy tín số 1 Việt Nam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="container py-5 mt-5">
    <div class="about-card" style="border-left: 5px solid #27ae60;">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-2 mb-3 mb-md-0">
                <img src="assets/ceo.jpg" class="rounded-circle shadow-lg" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #27ae60;">
            </div>
            <div class="col-md-10">
                <p class="fs-5 italic opacity-90" style="font-style: italic;">
                    "Sự hài lòng của bạn là kim chỉ nam cho mọi hoạt động của chúng tôi. Hãy để chúng tôi đồng hành cùng bạn trên mọi nẻo đường."
                </p>
                <h5 class="fw-bold mb-0">Lưu Tấn Khoa</h5>
                <small class="text-success text-uppercase fw-bold">Founder & CEO</small>
            </div>
        </div>
    </div>
</section>

<section class="container stats shadow">
    <div class="row text-center">
        <div class="col-md-3 stat-box"><h2>100+</h2><p class="text-white-50">Tours</p></div>
        <div class="col-md-3 stat-box"><h2>5K+</h2><p class="text-white-50">Khách Hàng</p></div>
        <div class="col-md-3 stat-box"><h2>50+</h2><p class="text-white-50">Điểm Đến</p></div>
        <div class="col-md-3 stat-box"><h2>05+</h2><p class="text-white-50">Năm Kinh Nghiệm</p></div>
    </div>
</section>

<section class="container py-5 mb-5">
    <h2 class="text-center mb-5 fs-1 text-uppercase">Khoảnh Khắc Hành Trình</h2>
    <div class="row g-3">
        <div class="col-md-4 mb-3"> 
            <img src="assets/top-10-dia-diem-du-lich-dep-noi-tieng-tai-tuyen-quang-202304271452589132.jpg" class="img-fluid gallery-img" alt="1">
        </div>
        <div class="col-md-4 mb-3"> 
            <img src="assets/khoang_khac_2.jpg" class="img-fluid gallery-img" alt="2">
        </div>
        <div class="col-md-4 mb-3"> 
            <img src="assets/khu-du-lich-khoang-xanh-suoi-tien-02-min.jpg" class="img-fluid gallery-img" alt="3">
        </div>
    </div>
</section>

<?php include("layout/footer.php"); ?>