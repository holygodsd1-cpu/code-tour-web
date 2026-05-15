<?php
session_start();
include("../config/database.php");
include("../layout/header.php");

// 1. LẤY DỮ LIỆU TỪ BỘ LỌC
$price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : 20000000;
$regions = isset($_GET['regions']) ? $_GET['regions'] : []; 

// 2. SQL NÂNG CAO (Lấy sao trung bình và lượt đánh giá)
$sql = "SELECT tours.*, 
               AVG(reviews.rating) as avg_rating, 
               COUNT(reviews.id) as total_reviews 
        FROM tours 
        LEFT JOIN reviews ON tours.id = reviews.tour_id 
        WHERE tours.price <= $price_max";

// 3. LOGIC LỌC VÙNG MIỀN
if (!empty($regions)) {
    $sql .= " AND (";
    $sub_cond = [];
    $region_map = [
        'Bắc' => ['Hà Nội', 'Sapa', 'Hạ Long', 'Ninh Bình', 'Hà Giang', 'Lào Cai', 'Bắc', 'Tây Bắc', 'Đông Bắc'],
        'Trung' => ['Đà Nẵng', 'Huế', 'Hội An', 'Nha Trang', 'Đà Lạt', 'Quảng Bình', 'Phan Thiết', 'Quy Nhơn', 'Trung'],
        'Nam' => ['Sài Gòn', 'TPHCM', 'Vũng Tàu', 'Cần Thơ', 'Phú Quốc', 'Kiên Giang', 'Mũi Né', 'Bến Tre', 'Nam']
    ];
    foreach($regions as $r) {
        if (isset($region_map[$r])) {
            foreach ($region_map[$r] as $city) {
                $city_safe = mysqli_real_escape_string($conn, $city);
                $sub_cond[] = "tours.location LIKE '%$city_safe%'";
            }
        }
    }
    $sql .= implode(" OR ", $sub_cond) . ")";
}

$sql .= " GROUP BY tours.id ORDER BY tours.id DESC";
$result = mysqli_query($conn, $sql);
?>

<style>
/* --- TỔNG THỂ --- */
body { background-color: #0a0a0a; color: #e0e0e0; font-family: 'Inter', sans-serif; }

/* --- BANNER ÁNH SÁNG ĐỘNG --- */
.banner-section {
    position: relative;
    height: 380px;
    background: #000;
    display: flex; 
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 0 0 60px 60px;
    margin-bottom: 50px;
    border-bottom: 1px solid rgba(39, 174, 96, 0.3);
}

.banner-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 50% 50%, rgba(39, 174, 96, 0.25) 0%, rgba(0,0,0,1) 80%);
    z-index: 1;
}

