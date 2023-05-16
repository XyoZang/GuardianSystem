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
    //接收数据
    $name = isNULL($_POST['edit_name']);
    $gender = isNULL($_POST['edit_gender']);
    $phone_number = isNULL($_POST['edit_phone_number']);
    $id_number = isNULL($_POST['edit_id_number']);
    $now_time = date("Y-m-d H:i:s");
    //根据token解码出uid用以查询
    $uid = $_COOKIE["token"];
    //根据用户资料是否存在来决定是插入新数据还是更新已有数据
    $reProfile = $conn->query("SELECT name, gender, phone_number, id_number FROM user_profile WHERE uid='$uid'");
    if ($reProfile->num_rows < 1){
        $Profile_exist = false;
    } else{
        $Profile_exist = true;
    }
    if ($Profile_exist) {
        $sql = "UPDATE user_profile SET name = ?, gender = ?, phone_number = ?, id_number = ?, update_time = ? WHERE uid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiss", $name, $gender, $phone_number, $id_number, $now_time, $uid);
    } else{
        $sql = "INSERT INTO user_profile (uid, name, gender, phone_number, id_number, update_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiis", $uid, $name, $gender, $phone_number, $id_number, $now_time);
    }
    $stmt->execute();
    if ($stmt->affected_rows > 0){
        $status = "Success";
        $msg = "保存成功！";
    }else{
        $status = "Failed";
        $msg = "保存失败！";
        $log = "Error: " . mysqli_error($conn);
    }
    $stmt->close();
}
$conn->close();
//返回用户数据编辑状态信息给ajax
$response = array(
    'status' => $status,
    'msg' => $msg,
    "log" => $log,
);
echo json_encode($response);
?>