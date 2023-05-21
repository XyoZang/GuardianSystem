<?php

include 'common.php';
session_start();
set_exception_handler('myException');

/**
 * 
 * 账号管理文件
 * 包括注册、登录、登出、查询登陆状态
 * Request有 Regist、Login、LogOut、getStatus
 * 
 */

// 创建与 MySQL 数据库的连接
$conn = linkDB();

 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $msg = "服务器连接失败！";
} else {
    $Request = inputNULL($_POST['Request']);
    switch ($Request) {
        case "Regist":
            // Insert data into database
            $role = inputNULL($_POST['role']);
            switch ($role){
                case "user":
                    $userName = inputNULL($_POST['userName']);
                    $userPswd = inputNULL($_POST['userPswd']);
                    $userEmail = inputNULL($_POST['userEmail']);
                    if ($userName&&$userPswd&&$userEmail) {
                        $Regist = true;
                        $rName = $userName;
                        $rPswd = $userPswd;
                        $rEmail = $userEmail;
                    } else {
                        $status = "Failed";
                        $msg = "请完善信息！";
                    }
                    break;
                case "doctor":
                    //机构验证
                    $iAccount = inputNULL($_POST['iAccount']);
                    $iPswd = inputNULL($_POST['iPswd']);
                    //注册信息
                    $dName = inputNULL($_POST['dName']);
                    $dPswd = inputNULL($_POST['dPswd']);
                    $dEmail = inputNULL($_POST['dEmail']);
                    $result = $conn->query("SELECT password FROM medical_institution WHERE account='$iAccount'");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $password = $row['password'];
                        }
                        if ($iPswd == $password) {
                            if ($dName && $dPswd && $dEmail) {
                                $Regist = true;
                                $rName = $dName;
                                $rPswd = $dPswd;
                                $rEmail = $dEmail;
                            } else {
                                $status = "Success";
                                $msg = "验证成功，请继续完善信息！";
                                $log = "Continue";
                            }
                        } else {
                            $status = "Failed";
                            $msg = "验证密码错误！";
                        }
                    } else {
                        $status = "Failed";
                        $msg = "该账户不存在！";
                    }
                    break;
                default:
                    $status = "Failed";
                    $msg = "请求参数错误！";
                    break;
            }
            if ($Regist==true) {
                // 执行 SQL 查询语句，获取所有已注册的用户名和邮箱
                $result1 = $conn->query("SELECT user_name, email FROM sys_account");
                $IDlist = array();
                $Emaillist = array();
                while ($row = $result1->fetch_assoc()) {
                    $IDlist[] = $row["user_name"];
                    $Emaillist[] = $row["email"];
                }
                // 判断用户输入的信息是否已经被录入
                if (in_array($rName, $IDlist)) {
                    $status = "Failed";
                    $msg = "该用户名已被注册！";
                } elseif (in_array($rEmail, $Emaillist)) {
                    $status = "Failed";
                    $msg = "该邮箱已被注册！";
                } else {
                    // 如果没有被录入，则将该信息插入到数据库中
                    $id = generateUuid();
                    $now_time = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO sys_account (id, user_name, password, email, role, create_time) VALUES ('$id','$rName','$rPswd','$rEmail', '$role', '$now_time')";
                    // 执行查询
                    $result = $conn->query($sql);
                    if($result === false) {
                        //查询失败
                        $status = "Failed";
                        $msg = "注册失败！";
                        $log = "Error: " . mysqli_error($conn);
                    } else {
                        //查询成功
                        $status = "Success";
                        $msg = "注册成功！";
                        $log =  "Number of rows: " . mysqli_affected_rows($conn);
                    }
                }
            }
            break;
        case "Login": /*************************/
            //下面实现已有账号的登录功能
            $loginAccount = inputNULL($_POST['loginAccount']);
            $password = inputNULL($_POST['loginPswd']);
            //正则表达式判断登录方式为邮箱或用户名，并查询对应的密码
            //此正则表达式验证是否为邮箱格式
            $pattern = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
            if (preg_match($pattern, $loginAccount)){
                $Account = "email";
            } else{
                $Account = "user_name";
            }
            $result = $conn->query("SELECT password FROM sys_account WHERE $Account='$loginAccount'");
            // 将查询结果赋值给变量
            if ($result->num_rows < 1){
                $status = "Failed";
                $msg = "用户名错误或尚未注册！";
            } else{
                $row = $result->fetch_assoc();
                $mima = $row["password"];
                //验证数据库中录入的信息
                if ($password == $mima) {
                    $status = "Success";
                    $msg = "登录成功！";
                    //获取用户ID并加密生成token,生成cookie用于保持登陆状态
                    $re = $conn->query("SELECT id, role FROM sys_account WHERE $Account='$loginAccount'");
                    $ro = $re->fetch_assoc();
                    $id = $ro["id"];
                    $role = $ro["role"];
                    $_SESSION['id'] = $id;
                    $_SESSION['role'] = $role;
                    $data = $role;
                }else{
                    $status = "Failed";
                    $msg = "密码错误！";
                }
            }
            break;
        case "LogOut": /*************************/
            session_destroy();
            $status = 'Success';
            $msg = '您已退出登录！';
            break;
        case "getStatus": /*************************/
            //查询uid是否存在来判定
            if (isset($_SESSION['id'])&&isset($_SESSION['role'])){
                $status = 'Success';
                $data = inputNULL($_SESSION['role']);
            } else {
                $status = 'Success';
                $data = 'LogOut';
            }
            break;
        default:
            $status = 'Failed';
            $msg = '请求参数错误！';
            break;
    }
}
$conn->close();
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    'msg' => $msg,
    'log' => $log,
    "data" => $data
);
echo json_encode($response);
?>