<?php

include 'common.php';
session_start();
set_exception_handler('myException');

// 创建与 MySQL 数据库的连接
$conn = linkDB();

$Request = $_POST['Request'];
$uid = $_COOKIE['token'];
$now_time = date("Y-m-d H:i:s");
$pid = $_SESSION['pindex'.$_POST['pindex']];
$status = 'Failed';
$info = '请求参数错误！';
if ($Request=='Insert') {
    //接收数据
    $name = $_POST['name'];
    $id_number = $_POST['id_number'];
    $phone_number = $_POST['phone_number']?$_POST['phone_number']:NULL;
    $gender = $_POST['gender']?$_POST['gender']:NULL;
    $age = $_POST['age']?$_POST['age']:NULL;
    $relation = $_POST['relation'];
    //先根据用户输入的ID号码查询病人库中是否存在该病人
    $result = $conn->query("SELECT pid FROM patient_profile WHERE id_number='$id_number'");
    if ($result->num_rows>0){
        $Profile_exist = true;
        $pid = $result->fetch_assoc()['pid'];
    } else {
        $Profile_exist = false;
    }
    //若不存在则生成uuid并插入到病人库和连接库中
    if (!$Profile_exist) {
        $pid = generateUuid();
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
    } else if ($Profile_exist){
        //若病人存在于库中，则进一步查询病人是否已与用户绑定
        $result = $conn->query("SELECT * FROM link_user_patient WHERE uid='$uid' AND pid='$pid'");
        //若未绑定则直接将用户与病人绑定
        if ($result->num_rows < 1) {
            $conn->query("INSERT INTO link_user_patient (pid, uid, relation, create_time) VALUES ('$pid', '$uid', '$relation', '$now_time')");
            if ($conn->affected_rows > 0){
                $status = "Success";
                $info = "保存成功！";
            } else {
                $status = "Failed";
                $info = "数据插入失败！";
            }
        } else {
            $status = "Failed";
            $info = "请勿重复绑定！";
        }
        //若已绑定则不进行操作，提示用户绑定重复
    } else {
        $status = "Failed";
        $info = "未知错误！";
    }
} else if ($Request=='Edit') {
    if (isset($pid)){
        $name = $_POST['edit_name'];
        $phone_number = $_POST['edit_phone_number']?$_POST['edit_phone_number']:NULL;
        $gender = $_POST['edit_gender']?$_POST['edit_gender']:NULL;
        $age = $_POST['edit_age']?$_POST['edit_age']:NULL;
        $sql = "UPDATE patient_profile SET name = ?, phone_number = ?, gender = ?, age = ?, update_time = ? WHERE pid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisiss", $name, $phone_number, $gender, $age, $now_time, $pid);
        $stmt->execute();
        if ($stmt->affected_rows > 0){
            $status = "Success";
            $info = "保存成功！";
        }else{
            $status = "Failed";
            $info = "数据插入失败！";
            $log = "Error: " . mysqli_error($conn) . $error1;
        }
        $stmt->close();
    } else {
        $status = "Failed";
        $info = "资料不存在！";
    }
} else if ($Request=='Delete') {
    $conn->query("DELETE FROM link_user_patient WHERE pid='$pid' AND uid='$uid'");
    if ($conn->affected_rows > 0){
        $status='Success';
        $info='删除成功！';
    } else{
        $status='Failed';
        $info='删除失败！';
        $log="失败：" . mysqli_error($conn);
    }
}
$conn->close();
//返回用户数据编辑状态信息给ajax
$response = array(
    'status' => $status,
    'msg' => $info,
    "log" => $log,
);
echo json_encode($response);

?>