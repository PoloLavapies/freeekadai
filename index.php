<!DOCTYPE HTML>
<html>

<?php
// セッションの削除
print_r($_SESSION);
unset($_SESSION['id']);
unset($_SESSION['talk_id']);
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ログインページ</title>
    <!-- Bootstrapの読み込み -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="index.css?20170904-1431">
</head>

<body>
<div id="all">
    <div id="title">ログイン</div>
    <form action="login.php" method="post">
        ID : <input type="text" name="id" size="30" value=""/><br/>
        パスワード : <input type="text" name="pw" size="30" value=""/><br/>
        <!-- 直リンク禁止のための隠しフォーム -->
        <input hidden name="URI_forbid" value="123456">
        <input type="submit" class="btn btn-default btn-sm" value="ログイン"/>
    </form>
    <?php
    $e = $_GET["e"];
    if ($e === "0") {
        echo "<div id='error'>idを入力してください。</div>";
    } elseif ($e === "1") {
        echo "<div id='error'>パスワードを入力してください。</div>";
    } elseif ($e === "2") {
        echo "<div id='error'>id、またはパスワードが違います。</div>";
    }
    ?>
    <a href="register.php">新規登録はこちら</a>

</body>
</html>
