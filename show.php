<?php
$servername = "localhost";
$username = "guardiansystem";
$password = "zxq123456";
$dbname = "GuardianSystem";
// 创建与 MySQL 数据库的连接
$conn = new mysqli($servername, $username, $password, $dbname,3306);
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

// 执行 SQL 查询
$result1 = $conn->query($sql1);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);
$result4 = $conn->query($sql4);
$result5 = $conn->query($sql5);

$data1=array();
$data2=array();
$data3=array();
$data4=array();
$data4=array();
while ($row = $result1->fetch_assoc()) {
    $data1[] = $row['血压数据'];
}
while ($row = $result2->fetch_assoc()) {
    $data2[] = $row['血氧数据'];
}
while ($row = $result3->fetch_assoc()) {
    $data3[] = $row['脉搏数据'];
}
while ($row = $result4->fetch_assoc()) {
    $data4[] = $row['心电数据'];
}
while ($row = $result5->fetch_assoc()) {
    $data5[] = $row['脑电数据'];
}
$Data1 = array();
$Data2 = array();
$Data3 = array();
$Data4 = array();
$Data5 = array();
foreach($data1 as $value){
    $Data1[] = (float)$value;
}
foreach($data2 as $value){
    $Data2[] = (float)$value;
}
foreach($data3 as $value){
    $Data3[] = (float)$value;
}
foreach($data4 as $value){
    $Data4[] = (float)$value;
}
foreach($data5 as $value){
    $Data5[] = (float)$value;
}
$Data = array(
    '血压数据' => $Data1,
    '血氧数据' => $Data2,
    '脉搏数据' => $Data3,
    '心电数据' => $Data4,
    '脑电数据' => $Data5
);

$ResponseData = json_encode($Data, JSON_UNESCAPED_UNICODE);
echo $ResponseData;

// 关闭数据库连接
$conn->close();
?>