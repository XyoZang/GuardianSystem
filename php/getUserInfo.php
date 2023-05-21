<?php

include 'common.php';
session_start();

// 创建与 MySQL 数据库的连接
$conn = linkDB();

// Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $msg = "服务器连接失败！";
} else{
    $id = inputNULL($_SESSION['id']);
    $role = inputNULL($_SESSION['role']);
    $reAccount = $conn->query("SELECT user_name, email FROM sys_account WHERE id='$id'");
    //---------------------------------------------------------------------------------------------------------------------------
    if ($role == 'user') {
        $reProfile = $conn->query("SELECT name, gender, phone_number, id_number FROM user_profile WHERE uid='$id'");
    } else if ($role == 'doctor') {
        $reProfile = $conn->query("SELECT name, age, gender, title, department, phone_number, mid, comment FROM doctor_profile WHERE rid='$id'");
    }
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
        $mid = outputNULL($rowP["mid"]);
        $reName = $conn->query("SELECT name FROM medical_institution WHERE mid='$mid'");
        $instiName = $reName->fetch_assoc();
        $inName = $instiName['name'];
    }
    if ($role == 'user') {
        $info = array (
            'userName' => outputNULL($rowA["user_name"]),
            'userEmail' => outputNULL($rowA["email"]),
            'name' => outputNULL($rowP["name"]),
            'gender' => outputNULL($rowP["gender"]),
            'phone_number' => outputNULL($rowP["phone_number"]),
            'id_number' => outputNULL($rowP["id_number"])
        );
    } else if ($role == 'doctor') {
        $info = array (
            'userName' => outputNULL($rowA["user_name"]),
            'userEmail' => outputNULL($rowA["email"]),
            'name' => outputNULL($rowP["name"]),
            'age' => outputNULL($rowP["age"]),
            'gender' => outputNULL($rowP["gender"]),
            'title' => outputNULL($rowP["title"]),
            'department' => outputNULL($rowP["department"]),
            'phone_number' => outputNULL($rowP["phone_number"]),
            'institutionName' => $inName,
            'comment' => outputNULL($rowP["comment"])
        );
    }
}
$conn->close();
//返回用户信息给ajax
$response = array(
    'status' => $status,
    "msg" => $msg,
    "data" => $info
);
echo json_encode($response);
?>