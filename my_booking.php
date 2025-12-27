<?php
session_start();
include 'db.php';

/* ตรวจสอบสิทธิ์ */
if(!isset($_SESSION['user_id']) || $_SESSION['role']!='member'){
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare(
    "SELECT b.*, r.room_name
     FROM bookings b
     JOIN rooms r ON b.room_id = r.room_id
     WHERE b.user_id = :u
     ORDER BY b.booking_date DESC"
);
$stmt->execute([':u'=>$_SESSION['user_id']]);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>การจองของฉัน</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<div class="card shadow">
<div class="card-body">

<h4 class="mb-3">
<i class="bi bi-journal-text"></i> การจองของฉัน
</h4>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-secondary text-center">
<tr>
<th>ห้อง</th>
<th>วันที่</th>
<th>เวลา</th>
<th>ยกเลิก</th>
</tr>
</thead>
<tbody>

<?php if($stmt->rowCount() > 0){ ?>
<?php while($r = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
<tr>
<td><?= htmlspecialchars($r['room_name']) ?></td>
<td class="text-center"><?= htmlspecialchars($r['booking_date']) ?></td>
<td class="text-center">
<?= htmlspecialchars($r['start_time']." - ".$r['end_time']) ?>
</td>
<td class="text-center">
<a href="cancel_booking.php?id=<?= $r['booking_id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('ยืนยันการยกเลิกการจอง ?')">
<i class="bi bi-x-circle"></i> ยกเลิก
</a>
</td>
</tr>
<?php } ?>
<?php } else { ?>

<tr>
<td colspan="4" class="text-center text-muted">
<i class="bi bi-info-circle"></i> ยังไม่มีการจอง
</td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

<div class="text-end mt-3">
<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> กลับหน้าแรก
</a>
</div>

</div>
</div>

</div>
</body>
</html>
