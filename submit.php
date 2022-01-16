<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=uft-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<?php
include("config.php");
$user = $_POST["user"];
$token = $_POST["token"];
$mode = $_POST["mode"];
$type = $_POST["type"];
if(empty($_POST["orititle"])){} else {
    $orititle = $_POST["orititle"];
}
if(empty($_POST["title"])){} else {
    $title = $_POST["title"];
}
if(empty($_POST["content"])){} else {
    $content = $_POST["content"];
}
if(empty($_POST["priority"])){} else {
    $priority = $_POST["priority"];
}
if($mode=="submit"){
    switch($type){
        case "usercontent":
            if($stmt = mysqli_prepare($db, "INSERT INTO usercontent(username, content) VALUES (?, ?)")){
                mysqli_stmt_bind_param($stmt, "ss", $user, $content);
            }
            break;
        case "bookmark":
            $stmt = mysqli_prepare($db, "INSERT INTO bookmark(username, `url`, title, priority) VALUES (?, ?, ?, ?)");
            break;
        case "snippet":
            $stmt = mysqli_prepare($db, "INSERT INTO bookmark(username, content, title, priority) VALUES (?, ?, ?, ?)");
            break;
    }
    switch($type){
        case "bookmark":
        case "snippet":
            mysqli_stmt_bind_param($stmt, "sssi", $user, $content, $title, $priority);
            break;
    }
} else if($mode=="edit"){
    switch($type){
        case "usercontent":
            if($stmt = mysqli_prepare($db, "UPDATE usercontent SET content=? WHERE username=?)")){
                mysqli_stmt_bind_param($stmt, "ss", $content, $user);
            }
            break;
        case "bookmark":
            $stmt = mysqli_prepare($db, "UPDATE bookmark SET `url`=?, title=?, priority=? WHERE username=? AND title=?");
            break;
        case "snippet":
            $stmt = mysqli_prepare($db, "UPDATE bookmark SET content=?, title=?, priority=? WHERE username=? AND title=?");
            break;
    }
    switch($type){
        case "bookmark":
        case "snippet":
            mysqli_stmt_bind_param($stmt, "ssiss", $content, $title, $priority, $user, $orititle);
            break;
    }
} else if($mode=="delete"){
    switch($type){
        case "usercontent":
            if($stmt = mysqli_prepare($db, "DELETE FROM usercontent WHERE username=?)")){
                mysqli_stmt_bind_param($stmt, "s", $user);
            }
            break;
        case "bookmark":
        case "snippet":
            $stmt = mysqli_prepare($db, "DELETE FROM bookmark WHERE username=? AND title=?");
            mysqli_stmt_bind_param($stmt ,"ss", $user, $orititle);
            break;
    }
}
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<h1>操作成功！</h1>
</body>
</html>