<?php

include 'encrypt.php';
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
    //根据token解码出uid用以查询
    $uid = $_COOKIE["token"];
    $result = $conn->query("SELECT user_name, email FROM user_account WHERE uid='$uid'");
    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
        $status = "Failed";
        $info = "查询失败！";
    } else{
        $row = $result->fetch_assoc();
        $status = "Success";
        $info = "查询成功";
        $userName = $row["user_name"];
        $userEmail = $row["email"];
    }
}
mysqli_close($conn);
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    'userName' => $userName,
    'userEmail' => $userEmail,
    "info" => $info
);
echo json_encode($response);
?>