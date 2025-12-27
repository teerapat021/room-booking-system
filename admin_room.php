<?php
include 'auth_admin.php';
include 'db.php';

/* ======================
   เพิ่มห้อง
====================== */
if(isset($_POST['add'])){
    $stmt = $conn->prepare(
        "INSERT INTO rooms (room_name, status, price)
         VALUES (:n, :s, :p)"
    );
    $stmt->execute([
        ':n' => $_POST['name'],
        ':s' => $_POST['status'],
        ':p' => $_POST['price']
    ]);
}

/* ======================
   ลบห้อง
====================== */
if(isset($_GET['delete'])){
    $stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_room.php");
    exit();
}

/* ======================
   แก้ไขห้อง
====================== */
if(isset($_POST['update'])){
    $stmt = $conn->prepare(
        "UPDATE rooms
         SET room_name = :n, status = :s, price = :p
         WHERE room_id = :id"
    );
    $stmt->execute([
        ':n'  => $_POST['name'],
        ':s'  => $_POST['status'],
        ':p'  => $_POST['price'],
        ':id' => $_POST['room_id']
    ]);
    header("Location: admin_room.php");
    exit();
}

/* ======================
   ดึงข้อมูลห้องทั้งหมด
====================== */
$rooms = $conn->query("SELECT * FROM rooms")->fetchAll(PDO::FETCH_ASSOC);

/* ======================
   ถ้ามีการกดแก้ไข
====================== */
$editRoom = null;
if(isset($_GET['edit'])){
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editRoom = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการห้องพักผ่อน</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
<h4>
<i class="bi bi-tools"></i> จัดการห้องพักผ่อน (Admin)
</h4>

<!-- ===== ฟอร์มเพิ่ม / แก้ไข ===== -->
<div class="card mb-4">
<div class="card-body">
<h5><?= $editRoom ? "แก้ไขห้อง" : "เพิ่มห้องใหม่" ?></h5>

<form method="post">
<input type="hidden" name="room_id" value="<?= $editRoom['room_id'] ?? '' ?>">

<div class="mb-2">
<label>ชื่อห้อง</label>
<input type="text" name="name" class="form-control"
value="<?= $editRoom['room_name'] ?? '' ?>" required>
</div>

<div class="mb-2">
<label>ราคา (บาท)</label>
<input type="number" name="price" class="form-control"
value="<?= $editRoom['price'] ?? '' ?>" required>
</div>

<div class="mb-2">
<label>สถานะ</label>
<select name="status" class="form-control" required>
<option value="ว่าง" <?= (isset($editRoom) && $editRoom['status']=='ว่าง')?'selected':'' ?>>ว่าง</option>
<option value="ไม่ว่าง" <?= (isset($editRoom) && $editRoom['status']=='ไม่ว่าง')?'selected':'' ?>>ไม่ว่าง</option>
</select>
</div>

<?php if($editRoom){ ?>
<button name="update" class="btn btn-warning">บันทึกการแก้ไข</button>
<a href="admin_room.php" class="btn btn-secondary">ยกเลิก</a>
<?php } else { ?>
<button name="add" class="btn btn-success">เพิ่มห้อง</button>
<?php } ?>

</form>
</div>
</div>

<!-- ===== ตารางแสดงห้อง ===== -->
<table class="table table-bordered table-striped">
<tr class="table-dark">
<th>ชื่อห้อง</th>
<th>สถานะ</th>
<th>ราคา</th>
<th width="150">จัดการ</th>
</tr>

<?php foreach($rooms as $r){ ?>
<tr>
<td><?= $r['room_name'] ?></td>
<td><?= $r['status'] ?></td>
<td><?= $r['price'] ?> บาท</td>
<td>
<a href="admin_room.php?edit=<?= $r['room_id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>
<a href="admin_room.php?delete=<?= $r['room_id'] ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('ยืนยันการลบห้องนี้?')">ลบ</a>
</td>
</tr>
<?php } ?>
</table>

<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> กลับหน้าแรก
</a>
</div>

</body>
</html>
