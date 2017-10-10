<?php
$id = $_POST["id"];
$pw = $_POST["pw"];

$URI_forbid = $_POST["URI_forbid"];
if ($URI_forbid !== "123456") {
    header("Location: /URI_forbid.php");
    exit();
}

if ($id === "") {
    header("Location: /index.php?e=0");
    exit();
}

if ($pw === "") {
    header("Location: /index.php?e=1");
    exit();
}

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'SELECT pw FROM users WHERE id = "' . $id . '";';
$pw_db = $mysqli->query($sql);
$pw_db = $pw_db->fetch_all();
$pw_db = $pw_db[0][0];

if ($pw == $pw_db) {
    // セッションの開始
    session_start();
    $_SESSION['id'] = $id;
    // index.phpで削除したはずのtalk_idが保持されている場合があるので削除
    $_SESSION['talk_id'] = '';
    // マイページへ遷移
    $link = "Location: /mypage.php";
    header($link);
} else {
    header("Location: /index.php?e=2");
    exit();
}
?>


