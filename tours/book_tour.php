<?php
session_start();
include("../config/database.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php?error=1");
    exit();
}

if(!isset($_GET['tour_id'])){
    echo "Tour không tồn tại";
    exit();
}

$tour_id = $_GET['tour_id'];

$tourQuery = mysqli_query($conn,"SELECT * FROM tours WHERE id=$tour_id");
$tour = mysqli_fetch_assoc($tourQuery);
?>

<div class="container mt-4">

<h2>Đặt tour: <?= $tour['tour_name'] ?></h2>

<form method="POST" action="payment.php">

<input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
<input type="hidden" name="price" value="<?= $tour['price'] ?>">

<div class="mb-3">
<label>Họ và tên</label>
<input type="text" name="fullname" class="form-control" required>
</div>

<div class="mb-3">
<label>Số điện thoại</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Ngày khởi hành</label>
<input type="date" name="departure_date" class="form-control" required>
</div>

<div class="mb-3">
<label>Số lượng</label>
<input type="number" name="quantity" class="form-control" required>
</div>

<button type="submit" class="btn btn-primary">
Tiếp tục thanh toán
</button>

</form>

</div>

<?php include("../layout/footer.php"); ?>