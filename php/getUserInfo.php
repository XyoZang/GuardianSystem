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
    $reAccount = $conn->query("SELECT user_name, email FROM user_account WHERE uid='$uid'");
    $reProfile = $conn->query("SELECT name, gender, phone_number, id_number FROM user_profile WHERE uid='$uid'");
    // 将查询结果赋值给变量
    if ($reAccount->num_rows < 1){
        $status = "Failed";
        $info = "查询失败！";
    } else{
        $status = "Success";
        $info = "查询成功";
        $rowA = $reAccount->fetch_assoc();
        $rowP = $reProfile->fetch_assoc();
    }
    
}
mysqli_close($conn);
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    'userName' => $rowA["user_name"],
    'userEmail' => $rowA["email"],
    'name' => $rowP["name"],
    'gender' => $rowP["gender"],
    'phone_number' => $rowP["phone_number"],
    'id_number' => $rowP["id_number"],
    "info" => $info
);
echo json_encode($response);
?>