if ($.cookie('token')){
    console.log("已登录");
    logined = true;
    $(".loginTrue").show();
} else{
    logined = false;
    if (window.location.pathname != '/'){
        console.log("查询失败，请先登录！");
    }
    $(".loginFalse").show();
}
function logOut(){
    if ($.removeCookie('token', {path: '/'})){
        logined = false;
        window.location.href = '/';
    }
}