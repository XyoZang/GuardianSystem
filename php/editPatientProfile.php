<?php

include 'common.php';

// 创建与 MySQL 数据库的连接
$conn = linkDB();

 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $info = "服务器连接失败！";
} else{
    //生成pid
    $pid = generateUuid();
    //uid
    $uid = $_COOKIE['token'];
    //接收数据
    $name = $_POST['name'];
    $id_number = $_POST['id_number'];
    $phone_number = $_POST['phone_number']?$_POST['phone_number']:NULL;
    $gender = $_POST['gender']?$_POST['gender']:NULL;
    $age = $_POST['age']?$_POST['age']:NULL;
    $relation = $_POST['relation'];
    $now_time = date("Y-m-d H:i:s");
    //SQL语句插入被检测人信息表与链接表
    $sql1 = "INSERT INTO patient_profile (pid, name, id_number, phone_number, gender, age, update_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $sql2 = "INSERT INTO link_user_patient (pid, uid, relation, create_time) VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt2 = $conn->prepare($sql2);
    $stmt1->bind_param("ssiisis", $pid, $name, $id_number, $phone_number, $gender, $age, $now_time);
    $stmt2->bind_param("ssss", $pid, $uid, $relation, $now_time);
    $stmt1->execute();
    $error1 = mysqli_error($conn);
    $stmt2->execute();
    if ($stmt1->affected_rows > 0 && $stmt2->affected_rows > 0){
        $status = "Success";
        $info = "保存成功！";
    }else{
        $status = "Failed";
        $info = "数据插入失败！";
        $log = "Error: " . mysqli_error($conn) . $error1;
    }
    $stmt1->close();
    $stmt2->close();
}
$conn->close();
//返回用户数据编辑状态信息给ajax
$response = array(
    'status' => $status,
    'message' => $info,
    "log" => $log,
);
echo json_encode($response);
?>