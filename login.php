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
    $status = "Failed";
    $info = "服务器连接失败！";
} else{
    //下面实现已有账号的登录功能
    $loginName = $_POST['loginName'];
    $password = $_POST['loginPswd'];
    // 执行 SQL 查询语句，查询与用户输入的身份证号和密码所匹配的数据
    $result = $conn->query("SELECT password FROM user_account WHERE user_name='$loginName'");
    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
        $status = "Failed";
        $info = "用户名错误或尚未注册！";
    } else{
        $row = $result->fetch_assoc();
        $mima = $row["password"];
        //验证数据库中录入的信息
        if ($password == $mima) {
            $status = "Success";
            $info = "登录成功！";
        }else{
            $status = "Failed";
            $info = "密码错误！";
        }
    }
}
mysqli_close($conn);
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    'message' => $info,
    "log" => $log
);
echo json_encode($response);
?>