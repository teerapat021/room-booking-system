<?php
$host = "localhost";
$dbname = "room_booking";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ");
}
