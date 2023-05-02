<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>健康监测平台</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/echarts@4.1.0/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

</head>
<style>
        .card {
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f7f7f7;
            box-shadow: 2px 2px 5px #ccc;
            width: 45%;
            float: left;
        }
         .clearfix {
            clear: both;
        }
       body {
            background-color: #f9f9f9;
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        form {
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            margin: 50px auto;
        }
        h1 {
            text-align: center;
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #666;
        }
        input[type="text"], input[type="password"] {
        background-color: #f4f4f4;
        border: none;
        padding: 10px;
        font-size: 16px;
        color: #333;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
        transition: box-shadow 0.3s ease-out;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        }
        input[type="text"]:focus, input[type="password"]:focus, input[type="radio"]:focus {
            outline: none;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
        }

        button[type="submit"] {
            background-color: #f45b69;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            padding: 10px 20px;
            transition: background-color 0.3s ease-out;
            margin-top: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        button[type="submit"]:hover {
            background-color: #ef3d47;
        }
</style>
<body>
<?php
// 连接MySQL数据库
$con = mysqli_connect('localhost:3306', 'GuardianSystem', 'CzrH5CcfmryAGxtP', 'guardiansystem');
 // Check if connection is successful
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
    echo "404 NotFound!";
} else{
    $Page = isset($_GET['page']) ? 'Regist' : $_GET['page'];
}
// Insert data into database
if (isset($_POST['submitfirst'])) {
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
    $result = $con->query("SELECT `被监测人身份证号`, `家属身份证号` FROM `亲属关系`");
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
	mysqli_query($con, $sql1);
	mysqli_query($con, $sql3);
	mysqli_query($con, $sql2);
 	// Display success message
    echo '<script> alert("注册成功!"); </script>';
    }
}
//下面实现已有账号的登录功能
if (isset($_POST['submitsec'])) {
    //输入家属身份证号和密码以验证身份
    $jiashuID = $_POST['jiashuID'];
    $password = $_POST['password'];
    // 执行 SQL 查询语句，获取所有已录入的家属的身份证号和设定的登录密码
    $result = $con->query("SELECT `登录密码`, `家属身份证号` FROM `亲属关系`");
    // 存储所有已录入的姓名和电话的数组
    $ID = array();
    $mima = array();
    while ($row = $result->fetch_assoc()) {
        $ID[] = $row["家属身份证号"];
        $mima[] = $row["登录密码"];
    }
    //验证数据库中录入的信息
    if (in_array($_POST["jiashuID"], $ID) && in_array($_POST["password"], $mima)) {
            echo '<script> alert("验证成功，已登录!"); </script>';
    }else{
            echo '<script> alert("登录失败，身份证号或密码错误!"); </script>';
    }
}
 // Close database connection
mysqli_close($con);
?>
<form id="form1" method="post" action="">
        <h1>注册平台</h1>
		<h3>家属信息录入</h3>
		<label for="text">家属姓名:</label>
		<input type="text" id="jiashuname" name="jiashuname" required>
		<label for="jiashuID">身份证号:</label>
		<input type="text" id="jiashuID" name="jiashuID" required>
		<label for="jiashuxingbie">性别:</label>
        <label for="jiashuxingbie">男</label>
        <input type="radio" id="jiashuxingbie" name="jiashuxingbie" value="男" required>
        <label for="jiashuxingbie">女</label>
        <input type="radio" id="jiashuxingbie" name="jiashuxingbie" value="女" required>
		<label for="jiashudianhua">电话:</label>
		<input type="text" id="jiashudianhua" name="jiashudianhua" required>
		<label for="xueyuanguanxi">血缘关系:</label>
		<input type="text" id="xueyuanguanxi" name="xueyuanguanxi" required>
		<label for="password">设置密码(输入任意的数字组合):</label>
		<input type="password" id="password" name="password" required>
		<h3>被监护人信息录入</h3>
		<label for="laorenname">姓名:</label>
		<input type="text" id="laorenname" name="laorenname" required>
        <label for="laorenxingbie">性别:</label>
        <label for="laorenxingbie">男</label>
        <input type="radio" id="laorenxingbie" name="laorenxingbie" value="男" required>
        <label for="laorenxingbie">女</label>
        <input type="radio" id="laorenxingbie" name="laorenxingbie" value="女" required>
		<label for="laorenage">年龄:</label>
		<input type="text" id="laorenage" name="laorenage" required>
		<label for="laorenID">身份证号:</label>
		<input type="text" id="laorenID" name="laorenID" required>
		<label for="laorendianhua">电话:</label>
		<input type="text" id="laorendianhua" name="laorendianhua" required>
	    <button type="submit" name=“submitfirst”>提交</button>
        <button onclick="showForm2()">已有账号，去登录</button>
</form>
<form id="form2" method="post" action="">
        <h1>登录平台</h1>
		<h3>家属身份验证</h3>
        <label>家属姓名:</label>
        <input type="text" id="jiashuname" name="jiashuname" required>
		<label for="jiashuID">身份证号:</label>
        <input type="text" id="jiashuID" name="jiashuID" required>
		<label for="password">登录密码:</label>
		<input type="password" id="password" name="password" required>
	    <button type="submit" name=“submitsec”>点击登录账号</button>
        <button onclick="showForm1()">回到注册平台</button>
</form>
       <script>
         function showForm2() {
               document.getElementById("form1").style.display = "none";
               document.getElementById("form2").style.display = "block";
          }

         function showForm1() {
               document.getElementById("form2").style.display = "none";
               document.getElementById("form1").style.display = "block";
          }
       </script>
</body>
</html>












