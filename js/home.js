$(".edit_profile").hide();
$(document).ready(function(){
    //查询用户账户信息
    $.ajax({
        url: '/php/getUserInfo.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            $("#userName").html(response['userName']);
            $("#userEmail").html(response['userEmail']);
            $("#name").html(response['name']);
            $("#gender").html(response['gender']);
            $("#phone_number").html(response['phone_number']);
            $("#id_number").html(response['id_number']);
        },
        error: function(xhr, status, error) {
            console.log('Ajax连接失败');
        }
    });
    $("#btn_edit_profile").click(function(){
        $(".show_profile").hide();
        $(".edit_profile").show();
        $("#btn_profile_submit_cancel").show();
    });
    $("#btn_cancel_profile").click(function(){
        $(".edit_profile").hide();
        $("#btn_profile_submit_cancel").hide();
        $(".show_profile").show();
    })
    // ^1[3456789]\d{9}$ 这个正则表达式可以匹配以1开头，第二位为3-9之间的数字，后面跟着9个数字的手机号码。
    $("#btn_submit_profile").click(function(){
        $.ajax({
            url: '/php/editUserProfile.php',
            method: 'POST',
            data: $('#formEditProfile').serialize(),
            dataType: 'json',
            success: function(response) {
                // 处理服务器返回的数据
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log('Ajax连接失败');
            }
        });
    })
});
