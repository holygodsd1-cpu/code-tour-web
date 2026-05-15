<?php
session_start();
include("../config/database.php");

$id = $_GET['id'];

/* Xóa lịch tour trước */
mysqli_query($conn,"DELETE FROM tour_schedule WHERE tour_id=$id");

/* Sau đó mới xóa tour */
mysqli_query($conn,"DELETE FROM tours WHERE id=$id");

header("Location: manage_tours.php");
?>