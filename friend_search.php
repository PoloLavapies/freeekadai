<?php
// idがなかった場合はトップページへ飛ばす
session_start();
$id = $_SESSION['id'];
if ($id == "") {
    header("Location: /URI_forbid.php");
    exit();
}

// 不正な操作の処理
$URI_forbid = $_POST["URI_forbid"];
if ($URI_forbid !== "123456") {
    header("Location: /URI_forbid.php");
    exit();
}

// 検索
$friend_id = $_POST["friend_add_id"];
$mysqli = new mysqli('localhost', 'root', 'root', 'db');
$sql = 'SELECT name FROM users WHERE id = "' . $friend_id . '";';
$name = $mysqli->query($sql);
$name = $name->fetch_all();
$name = $name[0][0];

// 探したユーザーが存在しないまたは自分の場合、それ以外の場合で場合分け
if ($name == "" || $friend_id == $id) {
    $_SESSION['add_state'] = 'error';
    header("Location: /mypage.php");
    exit();
} else {
    $_SESSION['add_id'] = $friend_id;
    $_SESSION['add_name'] = $name;
    // すでに友達かどうか
    $mysqli = new mysqli('localhost', 'root', 'root', 'db');
    $sql = 'SELECT * FROM friends WHERE user1 = "' . $id . '" AND user2 = "' . $friend_id . '";';
    $already = $mysqli->query($sql);
    $already = $already->fetch_all();
    $already = $already[0][0];
    if ($already == "") {
        $_SESSION['add_state'] = 'success';
    } else {
        $_SESSION['add_state'] = 'already';
    }
    header("Location: /mypage.php");
    exit();
}
?>
