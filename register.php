<?php
session_start();
include 'db.php';

$msg = "";
$success = false;

if(isset($_POST['register'])){
    $data = [
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'username' => $_POST['username'],
        'password' => md5($_POST['password']),
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'role' => 'member'
    ];

    // เช็ก username ซ้ำ
    $check = $conn->prepare("SELECT user_id FROM users WHERE username=:u");
    $check->execute([':u'=>$data['username']]);

    if($check->rowCount()>0){
        $msg = "❌ ชื่อผู้ใช้นี้มีอยู่แล้ว";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO users
            (fname,lname,username,password,phone,email,role)
            VALUES(:fname,:lname,:username,:password,:phone,:email,:role)"
        );
        $stmt->execute($data);
        $msg = "✅ สมัครสมาชิกสำเร็จ";
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>สมัครสมาชิก</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container col-md-5 mt-4">
<h4 class="text-center">
<i class="bi bi-person-plus"></i> <i class="bi bi-check-circle"></i> สมัครสมาชิก
</h4>

<?php if($msg){ ?>
<div class="alert <?= $success?'alert-success':'alert-danger' ?>">
<?= $msg ?>
</div>
<?php } ?>

<?php if(!$success){ ?>
<form method="post">

<div class="row">
<div class="col">
<input name="fname" class="form-control mb-2" placeholder="ชื่อ" required>
</div>
<div class="col">
<input name="lname" class="form-control mb-2" placeholder="นามสกุล" required>
</div>
</div>

<input name="username" class="form-control mb-2" placeholder="ชื่อผู้ใช้" required>
<input type="password" name="password" class="form-control mb-2" placeholder="รหัสผ่าน" required>
<input name="phone" class="form-control mb-2" placeholder="เบอร์โทรศัพท์" required>
<input type="email" name="email" class="form-control mb-2" placeholder="อีเมล" required>

<button name="register" class="btn btn-success w-100 mb-2">
สมัครสมาชิก
</button>

<a href="index.php" class="btn btn-secondary w-100">
ยกเลิกสมัคร
</a>

</form>

<div class="text-center mt-3">
มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a>
</div>

<?php } else { ?>
<a href="login.php" class="btn btn-primary w-100 mb-2">
ไปหน้าเข้าสู่ระบบ
</a>
<a href="index.php" class="btn btn-secondary w-100">
กลับหน้าแรก
</a>
<?php } ?>

</div>
</body>
</html>
