$(document).ready(function(){
    //查询用户账户信息
    $.ajax({
        url: '/php/getUserAccount.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            $("#userName").html(response['userName']);
            $("#userEmail").html(response['userEmail'])
        },
        error: function(xhr, status, error) {
            console.log('Ajax连接失败');
        }
    });
});
