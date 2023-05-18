/********* main.js *********/

/********** main *********/

loadPage();

//监听URL更新
window.addEventListener("hashchange", Refresh);

//侧边栏聚焦变色
$('.nav-pills .nav-link').hover(function() {
    $(this).addClass('active');
}, function() {
    if (!$(this).is($(".fix"))) {
        $(this).removeClass('active');
    }
});

//侧边栏折叠
$("#sidebarCollapse").click(function(){
    $('.side_collapse').toggle();
    $(".toggle-width").toggleClass('sidebar-icon-only');
});

/********* 函数定义 **********/
function Refresh(){
    location.reload();
}
function loadPage(){
    var login_status = getLoginStatus();
    if (login_status){
        $(".loginTrue").show();
        if ($("#include").length){
            location.reload();
        } else{
            $(".holyGrail-content").html("");
            if (login_status=='user') {
                switch(window.location.hash.replace(/\?.*/, '')){
                    case "#home": pathn = "user_center.html"; pathjs = "../component/user/js/userHome.js"; i = 0; break;
                    case "#info": pathn = "../component/user/userInfo.html"; pathjs = "../component/user/js/userInfo.js"; i = 1; break;
                    case "#patientlist": pathn = "../component/user/patientList.html"; pathjs = "../component/user/js/patientList.js"; i = 2; break;
                    case "#patientdata": pathn = "../component/user/patientData.html"; pathjs = "../component/user/js/patientData.js"; i = 2; break;
                    default: pathn = "user_center.html"; pathjs = "../component/user/userInfo.js"; i = 0; break;
                }
            } else if (login_status=='doctor') {
                switch(window.location.hash.replace(/\?.*/, '')){
                    case "#userhome": pathn = "user_center.html"; pathjs = "../component/js/userHome.js"; i = 0; break;
                    case "#info": pathn = "../component/doctor/doctorInfo.html"; pathjs = "../component/doctor/js/userInfo.js"; i = 1; break;
                    case "#patientlist": pathn = "../component/doctor/patientList.html"; pathjs = "../component/doctor/js/patientList.js"; i = 2; break;
                    case "#patientdata": pathn = "../component/doctor/patientData.html"; pathjs = "../component/doctor/js/patientData.js"; i = 2; break;
                    default: pathn = "user_center.html"; pathjs = "../component/doctor/userInfo.js"; i = 0; break;
                }
            }
            $(".holyGrail-content").load(pathn); //加载相对应的内容
            // 给侧边栏添加 active 类
            $('.nav-pills .nav-item').eq(i).find('.nav-link').addClass('active fix');
            loadJS(pathjs, function() {});
        }
    } else{
        $(".loginFalse").show();
        if (!window.location.path || window.location.path!="/"){
            Qmsg.error("请先登录！");
        }
    }
}
function loadJS(url, callback=null) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.id = "include";

    if (script.readyState) { // IE
        script.onreadystatechange = function () {
            if (script.readyState == 'loaded' || script.readyState == 'complete') {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else { // Others
        script.onload = function () {
            callback();
        };
    }

    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}