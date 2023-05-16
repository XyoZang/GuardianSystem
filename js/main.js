/********* main.js *********/

/********** main *********/

var login;
loadPage();

// 监听URL更新
window.addEventListener("hashchange", Refresh);

// 地址栏聚焦变色
$('.nav-pills .nav-link').hover(function() {
    $(this).addClass('active');
}, function() {
    if (!$(this).is($(".fix"))) {
        $(this).removeClass('active');
    }
});

/********* 函数定义 **********/
function Refresh(){
    location.reload();
}
function loadPage(){
    if (getLoginStatus()){
        $(".loginTrue").show();
        console.log("loadPage");
        if ($("#include").length){
            location.reload();
        } else{
            $(".holyGrail-content").html("");
            switch(window.location.hash.replace(/\?.*/, '')){
                case "#userhome": pathn = "user_center.html"; pathjs = "../component/js/userHome.js"; i = 0; break;
                case "#userinfo": pathn = "../component/userInfo.html"; pathjs = "../component/js/userInfo.js"; i = 1; break;
                case "#patientlist": pathn = "../component/patientList.html"; pathjs = "../component/js/patientList.js"; i = 2; break;
                case "#patientdata": pathn = "../component/patientData.html"; pathjs = "../component/js/patientData.js"; i = 2; break;
                default: pathn = "user_center.html"; pathjs = "../component/userInfo.js"; i = 0; break;
            }
            $(".holyGrail-content").load(pathn); //加载相对应的内容
            // 给侧边栏添加 active 类
            $('.nav-pills .nav-item').eq(i).find('.nav-link').addClass('active fix');
            loadJS(pathjs, function() {
                console.log("loadJS");
            });
        }
    } else{
        $(".loginFalse").show();
        if (!window.location.path || window.location.path!="/"){
            Qmsg.error("请先登录！");
        }
    }
}
function loadJS(url, callback) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.id = "include";
  
    if (script.readyState) { // IE
      script.onreadystatechange = function() {
        if (script.readyState == 'loaded' || script.readyState == 'complete') {
          script.onreadystatechange = null;
          callback();
        }
      };
    } else { // Others
      script.onload = function() {
        callback();
      };
    }
  
    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}
function toggleSide(){
    $('.side_collapse').toggle();
    $(".toggle-width").toggleClass('sidebar-icon-only');
}