<?php
session_start();
include 'db.php';

/* ต้องเป็นสมาชิก */
if(!isset($_SESSION['user_id']) || $_SESSION['role']!='member'){
    header("Location: index.php");
    exit();
}

/* ตรวจสอบ id */
if(!isset($_GET['id'])){
    header("Location: my_booking.php");
    exit();
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

/* ดึง room_id ก่อน */
$stmt = $conn->prepare(
    "SELECT room_id FROM bookings WHERE booking_id=? AND user_id=?"
);
$stmt->execute([$booking_id, $user_id]);
$room_id = $stmt->fetchColumn();

if(!$room_id){
    header("Location: my_booking.php");
    exit();
}

/* ลบการจอง */
$conn->prepare(
    "DELETE FROM bookings WHERE booking_id=? AND user_id=?"
)->execute([$booking_id, $user_id]);

/* เปลี่ยนสถานะห้องเป็นว่าง */
$conn->prepare(
    "UPDATE rooms SET status='ว่าง' WHERE room_id=?"
)->execute([$room_id]);

header("Location: my_booking.php");
exit();
