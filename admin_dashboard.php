<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: index.php");
    exit();
}

/* นับข้อมูล */
$totalMembers = $conn->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetchColumn();
$totalRooms   = $conn->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$totalBooking = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

/* กราฟการจองแต่ละห้อง */
$roomNames = [];
$roomCount = [];

$stmt = $conn->query("
SELECT rooms.room_name, COUNT(bookings.booking_id) AS total
FROM rooms
LEFT JOIN bookings ON rooms.room_id = bookings.room_id
GROUP BY rooms.room_id
");

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $roomNames[] = $row['room_name'];
    $roomCount[] = $row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

<h3 class="mb-4">
<i class="bi bi-speedometer2"></i> Dashboard ผู้ดูแลระบบ
</h3>

<div class="row mb-4">
<div class="col-md-4">
<div class="card text-bg-primary shadow">
<div class="card-body">
<i class="bi bi-people fs-3"></i>
<h5>สมาชิก</h5>
<h3><?= $totalMembers ?> คน</h3>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-bg-warning shadow">
<div class="card-body">
<i class="bi bi-door-open fs-3"></i>
<h5>ห้องทั้งหมด</h5>
<h3><?= $totalRooms ?> ห้อง</h3>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-bg-success shadow">
<div class="card-body">
<i class="bi bi-calendar-check fs-3"></i>
<h5>การจอง</h5>
<h3><?= $totalBooking ?> รายการ</h3>
</div>
</div>
</div>
</div>

<div class="card shadow">
<div class="card-body">
<h5><i class="bi bi-bar-chart"></i> สถิติการจองแต่ละห้อง</h5>
<canvas id="roomChart"></canvas>
</div>
</div>

<div class="mt-3">
<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> กลับหน้าแรก
</a>
</div>

</div>

<script>
new Chart(document.getElementById('roomChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($roomNames) ?>,
        datasets: [{
            label: 'จำนวนการจอง',
            data: <?= json_encode($roomCount) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    }
});
</script>

</body>
</html>
