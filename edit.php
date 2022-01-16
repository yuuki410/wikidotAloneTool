<!DOCTYPE HTML>
<html>
<head>
<?php
include("config.php");
$user = $_GET["user"];
if(isset($_COOKIE["token"])){
    $token = $_COOKIE["token"];
}
$mode = "edit";
$type = "usercontent";
if($_GET["mode"]=="submit") $mode = "submit";
if($_GET["type"]) $type = $_GET["type"];
if($mode=="edit"){
    switch($type){
        case "usercontent":
            $title = "用户内容";
            if($stmt = mysqli_prepare($db, "SELECT `content` FROM `usercontent` WHERE `username`=?")){
                mysqli_stmt_bind_param($stmt, "s", $user);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $content);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                $content = addslashes($content);
            }
            break;
        case "bookmark":
        case "snippet":
            $title = $_GET["title"];
            if($stmt = mysqli_prepare($db, "SELECT * FROM ? WHERE `username`=? AND `title`=?")){
                $table=$type."s";
                mysqli_stmt_bind_param($stmt, "sss", $table, $user, $title);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $content);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                $priority = $content["priority"];
            }
            break;
    }
    switch($type){
        case "bookmark":
            $content = $content["url"];
            break;
        case "snippet":
            $content = $content["content"];
            break;
    }
} else {
    $title = $content = "";
    $priority = 255;
}
?>
<meta http-equiv="content-type" content="text/html; charset=uft-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
    <h1>编辑<?php echo($title) ?></h1>
    <hr />
    <form action="submit.php" method="post">
        <?php
        echo("<input type=\"hidden\" name=\"user\" value=\"");
        echo($user);
        echo("\">");
        echo("<input type=\"hidden\" name=\"mode\" value=\"");
        echo($mode);
        echo("\">");
        echo("<input type=\"hidden\" name=\"type\" value=\"");
        echo($type);
        echo("\">");
        echo("<input type=\"hidden\" name=\"orititle\" value=\"");
        echo($title);
        echo("\">");
        if($type!="usercontent"){
            echo("标题：");
            echo("<input type=\"text\" name=\"title\" value=\"");
            echo($title);
            echo("\">");
            echo("<br />");
        }
        echo("内容：");
        if($type=="bookmark"){
            echo("<input type=\"text\" name=\"content\" value=\"");
            echo($content);
            echo("\">");
        } else {
            echo("<textarea type=\"text\" name=\"content\">");
            echo($content);
            echo("</textarea>");
        }
        echo("<br />");
        if($type!="usercontent"){
            echo("优先级 (-128~127)：");
            echo("<input type=\"number\" value=\"");
            echo($priority);
            echo("\">");
            echo("<br />");
        }
        echo("口令：");
        echo("<input type=\"password\"");
        if($token){
            echo(" value=\"");
            echo($token);
            echo("\"");
        }
        echo(">");
        echo("<br />");
        if($token){
            echo("（已从Cookies中读取口令并自动填充，您无需再次输入）");
        }
        ?>
        <input type="radio" name="mode" <?php if($mode=="submit") echo "checked";?>  value="submit">新建
        <input type="radio" name="mode" <?php if($mode=="edit") echo "checked";?>  value="edit">编辑
        <input type="radio" name="mode" <?php if($mode=="delete") echo "checked";?>  value="submit">删除
        <input type="submit" value="保存">
    </form>
    <hr />
    <footer>
        <strong>特别提醒：</strong><br />
        当您编辑书签或代码片段时，请勿在标题中使用特殊字符，如半角引号，HTML标签，这会导致页面渲染出错。另外，标题不能重复。<br />
        优先级用于排序，按升序排列。
    </footer>
</body>
</html>