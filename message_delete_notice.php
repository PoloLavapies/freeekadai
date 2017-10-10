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

$receive = $_POST["receive"];
$send = $_POST["send"];

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'DELETE FROM talk WHERE receive = "' . $receive . '" AND send = "' . $send . '";';
$mysqli->query($sql);

header("Location: /mypage.php");
exit();
?>