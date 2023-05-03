<!doctype html>

<html lang="cn">
<head>
    <meta charset="UTF-8">
    <title>登陆中···</title>
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
    // Insert data into database
    $jiashuname = $_POST['jiashuname'];
    $jiashuID = $_POST['jiashuID'];
    $jiashuxingbie = $_POST['jiashuxingbie'];
    $jiashudianhua = $_POST['jiashudianhua'];
    $xueyuanguanxi = $_POST['xueyuanguanxi'];
    $password = $_POST['password'];
    $laorenname = $_POST['laorenname'];
    $laorenxingbie = $_POST['laorenxingbie'];
    $laorenage = $_POST['laorenage'];
    $laorenID = $_POST['laorenID'];
    $laorendianhua = $_POST['laorendianhua'];
    // 执行 SQL 查询语句，获取所有已录入的家属和被监护者的身份证号
    $result = $conn->query("SELECT `被监测人身份证号`, `家属身份证号` FROM `亲属关系`");
    // 存储所有已录入的家属和被监护者的身份证号
    $IDfst = array();
    $IDsec = array();
    while ($row = $result->fetch_assoc()) {
        $IDfst[] = $row["家属身份证号"];
        $IDsec[] = $row["被监测人身份证号"];
    }
   
    // 判断用户输入的信息是否已经被录入
    if (in_array($_POST["jiashuID"], $IDfst) && in_array($_POST["laorenID"], $IDsec)) {
    ?>
    <script>
        Swal.fire({
            title: '注册失败！',
            text: "该账号已被创建！",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '点击重试'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href="Test.html?p=Regist";
            }
        })
    </script>
    <?php
    } 
    else {
        // 如果没有被录入，则将该信息插入到数据库中
        $sql1 = "INSERT INTO `家属` (`姓名`,`身份证号`,`性别`,`电话号码`) VALUES ('$jiashuname','$jiashuID','$jiashuxingbie','$jiashudianhua')";
        $sql2 = "INSERT INTO `亲属关系` (`被监测人身份证号`,`家属身份证号`,`血缘关系`,`登录密码`) VALUES ('$laorenID','$jiashuID','$xueyuanguanxi','$password')";
        $sql3 = "INSERT INTO `被监测人` (`姓名`,`身份证号`,`性别`,`年龄`,`联系电话`) VALUES ('$laorenname','$laorenID','$laorenxingbie','$laorenage','$laorendianhua')";
        // 执行查询
        $conn->query($sql1);
        $conn->query($sql3);
        $conn->query($sql2);
        // Display success message
    ?>
    <script>
        let timerInterval
        Swal.fire({
            title: '注册成功!',
            html: '即将跳转至登录界面： <b></b> milliseconds.',
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
            window.location.href="Test.html?p=Login";
        })
    </script>
    <?php
    }

 // Close database connection
}

mysqli_close($conn);
?>
</body>

