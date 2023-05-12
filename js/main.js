if ($.cookie('token')){
    console.log("已登录");
    $(".loginTrue").show();
} else{
    $(".loginFalse").show();
}
function logOut(){
    if ($.removeCookie('token', {path: '/'})){
        window.location.href = '/';
    }
}