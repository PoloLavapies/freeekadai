<?php
session_start();
$id = $_SESSION['id'];

// 一時ファイル名
$tempfile = $_FILES['img'] ['tmp_name'];
// ファイルがなければ遷移
if (!(is_uploaded_file($tempfile))) {
    $link = "Location: /mypage.php";
    header($link);
    exit();
}

// ファイル名
$filename = $_FILES['img']['name'];
// 拡張子
$ext = substr($filename, strrpos($filename, '.') + 1);

$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'SELECT icon FROM icons WHERE id = "' . $id . '";';
$icon = $mysqli->query($sql);
$icon = $icon->fetch_all();
$icon = $icon[0][0];
// アイコンが登録されている場合とそうでない場合で場合分け
if ($icon == "") {
    $sql = 'INSERT INTO icons (id, ext) VALUES ("' . $id . '", "' . $ext . '");';
    $mysqli->query($sql);
    $sql = 'SELECT icon FROM icons WHERE id = "' . $id . '";';
    // DBに登録したアイコンのidを削除
    $icon_id = $mysqli->query($sql);
    $icon_id = $icon_id->fetch_all();
    $icon_id = $icon_id[0][0];
} else {
    // 現在のアイコンを削除
    $sql = 'SELECT icon FROM icons WHERE id = "' . $id . '";';
    $icon_now = $mysqli->query($sql);
    $icon_now = $icon_now->fetch_all();
    $icon_now = $icon_now[0][0];
    $sql = 'SELECT ext FROM icons WHERE id = "' . $id . '";';
    $ext_now = $mysqli->query($sql);
    $ext_now = $ext_now->fetch_all();
    $ext_now = $ext_now[0][0];
    $file_now = 'img/' . $icon_now . '.' . $ext_now;
    unlink($file_now);
    // 現在のアイコンに関する情報をDBから削除
    $sql = 'DELETE FROM icons WHERE id = "' . $id . '";';
    $mysqli->query($sql);
    // DBの情報を新たなアイコンのものに置き換える
    $sql = 'INSERT INTO icons (id, ext) VALUES ("' . $id . '", "' . $ext . '");';
    $mysqli->query($sql);
    $sql = 'SELECT icon FROM icons WHERE id = "' . $id . '";';
    $icon_id = $mysqli->query($sql);
    $icon_id = $icon_id->fetch_all();
    $icon_id = $icon_id[0][0];
}

// 画像の移動を行う
// ファイル名については。mysqlを参照する必要がある。
$new_name = "img/" . $icon_id . "." . $ext;
move_uploaded_file($tempfile, $new_name);

// マイページへ遷移
$link = "Location: /mypage.php";
header($link);
exit();
?>
