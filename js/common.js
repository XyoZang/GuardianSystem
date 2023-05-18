function getLoginStatus(){
    $.ajax({
        url: '../php/getAccount.php',
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
function Msg(response, successFunc=null, failFunc=null){
    if (response["status"]=="Success"){
        Qmsg.success("提示："+response["msg"]);
        if (successFunc!=null) {
            successFunc();
        }
    }else if(response["status"]=="Failed"){
        if (response["type"]=="Warning"){
            Qmsg.warning("提示："+response["msg"]);
        } else{
            Qmsg.error("失败："+response["msg"]);
        }
        if (failFunc!=null) {
            failFunc();
        }
    }
}
//校验手机号, isNULL为true则代表可为空, 默认不可为空
function phoneNumCheck(dom, isNULL=false) {
    var dom = $(dom);
    var textSpan = dom.next("span");
    var text= dom.val();
    //这个正则表达式可以匹配以1开头，第二位为3-9之间的数字，后面跟着9个数字的手机号码。
    var re = /^1[3456789]\d{9}$/;
    var isNULL = isNULL && !text;
    if(re.test(text) || isNULL) {
        textSpan.html("");
        return true;
    }else {
        textSpan.html("手机号格式不正确！");
        return false;
    }
}
//校验身份证号, isNULL为true则代表可为空
function idNumCheck(dom, isNULL=false) {
    var dom = $(dom);
    var textSpan = dom.next("span");
    var text= dom.val();
    var re18 = /^([1-6][1-9]|50)\d{4}(18|19|20)\d{2}((0[1-9])|10|11|12)(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/;
    var re15 =  /^([1-6][1-9]|50)\d{4}\d{2}((0[1-9])|10|11|12)(([0-2][1-9])|10|20|30|31)\d{3}$/;
    var isNULL = isNULL && !text;
    if(re18.test(text) || re15.test(text) || isNULL) {
        textSpan.html("");
        return true;
    } else {
        textSpan.html("身份证号格式不正确！");
        return false;
    }
}