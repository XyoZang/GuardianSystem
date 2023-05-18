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
    var activeForm = $('#nav-tabContent').find('.active').find('.formRegist');
    console.log(activeForm.serialize());
    $.ajax({
        url: '../php/getAccount.php',
        method: 'POST',
        data: 'Request=Regist&'+activeForm.serialize(),
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            Msg(response, function() {
                if (response['log']=="Continue") {
                    $("#ContinueInfo").show();
                } else {
                    $("#registModalDismiss").click();
                    $("#ContinueInfo").hide();
                }
            });
        },
        error: function(xhr, status, error) {
            console.log('连接失败');
        }
    });
});
//登录表单提交
$('#loginBtn').click(function() {
    if ($("#loginAccount").val()){
        $.ajax({
            url: '../php/getAccount.php',
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
                    if (response['data']=='user') {
                        $("#consoleHref").attr("href", "./user/#info");
                    } else if (response['data']=='doctor') {
                        $("#consoleHref").attr("href", "./data/#info");
                    }
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
var login_status = getLoginStatus();
if (login_status=='user') {
    $(".loginTrue").show();
    $("#consoleHref").attr("href", "./user/#info");
} else if (login_status=='doctor') {
    $(".loginTrue").show();
    $("#consoleHref").attr("href", "./data/#info");
} else {
    $(".loginFalse").show();
}
