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
    //根据token解码出uid用以查询
    $uid = $_COOKIE["token"];
    $result = $conn->query("SELECT pid FROM link_user_patient WHERE uid='$uid'");
    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
        $status = "Failed";
        $info = "查询失败或尚未绑定监测人！";
    } else{
        $pidList = array();
        while ($row = $result->fetch_assoc()) {
            $pidList[] = $row['pid'];
        }
        $patientList = array();
        $pindex = 0;
        foreach ($pidList as $pid){
            $re = $conn->query("SELECT name, age FROM patient_profile WHERE pid='$pid'");
            if ($re->num_rows<1) {
                $status = 'Failed';
                $info = '病人信息查询失败';
            } else{
                $status = "Success";
                $info = "查询成功";
                while ($r = $re->fetch_assoc()) {
                    $patientList[] = array(
                        'pid' => $pid,
                        'name' => $r["name"],
                        'age' => $r['age']
                    );
                }
                $pindex += 1;
                $_SESSION['pindex' . $pindex] = $pid;
            }
        }
    }
}
$conn->close();
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    "msg" => $info,
    'data' => $patientList
);
echo json_encode($response);
?>