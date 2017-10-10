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

$talk_id = $_POST["talk_delete_id"];

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'DELETE FROM talk WHERE id = "' . $talk_id . '";';
$mysqli->query($sql);

header("Location: /mypage.php");
exit();
?>
