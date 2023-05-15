$(document).ready(function(){
    //查询用户账户信息
    $.ajax({
        url: '../php/getUserInfo.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            Msg(response, function() {
                $("#userName").html(response['data']['userName']);
                $("#userEmail").html(response['data']['userEmail']);
                $("#name").html(response['data']['name']);
                $("#gender").html(response['data']['gender']);
                $("#phone_number").html(response['data']['phone_number']);
                $("#id_number").html(response['data']['id_number']);
            });
        },
        error: function(xhr, status, error) {
            console.log('Ajax连接失败');
        }
    });
    //提交用户修改信息
    $("#btn_submit_profile").click(function(){
        if (phoneNumCheck() && idNumCheck()){
            $.ajax({
                url: '../php/editUserProfile.php',
                method: 'POST',
                data: $('#formEditProfile').serialize(),
                dataType: 'json',
                success: function(response) {
                    // 处理服务器返回的数据
                    console.log(response);
                    Msg(response, function() {
                        $("#btn_cancel_profile").click();
                        $("#name").html($("#edit_name").val());
                        $("#gender").html($("#edit_gender").val());
                        $("#phone_number").html($("#edit_phone_number").val());
                        $("#id_number").html($("#edit_id_number").val());
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Ajax连接失败');
                }
            });
        } else{
            Qmsg.error("失败：请检查信息格式！");
        }
    })
    //编辑按钮
    $("#btn_edit_profile").click(function(){
        $(".show_profile").hide();
        $(".edit_profile").show();
        $("#btn_edit_profile").hide();
        $("#btn_profile_submit_cancel").show();
        //编辑时附带原本信息
        $("#edit_name").val($("#name").html());
        $("#edit_gender").val($("#gender").html());
        $("#edit_phone_number").val($("#phone_number").html());
        $("#edit_id_number").val($("#id_number").html());
    });
    //取消编辑按钮
    $("#btn_cancel_profile").click(function(){
        $(".edit_profile").hide();
        $("#btn_profile_submit_cancel").hide();
        $(".show_profile").show();
        $("#btn_edit_profile").show();
    });
});
//校验手机号
function phoneNumCheck(){
    var text= $("#edit_phone_number").val();
    //这个正则表达式可以匹配以1开头，第二位为3-9之间的数字，后面跟着9个数字的手机号码。
    var re = /^1[3456789]\d{9}$/;
    if(re.test(text) || !text) {
        $("#phoneNumCheckSpan").html("");
        return true;
    }else {
        $("#phoneNumCheckSpan").html("手机号格式不正确！");
        return false;
    }
}
//校验身份证号, 由于身份证号可不填，要求text为空时同样可以通过
function idNumCheck(){
    var text= $("#edit_id_number").val();
    var re18 = /^([1-6][1-9]|50)\d{4}(18|19|20)\d{2}((0[1-9])|10|11|12)(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/;
    var re15 =  /^([1-6][1-9]|50)\d{4}\d{2}((0[1-9])|10|11|12)(([0-2][1-9])|10|20|30|31)\d{3}$/;
    if(re18.test(text) || re15.test(text) || !text) {
        $("#idNumCheckSpan").html("");
        return true;
    } else {
        $("#idNumCheckSpan").html("身份证号格式不正确！");
        return false;
    }
}
