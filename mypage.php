<?php
// idがなかった場合はトップページへ飛ばす
session_start();
$id = $_SESSION['id'];
if ($id == "") {
    header("Location: /URI_forbid.php");
    exit();
}

// フレンド検索でmypageに来た場合、セッションの値を取り出し、それらを削除
$add_state = $_SESSION['add_state'];
$add_id = $_SESSION['add_id'];
$add_name = $_SESSION['add_name'];
$_SESSION['add_state'] = '';
$_SESSION['add_id'] = '';
$_SESSION['add_name'] = '';

// talk_idを受け取る
$talk_id = $_SESSION['talk_id'];

// 既読の処理
if (!($talk_id == "")) {
    $mysqli = new mysqli('localhost', 'root', 'root', 'db');
    $sql = 'UPDATE talk SET kidoku = 1 WHERE send = "' . $talk_id . '" AND  receive = "' . $id . '";';
    $mysqli->query($sql);
}
?>

<!-- 
スクロールバーをbox内にだけ表示させたい
参考:http://javascript123.seesaa.net/article/103060039.html
 -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>マイページ</title>
    <!-- JQueryの読み込み -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Bootstrapの読み込み -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="mypage.css?21191806-0931">
</head>

<body>

<div id="box1">
    <div id="icon">
        <?php
        // アイコンの表示
        $mysqli = new mysqli('localhost', 'root', 'root', 'db');
        $sql = 'SELECT icon FROM icons WHERE id = "' . $id . '";';
        $icon = $mysqli->query($sql);
        $icon = $icon->fetch_all();
        $icon = $icon[0][0];
        if (!($icon == "")) {
            $sql = 'SELECT ext FROM icons WHERE id = "' . $id . '";';
            $ext = $mysqli->query($sql);
            $ext = $ext->fetch_all();
            $ext = $ext[0][0];
            $file = 'img/' . $icon . '.' . $ext;
            echo '<img id="icon" src="' . $file . '">';
        } else {
            echo '<img id="icon" src="img/no_img.jpg">';
        }
        // 名前とIDとログアウトボタンの表示
        $mysqli = new mysqli('localhost', 'root', 'root', 'db');
        $sql = 'SELECT name FROM users WHERE id = "' . $id . '";';
        $name = $mysqli->query($sql);
        $name = $name->fetch_all();
        $name = $name[0][0];
        echo '<div id="nameidlogout">';
        echo '<div id="name">名前 : ' . $name . '</div>';
        echo '<div id="id">ID : ' . $id . '</div>';
        echo '<form action="index.php">';
        echo '<input type=submit value="ログアウト">';
        echo '</form>';
        echo '</div>';
        ?>

        <div id="filechangebtns">
            <form action="img.php" method="post" enctype="multipart/form-data">
                <input id="choose_file" type="file" name="img">
                <input type="submit" value="アイコンを変更">
            </form>
        </div>
    </div>

    <div class="line"></div>

    <!-- 友だち追加 -->
    <div id="search_friend">
        <h4>友だちを探す</h4>
        <form action="friend_search.php" method="post" enctype="multipart/form-data">
            idを入力
            <input type="text" name="friend_add_id">
            <!-- 直リンク禁止のための隠しフォーム -->
            <input hidden name="URI_forbid" value="123456">
            <input type="submit" value="検索">
        </form>
    </div>

    <?php
    // 検索結果
    if ($add_state == 'error') {
        echo '<div id="add_error">お探しのユーザーは見つかりませんでした。</div>';
    } elseif ($add_state == 'success' || $add_state == 'already') {
        // アイコンの表示
        $mysqli = new mysqli('localhost', 'root', 'root', 'db');
        $sql = 'SELECT icon FROM icons WHERE id = "' . $add_id . '";';
        $icon = $mysqli->query($sql);
        $icon = $icon->fetch_all();
        $icon = $icon[0][0];
        if (!($icon == "")) {
            $sql = 'SELECT ext FROM icons WHERE id = "' . $add_id . '";';
            $ext = $mysqli->query($sql);
            $ext = $ext->fetch_all();
            $ext = $ext[0][0];
            $file = 'img/' . $icon . '.' . $ext;
            echo '<img id="add_icon" src="' . $file . '">';
        } else {
            echo '<img id="add_icon" src="img/no_img.jpg">';
        }
        echo '<div id="add_idname">';
        echo 'id : ' . $add_id . '<br>';
        echo '名前 : ' . $add_name . '<br>';
        echo '</div>';
        // ユーザー追加ボタン
        if ($add_state == 'success') {
            echo '<form action="friend_add.php" method="post">';
            echo '<input hidden name="user1" value="' . $id . '">';
            echo '<input hidden name="user2" value="' . $add_id . '">';
            echo '<input hidden name="URI_forbid" value="123456">';
            echo '<input type="submit" value="友だち追加">';
            echo '</form>';
        } else {
            // トークの開始
            echo '<div class="talk_btn">';
            echo '<form action="talk_start.php" method="post">';
            echo '<input hidden name="talk_id" value="' . $add_id . '">';
            echo '<input id="add_talk" type="submit" value="トーク">';
            echo '</form>';
            echo '</div>';
        }
    }
    ?>
