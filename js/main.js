function getLoginStatus(){
    $.ajax({
        url: '../php/getLoginStatus.php',
        method: 'POST',
        data: 'Request=getStatus',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            if (response['data']=='Login'){
                $(".loginTrue").show();
                console.log("已登录");
            }else if (response['data']=='LogOut'){
                $(".loginFalse").show();
                console.log("请先登录！");
                if (window.location.pathname != '/'){
                    Qmsg.error("失败：请先登录！");
                }
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function logOut(){
    $.ajax({
        url: '../php/getLoginStatus.php',
        method: 'POST',
        data: 'Request=LogOut',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            Msg(response, function() {
                $.removeCookie('token', {path: '/'});
                window.location.href = '/';
            });
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function Msg(response, successFunc){
    if (response["status"]=="Success"){
        Qmsg.success("提示："+response["msg"]);
        successFunc();
    }else if(response["status"]=="Failed"){
        Qmsg.error("失败："+response["msg"]);
    }
}
function toggleSide(){
    $('.side_collapse').toggle();
    $(".toggle-width").toggleClass('sidebar-icon-only');
}
$(document).ready(function() {
    getLoginStatus();
    $('.nav-pills .nav-link').hover(function() {
        $(this).addClass('active');
    }, function() {
        if (!$(this).is($(".fix"))) {
            $(this).removeClass('active');
        }
    });
});