<?php
$talk_id = $_POST['talk_id'];

session_start();
$_SESSION['talk_id'] = $talk_id;
header("Location: /mypage.php");
exit();
?>
