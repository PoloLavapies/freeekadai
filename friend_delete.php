<?php
session_start();
$id = $_SESSION['id'];
if ($id == "") {
    header("Location: /URI_forbid.php");
    exit();
}

$URI_forbid = $_POST["URI_forbid"];

if ($URI_forbid !== "123456") {
    header("Location: /URI_forbid.php");
    exit();
}

$friend_id = $_POST["friend_id"];

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'DELETE FROM friends WHERE user1 = "' . $id . '" AND user2 = "' . $friend_id . '";';
$mysqli->query($sql);

header("Location: /mypage.php");
exit();
?>
