<?php

$config = include 'config.php';

//连接数据库
function linkDB(){
    global $config;
    //初始化数据库信息
    $mysqlHost = $config['mysqlHost'];
    $mysqlUsername = $config['mysqlUsername'];
    $mysqlPassword = $config['mysqlPassword'];
    $mysqlDbname = $config['mysqlDbname'];
    // 创建与 MySQL 数据库的连接
    $conn = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDbname);
    return $conn;
}

//UUID生成
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
    return md5($uuid);
}

//盐生成
function generateSalt($len=6){
    $salt = '';
    for ($i = 0; $i < $len; $i++) {
        $salt .= chr(mt_rand(33, 126));
    }
    return $salt;
}

//异常捕获函数
function myException($exception) {
    $response = array(
        'status' => 'Failed',
        'msg' => 'ErrorLine: '.$exception->getLine(),
        "log" => 'Error: '.$exception->getMessage(),
    );
    echo json_encode($response);
}
?>