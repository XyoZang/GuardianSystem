<?php
//可以考虑更改文件名“editUer/DoctorProfile”
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
    //这里的前端需要应该根据角色需要来更改表单
    //---------------------------------------------------------------------------------------------------------------------------

    //此处应该有if语句，如果角色为用户则：
    $name = inputNULL($_POST['edit_name']);
    $gender = inputNULL($_POST['edit_gender']);
    $phone_number = inputNULL($_POST['edit_phone_number']);
    $id_number = inputNULL($_POST['edit_id_number']);
    $now_time = date("Y-m-d H:i:s");
    //此处应该有else语句，如果角色为医生则：
    $name = inputNULL($_POST['edit_name']);
    $age = inputNULL($_POST['edit_age']);
    $gender = inputNULL($_POST['edit_gender']);
    $title = inputNULL($_POST['edit_title']);
    $department = inputNULL($_POST['edit_department']);
    $mid = inputNULL($_POST['edit_mid']);
    $comment = inputNULL($_POST['edit_comment']);
    $now_time = date("Y-m-d H:i:s");
    //---------------------------------------------------------------------------------------------------------------------------


    //根据token解码出id用以查询
    //为了避免混淆uid和rid，这里统一用id
    $id = $_COOKIE["token"];

    //根据用户资料是否存在来决定是插入新数据还是更新已有数据
    //---------------------------------------------------------------------------------------------------------------------------
    //此处应该有if语句，如果角色为用户则：
    $reProfile = $conn->query("SELECT name, gender, phone_number, id_number FROM user_profile WHERE uid='$id'");
    //此处应该有else语句，如果角色为医生则：
    $reProfile = $conn->query("SELECT name, age, gender, title, department, mid, comment FROM doctor_profile WHERE rid='$id'");
    //---------------------------------------------------------------------------------------------------------------------------
    if ($reProfile->num_rows < 1){
        $Profile_exist = false;
    } else{
        $Profile_exist = true;
    }
    //---------------------------------------------------------------------------------------------------------------------------
    //更改if条件，如果Profile存在并且角色为用户则：
    if ($Profile_exist) {
        $sql = "UPDATE user_profile SET name = ?, gender = ?, phone_number = ?, id_number = ?, update_time = ? WHERE uid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiss", $name, $gender, $phone_number, $id_number, $now_time, $id);
    } else{
        $sql = "INSERT INTO user_profile (uid, name, gender, phone_number, id_number, update_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiis", $id, $name, $gender, $phone_number, $id_number, $now_time);
    }
    
    //更改if条件，如果Profile存在并且角色为医生则：
    if ($Profile_exist) {
        $sql = "UPDATE doctor_profile SET name = ?, age = ?, gender = ?, title = ?, department = ?, comment = ?, update_time = ? WHERE rid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiss", $name, $age, $gender, $title, $department, $comment, $now_time, $id);
    } else{
        $sql = "INSERT INTO user_profile (rid, name, age, gender, title, department, comment, update_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiis", $id, $name, $age, $gender, $title, $department, $comment, $now_time);
    }
    //---------------------------------------------------------------------------------------------------------------------------
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