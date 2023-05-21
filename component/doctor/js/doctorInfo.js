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
            $("#age").html(response['data']['age']);
            $("#title").html(response['data']['title']);
            $("#department").html(response['data']['department']);
            $("#phone_number").html(response['data']['phone_number']);
            $("#institutionName").html(response['data']['institutionName']);
        });
    },
    error: function(xhr, status, error) {
        console.log('Ajax连接失败');
    }
});
// //提交用户修改信息
// $("#btn_submit_profile").click(function(){
//     if (phoneNumCheck('#edit_phone_number',true) && idNumCheck('#edit_id_number',true)){
//         $.ajax({
//             url: '../php/editUserProfile.php',
//             method: 'POST',
//             data: $('#formEditProfile').serialize(),
//             dataType: 'json',
//             success: function(response) {
//                 // 处理服务器返回的数据
//                 console.log(response);
//                 Msg(response, function() {
//                     $("#btn_cancel_profile").click();
//                     $("#name").html($("#edit_name").val());
//                     $("#gender").html($("#edit_gender").val());
//                     $("#phone_number").html($("#edit_phone_number").val());
//                     $("#id_number").html($("#edit_id_number").val());
//                 });
//             },
//             error: function(xhr, status, error) {
//                 console.log('Ajax连接失败');
//             }
//         });
//     } else{
//         Qmsg.error("失败：请检查信息格式！");
//     }
// })
// //编辑按钮
// $("#btn_edit_profile").click(function(){
//     $(".show_profile").hide();
//     $(".edit_profile").show();
//     $("#btn_edit_profile").hide();
//     $("#btn_profile_submit_cancel").show();
//     //编辑时附带原本信息
//     $("#edit_name").val($("#name").html());
//     $("#edit_gender").val($("#gender").html());
//     $("#edit_phone_number").val($("#phone_number").html());
//     $("#edit_id_number").val($("#id_number").html());
// });
// //取消编辑按钮
// $("#btn_cancel_profile").click(function(){
//     $(".edit_profile").hide();
//     $("#btn_profile_submit_cancel").hide();
//     $(".show_profile").show();
//     $("#btn_edit_profile").show();
// });

