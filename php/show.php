<?php

include 'common.php';

// 创建与 MySQL 数据库的连接
$conn = linkDB();

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败：" . $conn->connect_error);
}
// 查询最新的血压、血氧、脉搏、心电和脑电数据
$sql1 = "SELECT `血压数据` FROM `血压` ORDER BY `日期时间` DESC LIMIT 3";
$sql2 = "SELECT `血氧数据` FROM `血氧` ORDER BY `日期时间` DESC LIMIT 3";
$sql3 = "SELECT `脉搏数据` FROM `脉搏` ORDER BY `日期时间` DESC LIMIT 3";
$sql4 = "SELECT `心电数据` FROM `心电` ORDER BY `日期时间` DESC LIMIT 3";
$sql5 = "SELECT `脑电数据` FROM `脑电` ORDER BY `日期时间` DESC LIMIT 3";

function SQLtoJSON($conn, $sql, $filedName){
    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = (float)$row[$filedName];
    }
    return $data;
}

$response = array(
    '血压数据' => SQLtoJSON($conn, $sql1, '血压数据'),
    '血氧数据' => SQLtoJSON($conn, $sql2, '血氧数据'),
    '脉搏数据' => SQLtoJSON($conn, $sql3, '脉搏数据'),
    '心电数据' => SQLtoJSON($conn, $sql4, '心电数据'),
    '脑电数据' => SQLtoJSON($conn, $sql5, '脑电数据')
);
echo json_encode($response);

// 关闭数据库连接
$conn->close();
?>