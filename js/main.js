if ($.cookie('token')){
    console.log("已登录");
    logined = true;
    $(".loginTrue").show();
} else{
    logined = false;
    if (window.location.pathname != '/'){
        console.log("查询失败，请先登录！");
        Qmsg.error("失败：请先登录！");
    }
    $(".loginFalse").show();
}
function logOut(){
    if ($.removeCookie('token', {path: '/'})){
        logined = false;
        window.location.href = '/';
    }
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
    $('.nav-pills .nav-link').hover(function() {
        $(this).addClass('active');
    }, function() {
        if (!$(this).is($(".fix"))) {
            $(this).removeClass('active');
        }
    });
});