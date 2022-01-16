<?php
$site = "https://example.com/"; // 您的网站根目录URL，需要以斜杠结尾，若键入"./"则会使用相对路径
$db = mysqli_connect("localhost", "wda", "password", "wda"); // 请在建立MySQL账户后修改此行，然后将本文件重命名为config.php
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($db, "CREATE TABLE IF NOT EXISTS users (username TINYTEXT NOT NULL, token TINYTEXT)");
mysqli_query($db, "CREATE TABLE IF NOT EXISTS usercontent (username TINYTEXT NOT NULL, content TEXT)");
mysqli_query($db, "CREATE TABLE IF NOT EXISTS bookmarks (username TINYTEXT NOT NULL, `url` TINYTEXT, title TINYTEXT, priority TINYINT)");
mysqli_query($db, "CREATE TABLE IF NOT EXISTS snippets (username TINYTEXT NOT NULL, content TEXT, title TINYTEXT, priority TINYINT)");
?>