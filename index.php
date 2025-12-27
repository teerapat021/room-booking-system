<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจองห้องพักผ่อน</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ===== Navbar ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
<div class="container">
<a class="navbar-brand" href="index.php">
<i class="bi bi-house-door-fill"></i> ระบบจองห้องพักผ่อน
</a>

<div class="ms-auto">
<?php if(isset($_SESSION['user_id'])){ ?>

<a href="profile.php" class="btn btn-outline-info btn-sm me-2">
<i class="bi bi-person-circle"></i> โปรไฟล์
</a>

<a href="logout.php" class="btn btn-outline-danger btn-sm">
<i class="bi bi-box-arrow-right"></i> ออกจากระบบ
</a>

<?php } else { ?>

<a href="login.php" class="btn btn-success btn-sm me-2">
<i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
</a>

<a href="register.php" class="btn btn-primary btn-sm">
<i class="bi bi-person-plus"></i> สมัครสมาชิก
</a>

<?php } ?>
</div>
</div>
</nav>

<!-- ===== Content ===== -->
<div class="container mt-4">

<div class="card shadow">
<div class="card-body">

<h4 class="mb-3">
<i class="bi bi-table"></i> ตารางห้องพักผ่อน
</h4>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-secondary text-center">
<tr>
<th>ชื่อห้อง</th>
<th>สถานะ</th>
<th>ราคา</th>
<th>การใช้งาน</th>
</tr>
</thead>
<tbody>

<?php
$stmt = $conn->query("SELECT * FROM rooms ORDER BY room_id ASC");
while($r = $stmt->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
<td><?= htmlspecialchars($r['room_name']) ?></td>

<td class="text-center">
<?php if($r['status']=='ว่าง'){ ?>
<span class="badge bg-success">ว่าง</span>
<?php } else { ?>
<span class="badge bg-danger">ไม่ว่าง</span>
<?php } ?>
</td>

<td class="text-center"><?= number_format($r['price']) ?> บาท</td>

<td class="text-center">
<?php if($r['status']=='ว่าง'){ ?>
    <?php if(isset($_SESSION['role']) && $_SESSION['role']=='member'){ ?>
        <a href="booking.php?id=<?= $r['room_id'] ?>"
           class="btn btn-success btn-sm">
           <i class="bi bi-calendar-check"></i> จอง
        </a>
    <?php } else { ?>
        <span class="text-muted">
        <i class="bi bi-lock"></i> สมัครสมาชิกก่อน
        </span>
    <?php } ?>
<?php } else { ?>
    <span class="text-muted">ไม่สามารถจองได้</span>
<?php } ?>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</div>
</div>

<!-- ===== เมนูสำหรับสมาชิก ===== -->
<?php if(isset($_SESSION['role']) && $_SESSION['role']=='member'){ ?>
<div class="mt-3 text-end">
<a href="my_booking.php" class="btn btn-secondary">
<i class="bi bi-journal-text"></i> การจองของฉัน
</a>
</div>
<?php } ?>

<!-- ===== เมนูสำหรับแอดมิน ===== -->
<?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'){ ?>
<hr>
<div class="mt-3">
<h5><i class="bi bi-gear-fill"></i> เมนูผู้ดูแลระบบ</h5>

<a href="admin_dashboard.php" class="btn btn-dark me-2">
<i class="bi bi-speedometer2"></i> Dashboard
</a>

<a href="admin_room.php" class="btn btn-warning me-2">
<i class="bi bi-door-open"></i> จัดการห้อง
</a>

<a href="admin_users.php" class="btn btn-primary me-2">
<i class="bi bi-people"></i> จัดการสมาชิก
</a>

<a href="report.php" class="btn btn-info">
<i class="bi bi-file-earmark-bar-graph"></i> รายงานการจอง
</a>
</div>
<?php } ?>

</div>
</body>
</html>