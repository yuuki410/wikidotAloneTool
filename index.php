<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Wikidot Alone Tool</title>
    <style>
        .posted{
            background-color: gray;
        }
    </style>
</head>
<body>
    <h1>Wikidot Alone 小工具</h1>
    <p>要在你的沙盒页面上使用它，插入以下代码：</p>
    <code>[[include :yuuki410:component:wikidot-alone user=用户名]]</code>
    <p>随后使用下面的表单初始化你的小工具：</p>
    <p>
        <form action="index.php" method="post"><br />
        用户名：<input type="text" name="user"><br />
        口令：<input type="password" name="token"><br />
        （进行操作时需要提供口令，需要注意的是，<strong>口令在后台以明文形式存储，故请勿使用您的常用密码作为口令</strong>）<br />
        <input type="submit" value="提交">
        </form>
    </p>
    <p class="posted">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo("执行结果：<br />");
                if(empty($_POST["user"] || empty($_POST["token"]))){
                    echo("您没有填写用户名或口令");
                } else {
                    include("config.php");
                    if($stmt = mysqli_prepare($db ,"SELECT `username` FROM `users` WHERE `username`=?")){
                        mysqli_stmt_bind_param($stmt, "s", $_POST["user"]);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $result);
                        mysqli_stmt_fetch($stmt);
                        mysqli_stmt_close($stmt);
                    }
                    if(empty($result)){
                        if($stmt = mysqli_prepare($db, "INSERT INTO users (username, token) VALUES (?, ?)")){
                            mysqli_stmt_bind_param($stmt, "ss", $_POST["user"], $_POST["token"]);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                            echo("初始化成功！");
                        }
                    } else {
                        echo("用户名已存在！");
                    }
                }
            }
        ?>
    </p>
    <hr />
    <p>初始化成功后，请打开你的沙盒页以编辑小工具的显示选项。</p>
    <p>也请参阅：<a href="http://yuuki410.wikidot.com/component:wikidot-alone">http://yuuki410.wikidot.com/component:wikidot-alone</a></p>
    <hr />
    <footer>
        本工具使用Cookies以避免重复输入口令。<br />
        本工具源代码以AGPL v3.0许可，有关许可证信息，请参阅<a href="https://github.com/yuuki410/wikidotAloneTool/blob/master/LICENSE">此处</a>
    </footer>
</body>
</html>