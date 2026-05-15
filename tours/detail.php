<?php
session_start();
include("../config/database.php");
include("../layout/header.php");

$id = $_GET['id'];

// 1. LẤY THÔNG TIN TOUR
$tourQuery = mysqli_query($conn,"SELECT * FROM tours WHERE id=$id");
$tour = mysqli_fetch_assoc($tourQuery);

// 2. LẤY LỊCH KHỞI HÀNH
$scheduleQuery = mysqli_query($conn,"
SELECT 
tour_id,
DATE_FORMAT(departure_date,'%Y-%m-%d') as departure_date,
price
FROM tour_schedule
WHERE tour_id=$id
ORDER BY departure_date ASC
");

$schedules = [];
while($row = mysqli_fetch_assoc($scheduleQuery)){
    $schedules[] = $row;
}

// 3. XỬ LÝ GỬI BÌNH LUẬN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vui lòng đăng nhập để đánh giá!');</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $rating = (int)$_POST['rating'];
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);

        $query = "INSERT INTO reviews (tour_id, user_id, rating, comment) VALUES ('$id', '$user_id', '$rating', '$comment')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Cảm ơn cậu đã đánh giá!'); window.location.href='detail.php?id=$id';</script>";
        }
    }
}

// 4. LẤY DANH SÁCH BÌNH LUẬN & SAO TRUNG BÌNH
$sql_reviews = "SELECT reviews.*, users.fullname FROM reviews 
                JOIN users ON reviews.user_id = users.id 
                WHERE tour_id = '$id' ORDER BY created_at DESC";
$result_reviews = mysqli_query($conn, $sql_reviews);

$sql_avg = mysqli_query($conn, "SELECT AVG(rating) as avg_rating FROM reviews WHERE tour_id = '$id'");
$data_avg = mysqli_fetch_assoc($sql_avg);
$avg_rating = round($data_avg['avg_rating'], 1) ?? 0;
?>

<style>
/* --- TỔNG THỂ DARK MODE --- */
body { 
    background-color: #121212; 
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
}

.tour-detail-img {
    height: 500px;
    width: 100%;
    object-fit: cover;
    border-radius: 40px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
    border: 1px solid rgba(255,255,255,0.1);
}

.content-section {
    background: #1e1e1e;
    padding: 40px;
    border-radius: 35px;
    border: 1px solid rgba(255,255,255,0.05);
    margin-top: 30px;
}

.price-tag {
    font-size: 2rem;
    font-weight: 900;
    color: #27ae60;
    text-shadow: 0 0 15px rgba(39, 174, 96, 0.3);
}

.info-label {
    color: #27ae60;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 25px;
    font-size: 1.1rem;
}

.text-description {
    color: #b0b0b0;
    line-height: 1.8;
    padding-left: 32px;
}

