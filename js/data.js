$(document).ready(function(){
    updatePatient();
    //添加监测人表单提交
    $('#addPatientBtn').click(function() {
        $.ajax({
            url: '../php/editPatientProfile.php',
            method: 'POST',
            data: $('#formAddPatient').serialize(),
            dataType: 'json',
            success: function(response) {
                // 处理服务器返回的数据
                console.log(response);
                Msg(response, function() {
                    $("#btnAddPatientCancel").click();
                    updatePatient();
                });
            },
            error: function(xhr, status, error) {
                console.log('连接失败');
            }
        });
    });
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
            if (response['data']){
                $("#patientList").html("");
                response['data'].forEach(function(element, index) {
                    var newHTML = '<tr><td>'+(index+1)+'</td><td>'+element['name']+'</td><td>'+element['age']+
                    '</td><td><a type="button" class="btn btn-primary btnList" href="javascript:void(0);" onclick="toShowPage('+(index+1)+')">查看</a><a type="button" class="btn btn-danger" href="javascript:void(0);" onclick="deletePatient('+(index+1)+')">删除</a></td></tr>';
                    $("#patientList").append(newHTML);
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('Ajax连接失败');
        }
    });
}
function toShowPage(index){
    window.location.href = './data.html?pindex='+index;
}
function deletePatient(index){
    $.ajax({
        url: '../php/getPatientData.php',
        method: 'POST',
        data: 'Request=delete&pindex='+index,
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            Msg(response, function() {
                updatePatient();
            });
        },
        error: function(xhr, status, error) {
            console.log('连接失败');
        }
    });
}