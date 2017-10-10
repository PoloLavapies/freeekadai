<?php
$id = $_POST["id"];
$name = $_POST["name"];
$pw = $_POST["pw"];

$URI_forbid = $_POST["URI_forbid"];
if ($URI_forbid !== "123456") {
    header("Location: /URI_forbid.php");
    exit();
}

// 空欄の場合はregister.phpに戻す
if ($id === "") {
    header("Location: /register.php?e=0");
    exit();
}

if ($name === "") {
    header("Location: /register.php?e=1");
    exit();
}

if ($pw === "") {
    header("Location: /register.php?e=2");
    exit();
}

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'SELECT id FROM users WHERE id = "' . $id . '";';
$ifid = $mysqli->query($sql);
$ifid = $ifid->fetch_all();

// $idがtrue(=すでにidが登録されている)ときはregister.phpに戻す
if (!($ifid == array())) {
    header("Location: /register.php?e=3");
    exit();
}

// id,pwに英数字以外を含む時
if (!(preg_match("/^[a-zA-Z0-9]+$/", $id))) {
    header("Location: /register.php?e=4");
    exit();
}

if (!(preg_match("/^[a-zA-Z0-9]+$/", $pw))) {
    header("Location: /register.php?e=5");
    exit();
}

// id,nameの文字数チェック(12文字以内)
if (strlen($id) > 13) {
    header("Location: /register.php?e=6");
    exit();
}

if (strlen($name) > 16) {
    header("Location: /register.php?e=7");
    exit();
}

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'INSERT INTO users (id, name, pw) VALUES ("' . $id . '", "' . $name . '", "' . $pw . '");';
$mysqli->query($sql);
$link = "Location: /register_complete.php?id=" . $id;
header($link);
exit();
?>
