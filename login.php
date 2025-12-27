<?php
session_start();
include 'db.php';

$error = "";

if(isset($_POST['login'])){
    $stmt = $conn->prepare(
        "SELECT * FROM users
         WHERE username=:u AND password=:p"
    );
    $stmt->execute([
        ':u'=>$_POST['username'],
        ':p'=>md5($_POST['password'])
    ]);

    if($stmt->rowCount()==1){
        $u = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $u['user_id'];
        $_SESSION['role'] = $u['role'];
        $_SESSION['username'] = $u['username'];
        header("Location:index.php");
        exit();
    } else {
        $error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>เข้าสู่ระบบ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container col-md-4 mt-5">
<h4 class="text-center">
<i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
</h4>

<?php if($error){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="post">
<input name="username" class="form-control mb-2" placeholder="ชื่อผู้ใช้" required>
<input type="password" name="password" class="form-control mb-2" placeholder="รหัสผ่าน" required>

<button name="login" class="btn btn-primary w-100 mb-2">
<i class="bi bi-unlock"></i> เข้าสู่ระบบ
</button>

<a href="index.php" class="btn btn-secondary w-100">
ยกเลิก
</a>
</form>

<div class="text-center mt-3">
ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
</div>
</div>

</body>
</html>
