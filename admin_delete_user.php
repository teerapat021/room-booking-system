<?php
session_start();
include 'db.php';

/* ตรวจสอบสิทธิ์แอดมิน */
if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: index.php");
    exit();
}

/* ตรวจสอบ user_id */
if(!isset($_GET['id'])){
    header("Location: admin_users.php");
    exit();
}

$user_id = $_GET['id'];

/* ห้ามลบแอดมิน */
$stmt = $conn->prepare("SELECT role FROM users WHERE user_id=?");
$stmt->execute([$user_id]);
$role = $stmt->fetchColumn();

if($role == 'admin'){
    header("Location: admin_users.php");
    exit();
}

/* ลบสมาชิก */
$conn->prepare("DELETE FROM users WHERE user_id=?")->execute([$user_id]);

header("Location: admin_users.php");
exit();
