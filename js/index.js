//密码复杂度校验
function pswdCheck(){
    var text= $("#userPswd").val();
    var re =/^(?=.*[a-z])(?=.*\d)[^]{8,16}$/;
    var result =  re.test(text);
    if(!result) {
        $("#pswdCheckSpan").html("密码必须包含数字,字母,且不少于8位");
        return false;
    }else {
        $("#pswdCheckSpan").html("");
        return true;
    }
}
//确认密码
function pswdConf(){
    var text1= $("#userPswd").val();
    var text2= $("#userPswdConf").val();
    if (text2 == text1){
        $("#pswdConfSpan").html("");
        return true;
    }else {
        $("#pswdConfSpan").html("两次输入的密码不一致!");
        return false;
    }
}
//校验邮箱
function emailCheck(){
    var text= $("#userEmail").val();
    var re =/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var result =  re.test(text);
    if(!result || !text) {
        $("#emailCheckSpan").html("邮箱格式不正确！");
        return false;
    }else {
        $("#emailCheckSpan").html("");
        return true;
    }
}
//注册表单提交
$('#registBtn').click(function() {
    if (pswdCheck() && pswdConf() && emailCheck()){
        $.ajax({
            url: '../php/regist.php',
            method: 'POST',
            data: $('#formRegist').serialize(),
            dataType: 'json',
            success: function(response) {
                // 处理服务器返回的数据
                console.log(response);
                Msg(response, function() {
                    $("#registModalDismiss").click();
                });
            },
            error: function(xhr, status, error) {
                console.log('连接失败');
            }
        });
    } else{
        Qmsg.error("失败：请检查信息格式！");
    }
});
//登录表单提交
$('#loginBtn').click(function() {
    if ($("#loginAccount").val()){
        $.ajax({
            url: '../php/getLoginStatus.php',
            method: 'POST',
            data: 'Request=Login&'+$('#formLogin').serialize(),
            dataType: 'json',
            success: function(response) {
                // 处理服务器返回的数据
                console.log(response);
                Msg(response, function() {
                    $(".loginFalse").hide();
                    $(".loginTrue").show();
                    $("#loginModalDismiss").click();
                });
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    } else{
        Qmsg.error("请输入用户名！");
    }
});