/* --- PHẦN BÌNH LUẬN --- */
.review-section {
    margin-top: 30px;
    background: #1e1e1e;
    padding: 30px;
    border-radius: 35px;
    border: 1px solid rgba(255,255,255,0.05);
}
.rating-star-display { color: #f1c40f; font-size: 1.1rem; }
.review-item {
    background: #121212;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid #27ae60;
}
.star-input { direction: rtl; display: inline-block; }
.star-input input { display: none; }
.star-input label { color: #444; font-size: 2.2rem; cursor: pointer; transition: 0.3s; }
.star-input input:checked ~ label, .star-input label:hover, .star-input label:hover ~ label { color: #f1c40f; }

/* --- SIDEBAR ĐẶT TOUR & LỊCH (ĐÃ FIX LỆCH) --- */
.booking-card {
    background: #1e1e1e;
    border-radius: 35px;
    border: 1px solid #27ae60;
    position: sticky;
    top: 100px;
    box-shadow: 0 15px 45px rgba(0,0,0,0.4);
}

.calendar-container {
    background: #121212;
    padding: 20px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.05);
}

/* Fix lỗi lệch nút điều hướng */
.calendar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.calendar-header h6 {
    margin: 0;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
}

#calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.day {
    background: rgba(255,255,255,0.03);
    padding: 10px 5px;
    text-align: center;
    border-radius: 12px;
    font-size: 13px;
    color: #888;
}

.day.active {
    background: #27ae60;
    color: white;
    cursor: pointer;
    font-weight: bold;
    transform: scale(1);
    transition: 0.2s;
}
.day.active:hover { transform: scale(1.1); background: #2ecc71; }

.day .price { font-size: 9px; display: block; color: #d1ffd6; }

.form-control {
    background: #121212 !important;
    border: 1px solid #333 !important;
    color: white !important;
    border-radius: 12px;
    padding: 12px;
}
</style>

<div class="container mt-5 pb-5">
    <div class="row">
        <div class="col-md-7">
            <img src="../uploads/<?php echo $tour['image']; ?>" class="tour-detail-img">

            <div class="content-section">
                <h2 class="mb-3"><?php echo $tour['tour_name']; ?></h2>
                <div class="price-tag mb-4">
                    <?php echo number_format($tour['price']); ?> <small style="font-size: 1rem; color: #888;">VNĐ / khách</small>
                </div>

                <hr style="border-color: rgba(255,255,255,0.1);">

                <div class="info-label"><i class="bi bi-geo-alt-fill"></i> ĐỊA ĐIỂM</div>
                <p class="text-description"><?php echo $tour['location']; ?></p>

                <div class="info-label"><i class="bi bi-clock-history"></i> THỜI GIAN</div>
                <p class="text-description"><?php echo $tour['duration']; ?></p>

                <div class="info-label"><i class="bi bi-info-circle-fill"></i> GIỚI THIỆU TOUR</div>
                <p class="text-description"><?php echo $tour['description']; ?></p>

                <div class="info-label text-success"><i class="bi bi-check-circle-fill"></i> DỊCH VỤ BAO GỒM</div>
                <div class="text-description text-white-50 small"><?php echo nl2br($tour['include_service']); ?></div>

                <div class="info-label text-danger"><i class="bi bi-x-circle-fill"></i> KHÔNG BAO GỒM</div>
                <div class="text-description text-white-50 small"><?php echo nl2br($tour['exclude_service']); ?></div>
            </div>

            <div class="review-section shadow">
                <h4 class="fw-bold text-white mb-4">Đánh giá khách hàng (<?= $avg_rating ?> ★)</h4>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                <div class="p-4 border border-secondary rounded-4 mb-5">
                    <h6 class="text-success fw-bold mb-3">Viết nhận xét của cậu:</h6>
                    <form action="" method="POST">
                        <div class="mb-2">
                            <div class="star-input">
                                <input type="radio" name="rating" value="5" id="s5" required><label for="s5">★</label>
                                <input type="radio" name="rating" value="4" id="s4"><label for="s4">★</label>
                                <input type="radio" name="rating" value="3" id="s3"><label for="s3">★</label>
                                <input type="radio" name="rating" value="2" id="s2"><label for="s2">★</label>
                                <input type="radio" name="rating" value="1" id="s1"><label for="s1">★</label>
                            </div>
                        </div>
                        <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Trải nghiệm của cậu thế nào..." required></textarea>
                        <button type="submit" name="submit_review" class="btn btn-success rounded-pill px-4 fw-bold">Gửi đánh giá</button>
                    </form>
                </div>
                <?php endif; ?>

                <div class="review-list">
                    <?php if(mysqli_num_rows($result_reviews) > 0): ?>
                        <?php while($rev = mysqli_fetch_assoc($result_reviews)): ?>
                            <div class="review-item">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-white"><?= htmlspecialchars($rev['fullname']) ?></span>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($rev['created_at'])) ?></small>
                                </div>
                                <div class="rating-star-display mb-2">
                                    <?php for($i=1; $i<=5; $i++) echo $i <= $rev['rating'] ? '★' : '☆'; ?>
                                </div>
                                <p class="text-secondary small mb-0"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">Chưa có bình luận nào.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card booking-card p-4">
                <h4 class="mb-4 text-center fw-bold text-success">ĐẶT TOUR NGAY</h4>
                
                <p class="small text-white-50 mb-2 ms-2">Chọn ngày khởi hành:</p>
                <div class="calendar-container mb-4">
                    <div class="calendar-header">
                        <button type="button" onclick="changeMonth(-1)" class="btn btn-sm btn-outline-success" style="width: 40px;">◀</button>
                        <h6 id="monthYear" class="text-white"></h6>
                        <button type="button" onclick="changeMonth(1)" class="btn btn-sm btn-outline-success" style="width: 40px;">▶</button>
                    </div>
                    <div id="calendar"></div>
                </div>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <form action="payment.php" method="POST">
                        <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                        <input type="hidden" name="total_price" id="totalPriceInput">

                        <div class="mb-3">
                            <label class="small text-white-50 mb-1">Ngày đã chọn</label>
                            <input type="text" id="selectedDate" name="departure_date" class="form-control" readonly required placeholder="Chọn ngày trên lịch">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="small text-white-50 mb-1">Số khách</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control" oninput="calculateTotal()">
                            </div>
                            <div class="col-6">
                                <label class="small text-white-50 mb-1">Tổng tiền</label>
                                <input type="text" id="totalPrice" class="form-control fw-bold text-success" readonly value="0 VNĐ">
                            </div>
                        </div>

                        <div class="mt-4">
                            <input type="text" name="fullname" class="form-control mb-3" placeholder="Họ tên" value="<?php echo $_SESSION['fullname']; ?>" required>
                            <input type="text" name="phone" class="form-control mb-3" placeholder="Số điện thoại" required>
                            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        </div>

                        <button class="btn btn-success w-100 fw-bold py-3 rounded-pill mt-2 shadow">TIẾP TỤC THANH TOÁN</button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-4 border border-danger border-dashed rounded-4">
                        <p class="text-danger fw-bold">Vui lòng đăng nhập để đặt tour</p>
                        <a href="../auth/login.php" class="btn btn-sm btn-outline-danger px-4 rounded-pill">Đăng nhập</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
let schedules = <?php echo json_encode($schedules); ?>;
let calendar = document.getElementById("calendar");
let currentDate = new Date();

function renderCalendar(){
    calendar.innerHTML="";
    let month = currentDate.getMonth();
    let year = currentDate.getFullYear();
    document.getElementById("monthYear").innerText = "THÁNG " + (month + 1) + " / " + year;

    let firstDay = new Date(year, month, 1).getDay();
    let daysInMonth = new Date(year, month + 1, 0).getDate();

    for(let i=0; i<firstDay; i++) { calendar.innerHTML+="<div></div>"; }

    for(let day=1; day<=daysInMonth; day++){
        let dateString = year + "-" + String(month + 1).padStart(2, '0') + "-" + String(day).padStart(2, '0');
        let schedule = schedules.find(s => s.departure_date === dateString);

        if(schedule){
            calendar.innerHTML += `
                <div class="day active" onclick="selectDate('${dateString}', ${schedule.price})">
                    ${day}
                    <div class="price">${(schedule.price/1000).toFixed(0)}K</div>
                </div>
            `;
        } else {
            calendar.innerHTML += `<div class="day">${day}</div>`;
        }
    }
}

function changeMonth(step){
    currentDate.setMonth(currentDate.getMonth() + step);
    renderCalendar();
}

function selectDate(date, price){
    document.getElementById("selectedDate").value = date;
    let qty = document.getElementById("quantity").value;
    let total = qty * price;
    document.getElementById("totalPrice").value = total.toLocaleString("vi-VN") + " VNĐ";
    document.getElementById("totalPriceInput").value = total;
    document.getElementById("selectedDate").style.borderColor = "#27ae60";
}

function calculateTotal(){
    let qty = document.getElementById("quantity").value;
    let date = document.getElementById("selectedDate").value;
    let schedule = schedules.find(s => s.departure_date === date);
    if(schedule){
        let total = qty * schedule.price;
        document.getElementById("totalPrice").value = total.toLocaleString("vi-VN") + " VNĐ";
        document.getElementById("totalPriceInput").value = total;
    }
}

renderCalendar();
</script>

<?php include("../layout/footer.php"); ?>