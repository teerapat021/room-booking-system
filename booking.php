<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='member'){
    header("Location:login.php");
    exit();
}

$room_id = $_GET['id'];

if(isset($_POST['book'])){
    $stmt=$conn->prepare(
        "INSERT INTO bookings(user_id,room_id,booking_date,start_time,end_time,booking_status)
         VALUES(:u,:r,:d,:s,:e,'จองแล้ว')"
    );
    $stmt->execute([
        ':u'=>$_SESSION['user_id'],
        ':r'=>$room_id,
        ':d'=>$_POST['date'],
        ':s'=>$_POST['start'],
        ':e'=>$_POST['end']
    ]);

    $conn->prepare("UPDATE rooms SET status='ไม่ว่าง' WHERE room_id=?")
         ->execute([$room_id]);

    header("Location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>จองห้อง</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container col-md-4 mt-5">
<h4>จองห้อง</h4>
<form method="post">
<input type="date" name="date" class="form-control mb-2" required>
<input type="time" name="start" class="form-control mb-2" required>
<input type="time" name="end" class="form-control mb-2" required>
<button name="book" class="btn btn-success w-100">ยืนยันการจอง</button>
</form>
</div>
</body>
</html>
