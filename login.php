<!doctype html>

<html lang="cn">
<head>
    <meta charset="UTF-8">
    <title>注册中···</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php

$config = include 'config.php';

//初始化数据库信息
$mysqlHost = $config['mysqlHost'];
$mysqlUsername = $config['mysqlUsername'];
$mysqlPassword = $config['mysqlPassword'];
$mysqlDbname = $config['mysqlDbname'];

// 创建与 MySQL 数据库的连接
$conn = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDbname);

 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    echo "404 DataNotFound!";
} else{
    //下面实现已有账号的登录功能
    //输入家属身份证号和密码以验证身份
    $jiashuID = $_POST['jiashuID'];
    $password = $_POST['password'];
    // 执行 SQL 查询语句，查询与用户输入的身份证号和密码所匹配的数据
    $result = $conn->query("SELECT `登录密码` FROM `亲属关系` WHERE `家属身份证号`='$jiashuID'");
    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
    ?>
    <script>
        Swal.fire({
            title: '登录失败！',
            text: "该账号不存在！",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '点击重试'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href="Test.html?p=Login";
            }
        })
    </script>
    <?php
    } else{
        $row = $result->fetch_assoc();
        $mima = $row["登录密码"];
        //验证数据库中录入的信息
        if ($password == $mima) {
            ?>
            <script>
                let timerInterval
                Swal.fire({
                    title: '登录成功!',
                    html: '即将跳转至用户界面： <b></b> milliseconds.',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer');
                    }
                    window.location.href="show.html";
                })
            </script>
            <?php
        }else{
            ?>
            <script>
                Swal.fire({
                    title: '登录失败！',
                    text: "用户名或密码错误！",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '点击重试'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href="Test.html?p=Login";
                    }
                })
            </script>
            <?php
        }
    }
}
?>
</body>