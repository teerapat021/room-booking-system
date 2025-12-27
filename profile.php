<?php
session_start();
include 'db.php';

/* ===== ตรวจสอบการเข้าสู่ระบบ ===== */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

/* ===== อัปเดตข้อมูล + รูป ===== */
if(isset($_POST['update'])){

    /* ---- อัปโหลดรูปโปรไฟล์ ---- */
    if(!empty($_FILES['profile_img']['name'])){
        $file = $_FILES['profile_img'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // อนุญาตเฉพาะไฟล์รูป
        $allow = ['jpg','jpeg','png','gif'];
        if(in_array($ext,$allow)){
            $newName = "user".$user_id.".".$ext;
            move_uploaded_file($file['tmp_name'], "uploads/".$newName);

            $conn->prepare(
                "UPDATE users SET profile_img=? WHERE user_id=?"
            )->execute([$newName, $user_id]);
        }
    }

    /* ---- อัปเดตข้อมูลส่วนตัว ---- */
    $stmt = $conn->prepare(
        "UPDATE users SET
            fname = :f,
            lname = :l,
            phone = :p,
            email = :e
         WHERE user_id = :id"
    );
    $stmt->execute([
        ':f'  => $_POST['fname'],
        ':l'  => $_POST['lname'],
        ':p'  => $_POST['phone'],
        ':e'  => $_POST['email'],
        ':id' => $user_id
    ]);

    $msg = "บันทึกข้อมูลเรียบร้อยแล้ว";
}

/* ===== ดึงข้อมูลผู้ใช้ ===== */
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>โปรไฟล์ผู้ใช้</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container col-md-6 mt-4">

<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-3">
<i class="bi bi-person-lines-fill"></i> โปรไฟล์ผู้ใช้
</h4>

<?php if($msg){ ?>
<div class="alert alert-success text-center">
<i class="bi bi-check-circle"></i> <?= $msg ?>
</div>
<?php } ?>

<div class="text-center mb-3">
<img src="uploads/<?= htmlspecialchars($user['profile_img']) ?>"
     class="rounded-circle border"
     width="140" height="140">
</div>

<form method="post" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">
<i class="bi bi-image"></i> เปลี่ยนรูปโปรไฟล์
</label>
<input type="file" name="profile_img" class="form-control">
</div>

<div class="row">
<div class="col">
<label><i class="bi bi-person"></i> ชื่อ</label>
<input name="fname" class="form-control"
       value="<?= htmlspecialchars($user['fname']) ?>" required>
</div>
<div class="col">
<label><i class="bi bi-person"></i> นามสกุล</label>
<input name="lname" class="form-control"
       value="<?= htmlspecialchars($user['lname']) ?>" required>
</div>
</div>

<div class="mt-2">
<label><i class="bi bi-person-badge"></i> ชื่อผู้ใช้</label>
<input class="form-control"
       value="<?= htmlspecialchars($user['username']) ?>" disabled>
</div>

<div class="mt-2">
<label><i class="bi bi-telephone"></i> เบอร์โทรศัพท์</label>
<input name="phone" class="form-control"
       value="<?= htmlspecialchars($user['phone']) ?>">
</div>

<div class="mt-2">
<label><i class="bi bi-envelope"></i> อีเมล</label>
<input type="email" name="email" class="form-control"
       value="<?= htmlspecialchars($user['email']) ?>">
</div>

<button class="btn btn-warning w-100 mt-3" name="update">
<i class="bi bi-save"></i> บันทึกการแก้ไข
</button>

</form>

<div class="text-center mt-3">
<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> กลับหน้าแรก
</a>
</div>

</div>
</div>

</div>
</body>
</html>
