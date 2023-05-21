<?php
//用户通过查询link_user_patient来获取患者数据，医生通过查询病历来获取患者数据（病历表和医疗机构表的结合）
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
    //根据token解码出uid用以查询
    $id = inputNULL($_COOKIE["token"]);

    //---------------------------------------------------------------------------------------------------------------------------
    //此处应该有if语句，如果角色为用户则：
    $result = $conn->query("SELECT pid FROM link_user_patient WHERE uid='$id'");

    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
        $status = "Failed";
        $type = "Warning";
        $msg = "查询失败或尚未绑定监测人！";
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
                $msg = '病人信息查询失败';
            } else{
                $status = "Success";
                $msg = "查询成功";
                while ($r = $re->fetch_assoc()) {
                    $patientList[] = array(
                        'pid' => outputNULL($pid),
                        'name' => outputNULL($r["name"]),
                        'age' => outputNULL($r['age'])
                    );
                }
                $pindex += 1;
                $_SESSION['pindex' . $pindex] = $pid;
            }
        }
    }
    //---------------------------------------------------------------------------------------------------------------------------
    //此处应该有else语句，如果角色为医生则：
    //病历表中包含医生的rid和医疗机构的mid，子查询返回该医生所属的医疗机构（可能有多家机构）的mid，
    //然后作为限制条件查询这几家机构下的病人id(pid)
    $result = $conn->query("SELECT patient_bingli.pid FROM patient_bingli WHERE patient_bingli.mid = (SELECT patient_bingli.mid FROM patient_bingli WHERE patient_bingli.rid = $id);");
    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
        $status = "Failed";
        $type = "Warning";
        $msg = "查询失败或您的机构没有病历记录！";
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
                $msg = '病人信息查询失败';
            } else{
                $status = "Success";
                $msg = "查询成功";
                while ($r = $re->fetch_assoc()) {
                    $patientList[] = array(
                        'pid' => outputNULL($pid),
                        'name' => outputNULL($r["name"]),
                        'age' => outputNULL($r['age'])
                    );
                }
                $pindex += 1;
                $_SESSION['pindex' . $pindex] = $pid;
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------------------------
$conn->close();

//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    "msg" => $msg,
    "type" => $type,
    'data' => $patientList
);

echo json_encode($response);
?>