</div>

<div id="box2">
    <h3 id="midoku_index">新着メッセージ</h3>
    <?php
    $mysqli = new mysqli('localhost', 'root', 'root', 'db');
    $sql = 'SELECT send FROM talk WHERE receive = "' . $id . '" and kidoku = "0";';
    $midoku_friends = $mysqli->query($sql);
    $midoku_friends = $midoku_friends->fetch_all();
    // すでに未読エリアに表示した友だちのidのリスト
    $midoku_list = array();
    if (!($midoku_friends == array())) {
        echo '<div id="midoku_area">';
        echo '<div class="notice">新着メッセージがあります。</div>';
        foreach ($midoku_friends as $friend) {
            $friend_id = $friend[0];
            // すでに未読エリアに表示していない場合
            if (!(in_array($friend_id, $midoku_list))) {
                $midoku_list[] = $friend_id;
                $mysqli = new mysqli('localhost', 'root', 'root', 'db');
                $sql = 'SELECT name FROM users WHERE id = "' . $friend_id . '";';
                $friend_name = $mysqli->query($sql);
                $friend_name = $friend_name->fetch_all();
                $friend_name = $friend_name[0][0];
                // アイコン
                $mysqli = new mysqli('localhost', 'root', 'root', 'db');
                $sql = 'SELECT icon FROM icons WHERE id = "' . $friend_id . '";';
                $icon = $mysqli->query($sql);
                $icon = $icon->fetch_all();
                $icon = $icon[0][0];
                if (!($icon == "")) {
                    $sql = 'SELECT ext FROM icons WHERE id = "' . $friend_id . '";';
                    $ext = $mysqli->query($sql);
                    $ext = $ext->fetch_all();
                    $ext = $ext[0][0];
                    $friend_icon = 'img/' . $icon . '.' . $ext;
                } else {
                    $friend_icon = 'img/no_img.jpg';
                }
                echo '<div class="friend">';
                echo '<img class="friend_icon" src="' . $friend_icon . '">';
                echo '<div class="friend_id">ID : <br>' . $friend_id . '</div>';
                echo '<div class="friend_name">名前 : <br>' . $friend_name . '</div>';

                // 友達でなければ友達追加ボタン、または削除ボタン
                $mysqli = new mysqli('localhost', 'root', 'root', 'db');
                $sql = 'SELECT user1 FROM friends WHERE user1 = "' . $id . '" AND user2 = "' . $friend_id . '";';
                $if_friend = $mysqli->query($sql);
                $if_friend = $if_friend->fetch_all();

                if ($if_friend == array()) {
                    // 友だち追加のボタン
                    echo '<div class="add_btn_notice">';
                    echo '<form action="friend_add.php" method="post">';
                    echo '<input hidden name="user1" value="' . $id . '">';
                    echo '<input hidden name="user2" value="' . $friend_id . '">';
                    echo '<input hidden name="URI_forbid" value="123456">';
                    echo '<input type="submit" value="友だち追加">';
                    echo '</form>';
                    echo '</div>';
                    // メッセージ削除のボタン
                    echo '<div class="delete_btn_notice">';
                    echo '<form action="message_delete_notice.php" method="post">';
                    echo '<input hidden name="receive" value="' . $id . '">';
                    echo '<input hidden name="send" value="' . $friend_id . '">';
                    echo '<input hidden name="URI_forbid" value="123456">';
                    echo '<input type="submit" value="削除">';
                    echo '</form>';
                    echo '</div>';
                } else {
                    // トークの開始
                    echo '<div class="talk_btn">';
                    echo '<form action="talk_start.php" method="post">';
                    echo '<input hidden name="talk_id" value="' . $friend_id . '">';
                    echo '<input type="submit" value="トーク">';
                    echo '</form>';
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        echo '</div>';
    } else {
        echo '<div class="notice">新着メッセージはありません。</div>';
    }
    ?>
    <div class="line"></div>

    <h3 id="list_index">友だちリスト</h3>
    <?php
    $mysqli = new mysqli('localhost', 'root', 'root', 'db');
    $sql = 'SELECT user2 FROM friends WHERE user1 = "' . $id . '";';
    $friends = $mysqli->query($sql);
    $friends = $friends->fetch_all();
    $count = count($friends);
    if ($count == 0) {
        echo '<div class="notice">友だちは現在いません。左下の友だち追加から追加しましょう。</div>';
    } else {
        echo '<div id="friend_area">';
        for ($i = 0; $i < $count; $i++) {
            $friend_id = $friends[$i][0];
            $mysqli = new mysqli('localhost', 'root', 'root', 'db');
            $sql = 'SELECT name FROM users WHERE id = "' . $friend_id . '";';
            $friend_name = $mysqli->query($sql);
            $friend_name = $friend_name->fetch_all();
            $friend_name = $friend_name[0][0];
            // アイコン
            $mysqli = new mysqli('localhost', 'root', 'root', 'db');
            $sql = 'SELECT icon FROM icons WHERE id = "' . $friend_id . '";';
            $icon = $mysqli->query($sql);
            $icon = $icon->fetch_all();
            $icon = $icon[0][0];
            if (!($icon == "")) {
                $sql = 'SELECT ext FROM icons WHERE id = "' . $friend_id . '";';
                $ext = $mysqli->query($sql);
                $ext = $ext->fetch_all();
                $ext = $ext[0][0];
                $friend_icon = 'img/' . $icon . '.' . $ext;
            } else {
                $friend_icon = 'img/no_img.jpg';
            }
            echo '<div class="friend">';
            echo '<img class="friend_icon" src="' . $friend_icon . '">';
            echo '<div class="friend_id">ID : <br>' . $friend_id . '</div>';
            echo '<div class="friend_name">名前 : <br>' . $friend_name . '</div>';
            // トークの開始
            echo '<div class="talk_btn">';
            echo '<form action="talk_start.php" method="post">';
            echo '<input hidden name="talk_id" value="' . $friend_id . '">';
            echo '<input type="submit" value="トーク">';
            echo '</form>';
            echo '</div>';
            // 友だち削除
            echo '<div class="friend_delete">';
            echo '<form action="friend_delete.php" method="post">';
            echo '<input hidden name="friend_id" value="' . $friend_id . '">';
            echo '<input hidden name="URI_forbid" value="123456">';
            echo '<input type="submit" value="友だちから削除">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    ?>
</div>

<div id="box3">
    <?php
    // トーク中かどうかを調べる
    if ($talk_id == "") {
        echo "<div class=\"notice\">友だちを選択してトークを始めましょう。</div>";
    } else {
        // トーク中の相手を表示
        echo '<div>';
        // アイコンの取得
        $mysqli = new mysqli('localhost', 'root', 'root', 'db');
        $sql = 'SELECT icon FROM icons WHERE id = "' . $talk_id . '";';
        $icon = $mysqli->query($sql);
        $icon = $icon->fetch_all();
        $icon = $icon[0][0];
        if (!($icon == "")) {
            $sql = 'SELECT ext FROM icons WHERE id = "' . $talk_id . '";';
            $ext = $mysqli->query($sql);
            $ext = $ext->fetch_all();
            $ext = $ext[0][0];
            $friend_icon = 'img/' . $icon . '.' . $ext;
        } else {
            $friend_icon = 'img/no_img.jpg';
        }
        // 名前
        $mysqli = new mysqli('localhost', 'root', 'root', 'db');
        $sql = 'SELECT name FROM users WHERE id = "' . $talk_id . '";';
        $talk_name = $mysqli->query($sql);
        $talk_name = $talk_name->fetch_all();
        $talk_name = $talk_name[0][0];
        echo '<img class="friend_icon" src="' . $friend_icon . '">';
        echo $talk_name . ' (ID : ' . $talk_id . ') さんとトーク中';
        echo '</div>';
        echo '<div class="line"></div>';
        // 過去のトーク履歴の取得
        $mysqli = new mysqli('localhost', 'root', 'root', 'db');
        $sql = 'SELECT * FROM talk WHERE send = "' . $id . '" AND receive = "' . $talk_id . '";';
        $talk1 = $mysqli->query($sql);
        $talk1 = $talk1->fetch_all();
        $sql = 'SELECT * FROM talk WHERE send = "' . $talk_id . '" AND receive = "' . $id . '";';
        $talk2 = $mysqli->query($sql);
        $talk2 = $talk2->fetch_all();
        $talks = array_merge($talk1, $talk2);
        // $talksをid順に並び替える
        $keys = array();
        foreach ($talks as $talk) {
            $keys[] = $talk[3];
        }
        array_multisort($keys, SORT_ASC, $talks);
        // トーク履歴を書き出す(全て書き出してみる)
        echo '<div id="message_area">';
        foreach ($talks as $talk) {
            $send = $talk[0];
            // アイコン取得
            $mysqli = new mysqli('localhost', 'root', 'root', 'db');
            $sql = 'SELECT icon FROM icons WHERE id = "' . $send . '";';
            $icon = $mysqli->query($sql);
            $icon = $icon->fetch_all();
            $icon = $icon[0][0];
            if (!($icon == "")) {
                $sql = 'SELECT ext FROM icons WHERE id = "' . $send . '";';
                $ext = $mysqli->query($sql);
                $ext = $ext->fetch_all();
                $ext = $ext[0][0];
                $message_icon = 'img/' . $icon . '.' . $ext;
            } else {
                $message_icon = 'img/no_img.jpg';
            }
            // 時刻
            $time = $talk[4];
            date_default_timezone_set('Asia/Tokyo');
            $time = date("m月d日 H時i分", $time);
            // メッセージ
            $message = $talk[2];
            if ($send === $id) {
                $send_name = $name;
            } else {
                $send_name = $talk_id;
            }
            $message = $send_name . ' (' . $time . ') <br>' . $message;
            echo '<div class="iconmessage">';
            echo '<img class="friend_icon_mini" src="' . $message_icon . '">';
            echo '<div class="message">' . $message . '</div>';
            // メッセージ削除ボタン
            if ($send === $id) {
                echo '<div class="message_delete_btn">';
                $talk_delete_id = $talk[3];
                echo '<form action="message_delete.php" method="post">';
                echo '<input hidden name="talk_delete_id" value="' . $talk_delete_id . '">';
                echo '<input hidden name="URI_forbid" value="123456">';
                echo '<input type="submit" value="削除">';
                echo '</form>';
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        // メッセージ送信フォーム
        echo '<form action="message_add.php" method="post">';
        echo '<input hidden name="talk_id" value="' . $talk_id . '">';
        echo '<textarea id="new_message_area" name="message"></textarea>';
        echo '<input hidden name="URI_forbid" value="123456">';
        echo '<input id="message_send" type="submit" value="送信">';
        echo '</form>';
    }
    ?>

    <!-- メッセージエリアで一番下を表示させる設定 -->
    <script>
        area = document.getElementById("message_area")
        var height = area.scrollHeight;
        area.scrollTop = height;
    </script>

</div>

</body>
</html>
