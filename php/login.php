<?php

include 'encrypt.php';
$config = include 'config.php';

//初始化数据库信息
$mysqlHost = $config['mysqlHost'];
$mysqlUsername = $config['mysqlUsername'];
$mysqlPassword = $config['mysqlPassword'];
$mysqlDbname = $config['mysqlDbname'];

// 创建与 MySQL 数据库的连接
$conn = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDbname);

 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $info = "服务器连接失败！";
} else{
    //下面实现已有账号的登录功能
    $loginAccount = $_POST['loginAccount'];
    $password = $_POST['loginPswd'];
    //判断登录方式为邮箱或用户名，并查询对应的密码
    $pattern = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
    if (preg_match($pattern, $loginAccount)){
        $Account = "email";
    } else{
        $Account = "user_name";
    }
    $result = $conn->query("SELECT password FROM user_account WHERE $Account='$loginAccount'");
    // 将查询结果赋值给变量
    if ($result->num_rows < 1){
        $status = "Failed";
        $info = "用户名错误或尚未注册！";
    } else{
        $row = $result->fetch_assoc();
        $mima = $row["password"];
        //验证数据库中录入的信息
        if ($password == $mima) {
            $status = "Success";
            $info = "登录成功！";
            //获取用户ID并加密生成token,生成cookie用于保持登陆状态
            $re = $conn->query("SELECT uid FROM user_account WHERE $Account='$loginAccount'");
            $ro = $re->fetch_assoc();
            $uid = $ro["uid"];
            // $token = encrypt($uid);
            setcookie("token", $uid, time()+1800, '/');
        }else{
            $status = "Failed";
            $info = "密码错误！";
        }
    }
}
mysqli_close($conn);
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    'message' => $info,
    "log" => $log
);
echo json_encode($response);
?>