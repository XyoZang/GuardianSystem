<?php

$config = include 'config.php';

//初始化数据库信息
$mysqlHost = $config['mysqlHost'];
$mysqlUsername = $config['mysqlUsername'];
$mysqlPassword = $config['mysqlPassword'];
$mysqlDbname = $config['mysqlDbname'];

// 创建与 MySQL 数据库的连接
$conn = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDbname);

function generateUuid()
{
    if (function_exists('uuid_create')) {
        $uuid = uuid_create(1); // 使用 UUID v1 版本生成
    } else {
        // 如果 uuid_create() 不存在，使用其他方式生成
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // 指定 UUID 版本为 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // 标记为 UUID 变体
        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    return $uuid;
}
 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    $status = "Failed";
    $info = "服务器连接失败！";
} else{
    // Insert data into database
    $userName = $_POST['userName'];
    $userPswd = $_POST['userPswd'];
    $userEmail = $_POST['userEmail'];
    // 执行 SQL 查询语句，获取所有已录入的家属和被监护者的身份证号
    $result1 = $conn->query("SELECT user_name, email FROM user_account");
    // 存储所有已录入的家属和被监护者的身份证号
    $IDlist = array();
    $Emaillist = array();
    while ($row = $result1->fetch_assoc()) {
        $IDlist[] = $row["user_name"];
        $Emaillist = $row["email"];
    }
   
    // 判断用户输入的信息是否已经被录入
    if (in_array($userName, $IDlist)) {
        $status = "Failed";
        $info = "该用户名已被注册！";
    } elseif (in_array($userEmail, $Emaillist)) {
        $status = "Failed";
        $info = "该邮箱已被注册！";
    }
    else {
        //用户密码加密
        // $options = [
        //     'cost' => 12,
        // ];
        // $hashPswd =  password_hash($userPswd, PASSWORD_BCRYPT, $options);
        // 如果没有被录入，则将该信息插入到数据库中
        $uid = generateUuid();
        $uid = md5($uid);
        $now_time = date("Y-m-d H:i:s");
        $sql1 = "INSERT INTO user_account (uid, user_name, password, email, create_time) VALUES ('$uid','$userName','$userPswd','$userEmail', '$now_time')";
        // 执行查询
        $result = $conn->query($sql1);
        if($result === false) {
            //查询失败
            $status = "Failed";
            $info = "数据插入失败！";
            $log = "Error: " . mysqli_error($conn);
         } else {
            //查询成功
            $status = "Success";
            $info = "注册成功！";
            $log =  "Number of rows: " . mysqli_affected_rows($conn);
         }
    }
}
// Close database connection
mysqli_close($conn);
//返回用户注册状态信息给ajax
$response = array(
    'status' => $status,
    'message' => $info,
    "log" => $log
);
echo json_encode($response);
?>

