<?php
// 连接MySQL数据库
$conn = mysqli_connect('localhost:3306', 'GuardianSystem', 'CzrH5CcfmryAGxtP', 'guardiansystem');
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
        echo '<script> alert("身份证号错误或该账号不存在！"); </script>';
    } else{
        $row = $result->fetch_assoc();
        $mima = $row["登录密码"];
        //验证数据库中录入的信息
        if ($password == $mima) {
            echo '<script> alert("验证成功，已登录!"); </script>';
        }else{
            echo '<script> alert("登录失败，密码错误!"); </script>';
        }
    }
}
?>