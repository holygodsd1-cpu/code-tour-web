<?php
session_start();
include("../config/database.php");

$user_id = $_SESSION['user_id'];

$tour_id = $_POST['tour_id'];
$departure_date = $_POST['departure_date'];
$quantity = $_POST['quantity'];

$tourQuery = mysqli_query($conn,"SELECT price FROM tours WHERE id=$tour_id");
$tour = mysqli_fetch_assoc($tourQuery);

$total_price = $tour['price'] * $quantity;

mysqli_query($conn,"
INSERT INTO bookings(user_id,tour_id,booking_date,quantity,total_price,status,departure_date)
VALUES('$user_id','$tour_id',NOW(),'$quantity','$total_price','Pending','$departure_date')
");

header("Location: booking_success.php");
exit();
?>