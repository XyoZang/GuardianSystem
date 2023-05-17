<?php

include 'common.php';

// 创建与 MySQL 数据库的连接
$conn = linkDB();

 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $msg = "服务器连接失败！";
} else{
    //根据token解码出uid用以查询
    $uid = inputNULL($_COOKIE["token"]);
    $reAccount = $conn->query("SELECT user_name, email FROM user_account WHERE uid='$uid'");
    $reProfile = $conn->query("SELECT name, gender, phone_number, id_number FROM user_profile WHERE uid='$uid'");
    // 将查询结果赋值给变量
    if ($reAccount->num_rows < 1){
        $status = "Failed";
        $msg = "查询失败！";
    } else{
        $status = "Success";
        $msg = "查询成功";
        $rowA = $reAccount->fetch_assoc();
        $rowP = $reProfile->fetch_assoc();
    }
    $userInfo = array (
        'userName' => outputNULL($rowA["user_name"]),
        'userEmail' => outputNULL($rowA["email"]),
        'name' => outputNULL($rowP["name"]),
        'gender' => outputNULL($rowP["gender"]),
        'phone_number' => outputNULL($rowP["phone_number"]),
        'id_number' => outputNULL($rowP["id_number"])
    );
}
$conn->close();
//返回用户信息给ajax
$response = array(
    'status' => $status,
    "msg" => $msg,
    "data" => $userInfo
);
echo json_encode($response);
?>