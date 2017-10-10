<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>sample</title>
    <!-- Bootstrapの読み込み -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="register.css?20170904-1431">
</head>

<body>
<form action="register2.php" method="post">
    <div id="title">新規登録</div>
    名前 : <input type="text" name="name" size="30" value=""/><br/>
    ID : <input type="text" name="id" size="30" value=""/><br/>
    パスワード : <input type="text" name="pw" size="30" value=""/><br/>
    <!-- 直リンク禁止のための隠しフォーム -->
    <input hidden name="URI_forbid" value="123456">
    <input type="submit" value="登録"/>

    <?php
    $e = $_GET["e"];
    if ($e === "0") {
        echo "<div id='error'>idを入力してください。</div>";
    } elseif ($e === "1") {
        echo "<div id='error'>名前を入力してください。</div>";
    } elseif ($e === "2") {
        echo "<div id='error'>パスワードを入力してください。</div>";
    } elseif ($e === "3") {
        echo "<div id='error'>すでに使われているidです。</div>";
    } elseif ($e === "4") {
        echo "<div id='error'>idには半角英数字のみが使用できます。</div>";
    } elseif ($e === "5") {
        echo "<div id='error'>パスワードには半角英数字のみが使用できます。</div>";
    } elseif ($e === "6") {
        echo "<div id='error'>idが長すぎます。(半角12文字まで)</div>";
    } elseif ($e === "7") {
        echo "<div id='error'>名前が長すぎます。(半角10文字まで、または全角5文字まで)</div>";
    }
    ?>

</form>
</body>

</html>

