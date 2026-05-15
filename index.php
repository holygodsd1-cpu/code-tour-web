<?php
include("config/database.php");
include("layout/header.php");
?>

<style>
/* --- 1. TỔNG THỂ DARK MODE --- */
body {
    background-color: #1a1a1a; 
    color: #ffffff;
    font-family: 'Inter', sans-serif;
}

/* --- 2. HERO SECTION --- */
.hero {
    height: 85vh;
    background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url("https://images.unsplash.com/photo-1501785888041-af3ef285b470");
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-radius: 0 0 50px 50px;
}

.hero h1 {
    font-size: clamp(50px, 8vw, 100px); /* Tự co giãn chữ cho đẹp */
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -2px;
}

/* --- 3. FIX LỖI CHỮ TRẮNG NHÁCH TRONG THANH TÌM KIẾM --- */
.search-wrapper {
    margin-top: -65px;
    position: relative;
    z-index: 100;
}

.search-box-modern {
    background: #ffffff !important; /* Luôn giữ nền trắng cho thanh tìm kiếm */
    border-radius: 100px;
    padding: 10px 35px;
    display: flex;
    align-items: center;
    box-shadow: 0 15px 45px rgba(0,0,0,0.4);
}

.search-item {
    flex: 1;
    padding: 0 20px;
    border-right: 1px solid #eee;
}

.search-item:last-child { border: none; }

.search-item label {
    display: block;
    font-size: 11px;
    font-weight: 800;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 2px;
}

/* ÉP MÀU CHỮ TỐI Ở ĐÂY */
.search-item input, 
.search-item select {
    border: none !important;
    outline: none !important;
    font-weight: 700;
    width: 100%;
    color: #212529 !important; /* Chữ màu đen xám cực rõ */
    background: transparent !important;
    font-size: 15px;
}

/* Đổi màu chữ gợi ý */
.search-item input::placeholder {
    color: #aaa !important;
    font-weight: 400;
}

.btn-search-go {
    background: #27ae60;
    color: white !important;
    border: none;
    border-radius: 50%;
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: 0.3s ease;
}

.btn-search-go:hover {
    background: #2ecc71;
    transform: rotate(90deg) scale(1.1);
}

/* --- 4. CARD TOUR DARK STYLE --- */
.tour-card {
    background: #262626 !important;
    border: none !important;
    border-radius: 30px !important; 
    overflow: hidden;
    transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
}

.tour-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.5) !important;
}

.img-container {
    position: relative;
    overflow: hidden;
}

.img-container img {
    height: 260px;
    width: 100%;
    object-fit: cover;
    transition: 0.8s;
}

.tour-card:hover .img-container img {
    transform: scale(1.15);
}

.badge-rating {
    position: absolute;
    top: 20px;
    left: 20px;
    background: #f39c12;
    color: white;
    padding: 6px 14px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 13px;
    z-index: 2;
}

.tour-title-link {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 12px;
    min-height: 2.8rem;
}

.tour-title-link:hover { color: #27ae60; }

.price-big {
    font-size: 1.4rem;
    font-weight: 800;
    color: #ffffff;
}

.btn-dat-ngay {
    background: #27ae60;
    color: white !important;
    border-radius: 12px;
    padding: 7px 18px;
    font-weight: 700;
    text-decoration: none;
    font-size: 13px;
    transition: 0.3s;
}

.btn-dat-ngay:hover {
    background: #ffffff;
    color: #27ae60 !important;
}

.section-header h2 {
    font-weight: 800;
    font-size: 2.5rem;
    letter-spacing: -1px;
}
</style>

<div class="hero">
    <div class="container">
        <h1>TOURS DU LỊCH</h1>
        <p class="opacity-75">Khám Phá Kho Báu Việt Nam Cùng Travela</p>
    </div>
</div>

<div class="container search-wrapper">
    <div class="search-box-modern mx-auto" style="max-width: 1000px;">
        <form action="tours/list.php" method="GET" class="d-flex w-100 align-items-center">
            <div class="search-item">
                <label><i class="bi bi-geo-alt text-success"></i> Điểm đến</label>
                <input type="text" name="keyword" placeholder="Bạn muốn đi đâu?">
            </div>
            <div class="search-item">
                <label><i class="bi bi-calendar-check text-success"></i> Ngày khởi hành</label>
                <input type="text" placeholder="Chọn ngày" onfocus="(this.type='date')" onblur="(this.type='text')">
            </div>
            <div class="search-item">
                <label><i class="bi bi-people text-success"></i> Khách hàng</label>
                <select name="guests" class="form-select border-0 shadow-none">
                    <option value="1">1 Người</option>
                    <option value="2">2 Người</option>
                    <option value="3">3 Người+</option>
                </select>
            </div>
            <button type="submit" class="btn-search-go shadow">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="container mt-5 pt-5 mb-5">
    <div class="section-header d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="mb-1">Tour Nổi Bật</h2>
            <p class="text-white-50 mb-0">Hành trình trải nghiệm đẳng cấp 2026</p>
        </div>
        <a href="tours/list.php" class="text-success text-decoration-none fw-bold">
            Tất cả tour <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="row">
        <?php
        $sql = "SELECT * FROM tours ORDER BY id DESC LIMIT 4"; 
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)){
        ?>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card tour-card shadow">
                <div class="img-container">
                    <div class="badge-rating"><i class="bi bi-star-fill"></i> 5.0</div>
                    <img src="uploads/<?php echo $row['image']; ?>" alt="tour">
                </div>
                <div class="card-body p-4">
                    <p class="text-white-50 small mb-2"><i class="bi bi-geo-alt"></i> <?php echo $row['location'] ?? 'Việt Nam'; ?></p>
                    <a href="tours/detail.php?id=<?php echo $row['id']; ?>" class="tour-title-link">
                        <?php echo $row['tour_name']; ?>
                    </a>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="price-big">
                            <?php echo number_format($row['price']); ?> 
                            <small style="font-size: 12px; opacity: 0.5;">₫</small>
                        </div>
                        <a href="tours/detail.php?id=<?php echo $row['id']; ?>" class="btn-dat-ngay">Đặt Ngay</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo "<p class='text-center opacity-50'>Đang cập nhật tour...</p>";
        }
        ?>
    </div>
</div>

<div class="container mb-5 py-5" style="background: #262626; border-radius: 40px;">
    <h2 class="text-center fw-bold mb-5">Khám Phá Theo Điểm Đến</h2>
    <div class="row g-3 px-4 text-center">
        <?php 
        $locs = [['Phu Quoc', 'Phú Quốc'], ['Da Nang', 'Đà Nẵng'], ['Da Lat', 'Đà Lạt'], ['Ha Long', 'Hạ Long']];
        foreach($locs as $l):
        ?>
        <div class="col-md-3 col-6">
            <a href="tours/list.php?location=<?php echo $l[0]; ?>" class="btn w-100 py-3 fw-bold text-white shadow-sm" style="background: #333; border-radius: 20px; border: 1px solid #444;">
                <i class="bi bi-compass text-success me-2"></i> <?php echo $l[1]; ?>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include("layout/footer.php"); ?>