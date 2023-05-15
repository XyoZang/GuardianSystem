<?php

include 'common.php';
session_start();

// 创建与 MySQL 数据库的连接
$conn = linkDB();

 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $info = "服务器连接失败！";
} else{
    $pid = $_SESSION['pindex'.$_POST['pindex']];
    if ($_POST['Request']=='delete'){
        $result = $conn->query("DELETE FROM link_user_patient WHERE pid='$pid'");
        if ($conn->affected_rows > 0){
            $status='Success';
            $info='删除成功！';
        } else{
            $status='Failed';
            $info='执行失败！';
            $log="失败：" . $conn->error;
        }
    }  else{
        $result = $conn->query("SELECT name,id_number,phone_number,gender,age FROM patient_profile WHERE pid='$pid'");
        if ($result->num_rows < 1){
            $status = 'Failed';
            $info = '患者资料不存在！';
        } else{
            while($row = $result->fetch_assoc()){
                $patientData = array(
                    'name' => $row['name'],
                    'id_number' => $row['id_number'],
                    'phone_number' => $row['phone_number'],
                    'gender' => $row['gender'],
                    'age' => $row['age']
                );
            }
            $status = 'Success';
            $info = '数据获取成功！';
        }
    }
}
$conn->close();
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    "msg" => $info,
    'data' => $patientData,
    'log' => $log
);
echo json_encode($response);
?>