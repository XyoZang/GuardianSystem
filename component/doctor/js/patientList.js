//查询用户所绑定的监测人信息
updatePatient();
//添加监测人表单提交
// $('#addPatientBtn').click(function() {
//     if (phoneNumCheck('#phone_number',true) && idNumCheck('#id_number',false)){
//         $.ajax({
//             url: '../php/editPatientProfile.php',
//             method: 'POST',
//             data: 'Request=Insert&'+$('#formAddPatient').serialize(),
//             dataType: 'json',
//             success: function(response) {
//                 // 处理服务器返回的数据
//                 console.log(response);
//                 Msg(response, function() {
//                     $("#btnAddPatientCancel").click();
//                     updatePatient();
//                 }, function() {
//                     if (response['log']=='Continue') {
//                         $("#ContinueInfo").show();
//                     }
//                 });
//             },
//             error: function(xhr, status, error) {
//                 console.log('连接失败');
//             }
//         });
//     } else {
//         Qmsg.error("错误：请检查所填信息！");
//     }
// });
$("#phone_number").blur(function(){
    phoneNumCheck(this, true);
});
$("#id_number").blur(function(){
    idNumCheck(this, false);
});
//查询用户所绑定的监测人信息
function updatePatient(){
    $.ajax({
        url: '../php/getPatientList.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            $("#patientList").html("");
            Msg(response, function() {
                response['data'].forEach(function(element, index) {
                    var newHTML = '<tr><td>'+(index+1)+'</td><td>'+element['name']+'</td><td>'+element['age']+
                    '</td><td><a type="button" class="btn btn-primary btnList" href="javascript:void(0);" onclick="toShowPage('+(index+1)+')">查看</a></td></tr>';
                    $("#patientList").append(newHTML);
                });
            });
        },
        error: function(xhr, status, error) {
            console.log('Ajax连接失败');
        }
    });
}
//传参并转至病人数据展示
function toShowPage(index){
    window.location.href = '../data/#patientdata?pindex='+index;
}