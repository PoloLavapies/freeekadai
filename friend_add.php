<?php
$URI_forbid = $_POST["URI_forbid"];

if ($URI_forbid !== "123456") {
    header("Location: /URI_forbid.php");
    exit();
}

$user1 = $_POST["user1"];
$user2 = $_POST["user2"];

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'INSERT INTO friends (user1, user2) VALUES ("' . $user1 . '", "' . $user2 . '");';
$mysqli->query($sql);

header("Location: /mypage.php");
exit();
?>
