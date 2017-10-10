<?php
session_start();
$send = $_SESSION['id'];
if ($send == "") {
    header("Location: /URI_forbid.php");
    exit();
}

$URI_forbid = $_POST["URI_forbid"];
if ($URI_forbid !== "123456") {
    header("Location: /URI_forbid.php");
    exit();
}

$receive = $_POST["talk_id"];
$message = $_POST["message"];

$timestamp = time();

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'INSERT INTO talk (send, receive, message, time) VALUES ("' . $send . '", "' . $receive . '", "' . $message . '" ,"' . $timestamp . '");';
if (!($message == '')) {
    $mysqli->query($sql);
}

$_SESSION['talk_id'] = $receive;
header("Location: /mypage.php");
exit();
?>
