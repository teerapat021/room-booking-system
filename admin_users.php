<?php
session_start();
include 'db.php';

/* ===== ตรวจสอบสิทธิ์แอดมิน ===== */
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}

/* ===== ดึงจำนวนสมาชิก ===== */
$countStmt = $conn->query("SELECT COUNT(*) FROM users WHERE role='member'");
$totalMembers = $countStmt->fetchColumn();

/* ===== ดึงข้อมูลสมาชิก ===== */
$stmt = $conn->prepare("
    SELECT user_id, fname, lname, username, phone, email, profile_img
    FROM users
    WHERE role='member'
    ORDER BY user_id DESC
");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการสมาชิก</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<div class="card shadow">
<div class="card-body">

<h4 class="mb-3">
<i class="bi bi-people-fill"></i> รายชื่อสมาชิกทั้งหมด
</h4>

<div class="alert alert-info">
<i class="bi bi-person-check"></i>
จำนวนสมาชิกทั้งหมด : <strong><?= $totalMembers ?></strong> คน
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-dark text-center">
<tr>
<th>#</th>
<th>รูป</th>
<th>ชื่อ - นามสกุล</th>
<th>ชื่อผู้ใช้</th>
<th>เบอร์โทร</th>
<th>อีเมล</th>
<th>จัดการ</th>
</tr>
</thead>
<tbody>

<?php if(count($members)>0){ ?>
<?php $i=1; foreach($members as $m){ ?>
<tr>
<td class="text-center"><?= $i++ ?></td>

<td class="text-center">
<img src="uploads/<?= htmlspecialchars($m['profile_img']) ?>"
     class="rounded-circle border"
     width="50" height="50">
</td>

<td><?= htmlspecialchars($m['fname']." ".$m['lname']) ?></td>
<td><?= htmlspecialchars($m['username']) ?></td>
<td><?= htmlspecialchars($m['phone']) ?></td>
<td><?= htmlspecialchars($m['email']) ?></td>

<td class="text-center">
<a href="admin_delete_user.php?id=<?= $m['user_id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('ยืนยันการลบสมาชิกคนนี้ ?')">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>
<?php } ?>
<?php } else { ?>

<tr>
<td colspan="7" class="text-center text-danger">
<i class="bi bi-exclamation-circle"></i> ยังไม่มีสมาชิก
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