.banner-section::after {
    content: "";
    position: absolute;
    width: 200%;
    height: 200%;
    background: conic-gradient(from 180deg at 50% 50%, transparent 0%, #27ae60 25%, transparent 50%);
    animation: rotateGlow 8s linear infinite;
    opacity: 0.1;
    z-index: 0;
}

@keyframes rotateGlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.banner-content { position: relative; z-index: 2; text-align: center; }
.banner-content h1 {
    font-size: 4rem;
    font-weight: 900;
    text-transform: uppercase;
    background: linear-gradient(to bottom, #ffffff, #2ecc71);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 0 15px rgba(39, 174, 96, 0.5));
}

/* --- SIDEBAR --- */
.filter-card {
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 25px;
    background: #161616;
    padding: 25px !important;
}
.filter-card h5 { color: #27ae60; font-weight: 800; border-left: 4px solid #27ae60; padding-left: 15px; }

/* --- CSS CẬP NHẬT: ĐỔI MÀU CHỮ MIỀN --- */
.region-label {
    color: #ffffff !important; /* Ép buộc màu trắng */
    font-weight: 500;
}
/* Tùy chỉnh màu checkbox cho hợp tone */
.form-check-input {
    background-color: #1a1a1a;
    border-color: #333;
}
.form-check-input:checked {
    background-color: #27ae60;
    border-color: #27ae60;
}

/* --- TOUR CARD --- */
.tour-card {
    background: #161616;
    border: none;
    border-radius: 30px;
    overflow: hidden;
    transition: 0.4s;
}
.tour-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.5); }
.img-wrapper { position: relative; height: 240px; }
.img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
.badge-hot {
    position: absolute;
    top: 15px; right: 15px;
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    padding: 5px 12px; border-radius: 20px;
    font-size: 11px; font-weight: bold; z-index: 5;
}
.rating-box { color: #f1c40f; font-weight: bold; font-size: 0.9rem; }
.tour-title { color: #fff; text-decoration: none; font-weight: 700; font-size: 1.1rem; display: block; transition: 0.2s; }
.tour-title:hover { color: #27ae60; }
.price-box { color: #27ae60; font-size: 1.4rem; font-weight: 900; }
.btn-book {
    background: #27ae60; color: white; border: none;
    padding: 10px 25px; border-radius: 50px; font-weight: bold; transition: 0.3s;
    text-decoration: none;
}
.btn-book:hover { background: #fff; color: #27ae60; }
</style>

<section class="banner-section">
    <div class="banner-glow"></div>
    <div class="banner-content">
        <h1>Khám Phá Hành Trình</h1>
        <p class="text-success fw-bold" style="letter-spacing: 3px; font-size: 0.8rem; opacity: 0.8;">HÀNH TRÌNH ĐẲNG CẤP & KHÁC BIỆT</p>
    </div>
</section>

<div class="container pb-5">
    <form action="" method="GET">
        <div class="row">
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <div class="card filter-card mb-4">
                        <h5 class="mb-3">Lọc theo giá</h5>
                        <input type="range" name="price_max" class="form-range" id="priceRange" 
                               min="0" max="20000000" step="500000" value="<?= $price_max ?>">
                        <div class="d-flex justify-content-between mt-2 text-success fw-bold">
                            <span>0đ</span>
                            <span id="priceValue"><?= number_format($price_max, 0, ',', '.') ?>đ</span>
                        </div>
                    </div>

                    <div class="card filter-card mb-4">
                        <h5 class="mb-3">Điểm đến</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="regions[]" value="Bắc" id="b" <?= in_array('Bắc', $regions) ? 'checked' : '' ?>>
                            <label for="b" class="ms-2 region-label">Miền Bắc</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="regions[]" value="Trung" id="t" <?= in_array('Trung', $regions) ? 'checked' : '' ?>>
                            <label for="t" class="ms-2 region-label">Miền Trung</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="regions[]" value="Nam" id="n" <?= in_array('Nam', $regions) ? 'checked' : '' ?>>
                            <label for="n" class="ms-2 region-label">Miền Nam</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow">ÁP DỤNG</button>
                    <a href="tours.php" class="btn btn-link w-100 text-secondary text-decoration-none text-center mt-2 d-block">Xóa lọc</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)) { 
                        $rating = round($row['avg_rating'], 1) ?: 0;
                    ?>
                        <div class="col-md-4 mb-4">
                            <div class="card tour-card h-100 shadow">
                                <div class="img-wrapper">
                                    <div class="badge-hot">Hot 🔥</div>
                                    <img src="../uploads/<?= $row['image'] ?>">
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-success fw-bold"><i class="bi bi-geo-alt-fill"></i> <?= $row['location'] ?></small>
                                        <div class="rating-box">
                                            <i class="bi bi-star-fill"></i> <?= $rating ?> 
                                            <span class="text-muted small" style="font-weight: normal;">(<?= $row['total_reviews'] ?>)</span>
                                        </div>
                                    </div>
                                    <a href="detail.php?id=<?= $row['id'] ?>" class="tour-title flex-grow-1"><?= $row['tour_name'] ?></a>
                                    <hr style="border-color: #333; margin: 15px 0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price-box"><?= number_format($row['price']) ?><small style="font-size: 0.7rem;">đ</small></div>
                                        <a href="detail.php?id=<?= $row['id'] ?>" class="btn-book">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <div class="text-center py-5 w-100"><h4 class="opacity-50">Không tìm thấy tour nào!</h4></div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const rangeInput = document.getElementById('priceRange');
const priceValue = document.getElementById('priceValue');
rangeInput.addEventListener('input', function() {
    priceValue.textContent = parseInt(this.value).toLocaleString('vi-VN') + 'đ';
});
</script>

<?php include("../layout/footer.php"); ?>