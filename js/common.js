function getLoginStatus(){
    $.ajax({
        url: '../php/getLoginStatus.php',
        method: 'POST',
        async: false,
        data: 'Request=getStatus',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            if (response['data']=='Login'){
                login = true;
            }else if (response['data']=='LogOut'){
                login = false
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
    return login;
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