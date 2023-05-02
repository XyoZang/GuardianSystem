<?php
// 连接MySQL数据库
$conn = mysqli_connect('localhost:3306', 'GuardianSystem', 'CzrH5CcfmryAGxtP', 'guardiansystem');
 // Check if connection is successful
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    echo "404 DataNotFound!";
} else{
    // Insert data into database
    $jiashuname = $_POST['jiashuname'];
    $jiashuID = $_POST['jiashuID'];
    $jiashuxingbie = $_POST['jiashuxingbie'];
    $jiashudianhua = $_POST['jiashudianhua'];
    $xueyuanguanxi = $_POST['xueyuanguanxi'];
    $password = $_POST['password'];
    $laorenname = $_POST['laorenname'];
    $laorenxingbie = $_POST['laorenxingbie'];
    $laorenage = $_POST['laorenage'];
    $laorenID = $_POST['laorenID'];
    $laorendianhua = $_POST['laorendianhua'];
    // 执行 SQL 查询语句，获取所有已录入的家属和被监护者的身份证号
    $result = $conn->query("SELECT `被监测人身份证号`, `家属身份证号` FROM `亲属关系`");
    // 存储所有已录入的家属和被监护者的身份证号
    $IDfst = array();
    $IDsec = array();
    while ($row = $result->fetch_assoc()) {
        $IDfst[] = $row["家属身份证号"];
        $IDsec[] = $row["被监测人身份证号"];
    }
    // 判断用户输入的信息是否已经被录入
    if (in_array($_POST["jiashuID"], $IDfst) && in_array($_POST["laorenID"], $IDsec)) {
        echo '<script> alert("该账号已被创建!"); </script>';
    } 
    else {
        // 如果没有被录入，则将该信息插入到数据库中
        $sql1 = "INSERT INTO `家属` (`姓名`,`身份证号`,`性别`,`电话号码`) VALUES ('$jiashuname','$jiashuID','$jiashuxingbie','$jiashudianhua')";
        $sql2 = "INSERT INTO `亲属关系` (`被监测人身份证号`,`家属身份证号`,`血缘关系`,`登录密码`) VALUES ('$laorenID','$jiashuID','$xueyuanguanxi','$password')";
        $sql3 = "INSERT INTO `被监测人` (`姓名`,`身份证号`,`性别`,`年龄`,`联系电话`) VALUES ('$laorenname','$laorenID','$laorenxingbie','$laorenage','$laorendianhua')";
        // 执行查询
        mysqli_query($conn, $sql1);
        mysqli_query($conn, $sql3);
        mysqli_query($conn, $sql2);
        // Display success message
        echo '<script> alert("注册成功!"); </script>';
    }

 // Close database connection
}

mysqli_close($conn);
?>