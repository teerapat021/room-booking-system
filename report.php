<?php
include 'auth_admin.php';
include 'db.php';

$stmt=$conn->query(
    "SELECT b.*,u.username,r.room_name
     FROM bookings b
     JOIN users u ON b.user_id=u.user_id
     JOIN rooms r ON b.room_id=r.room_id"
);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>รายงาน</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h4>รายงานการจอง</h4>
<table class="table table-bordered">
<tr><th>ผู้จอง</th><th>ห้อง</th><th>วันที่</th><th>เวลา</th></tr>
<?php while($r=$stmt->fetch(PDO::FETCH_ASSOC)){ ?>
<tr>
<td><?= $r['username'] ?></td>
<td><?= $r['room_name'] ?></td>
<td><?= $r['booking_date'] ?></td>
<td><?= $r['start_time']." - ".$r['end_time'] ?></td>
</tr>
<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> กลับหน้าแรก
</a>
<?php } ?>
</table>
</div>
</body>
</html>