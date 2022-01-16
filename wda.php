<!DOCTYPE HTML>
<html>
<head>
<?php
include("config.php");
$user = $_GET["user"];
$category = $_GET["category"];
if($stmt = mysqli_prepare($db, "SELECT `content` FROM `usercontent` WHERE `username`=?")){
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $usercontent);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

function get_from($db, $table){
    if($stmt = mysqli_prepare($db, "SELECT * FROM ? WHERE `username`=? ORDER BY `priority`")){
        mysqli_stmt_bind_param($stmt, "ss", $table, $user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $district);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $district;
    }
}

$bookmark = get_from($db, "bookmarks");
$snippet = get_from($db, "snippet");
?>
<meta http-equiv="content-type" content="text/html; charset=uft-8">
<style type="text/css">
<?php
if($category=="wanderers" || $category=="wanderers-adult"){
    echo("@import url(https://cdn.jsdelivr.net/gh/SCP-CN-Tech/Interwiki@cn/style-wl.css);");
} else {
    echo("@import url(https://cdn.jsdelivr.net/gh/SCP-CN-Tech/Interwiki@cn/style.css);");
}
?>
</style>
<style type="text/css">
    li.bookmark, li.snippet{
        list-style: none;
    }
    textarea.snippet{
        height: 1em;
        width: 15%;
        font-size: smaller;
        overflow: hidden;
    }
    button.copy-button{
        height: 1em;
        width: 70%;
        font-size: small;
        overflow: hidden;
    }
</style>
<meta name="viewport" content="width=device-width,initial-scale=1">
<script>
    function execCopy(id) {
        document.querySelector('#'+id).select();
        if(document.execCommand('copy')) {} else {
            alert('复制失败！')
        }
    }
</script>
<script src="https://kit.fontawesome.com/cea948d15e.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="side-block" style="display:none">
    <div class="heading" id="htmlcontent">
        <p><a>Wikidot Alone Tool</a></p>
    </div>
    <div class="menu-item">
        <?php
            echo($usercontent);
            echo("<a href=\"" + $site + "edit.php?user=" + $user + "&type=usercontent&mode=");
            if(empty($usercontent)){
                echo("submit");
            } else {
                echo("edit");
            }
            echo("\" target=\"_blank\">(编辑)</a>");
        ?>
    </div>
    <div class="heading" id="bookmark">
        <p>书签</p> <?php echo("<a href=\"" + $site + "edit.php?user=" + $user + "&type=bookmark&mode=submit\" target=\"_blank\">(新书签)</a>"); ?>
    </div>
    <div class="menu-item">
        <ul>
        <?php
            foreach($bookmark as $item){
                echo("<li class=\"bookmark\">");
                echo("<a href=\"" + $site + "edit.php?user=" + $user + "&type=bookmark&title=" + urlencode($item["title"]) + "\" target=\"_blank\"><i class=\"fas fa-edit\">E</i></a> ");
                echo("<a href=");
                echo($item["url"]);
                echo(" target=\"_blank\">");
                echo($item["title"]);
                echo("</a>");
                echo("</li>");
            }
        ?>
        </ul>
    </div>
    <div class="heading" id="snippet">
        <p>代码片段</p> <?php echo("<a href=\"" + $site + "edit.php?user=" + $user + "&type=snippet&mode=submit\" target=\"_blank\">(新片段)</a>"); ?>
    </div>
    <div class="menu-item">
        <ul>
        <?php
            $count = 0;
            foreach($snippet as $item){
                echo("<li class=\"snippet\">");
                echo("<a href=\"" + $site + "edit.php?user=" + $user + "&type=snippet&title=" + urlencode($item["title"]) + "\" target=\"_blank\"><i class=\"fas fa-edit\">E</i></a> ");

                echo("<textarea class=\"snippet\" id=\"snippet-");
                echo($count);
                echo("\">");
                echo(addslashes($item["content"]));
                echo("</textarea>");

                echo("<button class=\"copy-button\" onclick=\"execCopy('snippet-");
                echo($count);
                echo("')\">");
                echo($item["title"]);
                echo("</button>");
                echo("</li>");
            }
        ?>
        </ul>
    </div>
    <div class="license" style="font-size:small;">Powered by <a href="https://wda.yuuki.eu.org/">Wikidot Alone Tool</a>.</div>
</div>
</body>
</html>