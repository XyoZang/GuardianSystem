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
    $Request = isNULL($_POST['Request']);
    if ($Request=='Login'){
        //下面实现已有账号的登录功能
        $loginAccount = isNULL($_POST['loginAccount']);
        $password = isNULL($_POST['loginPswd']);
        //正则表达式判断登录方式为邮箱或用户名，并查询对应的密码
        //此正则表达式验证是否为邮箱格式
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
            $msg = "用户名错误或尚未注册！";
        } else{
            $row = $result->fetch_assoc();
            $mima = $row["password"];
            //验证数据库中录入的信息
            if ($password == $mima) {
                $status = "Success";
                $msg = "登录成功！";
                //获取用户ID并加密生成token,生成cookie用于保持登陆状态
                $re = $conn->query("SELECT uid FROM user_account WHERE $Account='$loginAccount'");
                $ro = $re->fetch_assoc();
                $uid = $ro["uid"];
                // $token = encrypt($uid);
                setcookie("token", $uid, 0, '/');
                $_SESSION['uid'] = $uid;
            }else{
                $status = "Failed";
                $msg = "密码错误！";
            }
        }
    } else if ($Request=='LogOut'){
        session_destroy();
        $status = 'Success';
        $msg = '您已退出登录！';
    } else if ($Request=='getStatus'){
        //查询uid是否存在来判定
        if (isset($_SESSION['uid'])){
            $status = 'Success';
            $data = 'Login';
        } else{
            $status = 'Success';
            $data = 'LogOut';
        }
    } else{
        $status = 'Failed';
        $msg = '请求参数错误！';
    } 
}
$conn->close();
//返回用户登录状态信息给ajax
$response = array(
    'status' => $status,
    'msg' => $msg,
    "data" => $data
);
echo json_encode($response);
?>