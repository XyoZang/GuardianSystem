<?php
//可以考虑更改文件名“getUer/DoctorInfo”
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
    $id = inputNULL($_COOKIE["token"]);
    $reAccount = $conn->query("SELECT user_name, email FROM sys_account WHERE id='$id'");
    //---------------------------------------------------------------------------------------------------------------------------
    //此处应该有if语句，如果角色为用户则：
    $reProfile = $conn->query("SELECT name, gender, phone_number, id_number FROM user_profile WHERE uid='$id'");
    //此处应该有else语句，如果角色为医生则：
    $reProfile = $conn->query("SELECT name, age, gender, title, department, mid, comment FROM doctor_profile WHERE rid='$id'");
    //---------------------------------------------------------------------------------------------------------------------------
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
    //---------------------------------------------------------------------------------------------------------------------------
    //此处应该有if语句，如果角色为用户则：
    $userInfo = array (
        'userName' => outputNULL($rowA["user_name"]),
        'userEmail' => outputNULL($rowA["email"]),
        'name' => outputNULL($rowP["name"]),
        'gender' => outputNULL($rowP["gender"]),
        'phone_number' => outputNULL($rowP["phone_number"]),
        'id_number' => outputNULL($rowP["id_number"])
    );
    //此处应该有else语句，如果角色为医生则：
    $userInfo = array (
        'userName' => outputNULL($rowA["user_name"]),
        'userEmail' => outputNULL($rowA["email"]),
        'name' => outputNULL($rowP["name"]),
        'age' => outputNULL($rowP["age"]),
        'gender' => outputNULL($rowP["gender"]),
        'title' => outputNULL($rowP["title"]),
        'department' => outputNULL($rowP["department"]),
        'mid' => outputNULL($rowP["mid"]),
        'comment' => outputNULL($rowP["comment"])
    );
    //---------------------------------------------------------------------------------------------------------------------------
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