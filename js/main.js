main();
//主函数
function main(){
    if ($.cookie('token')){
        console.log("已登录");
        $(".loginFalse").hide();
    } else{
        $(".loginTrue").hide();
    }
}
function logOut(){
    if ($.removeCookie('token', {path: '/'})){
        window.location.href = '/';
    }
}