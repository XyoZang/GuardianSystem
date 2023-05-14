$(document).ready(function(){
    //查询用户所绑定的监测人信息
    $.ajax({
        url: '../php/getPatientList.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            if (response['data']){
                response['data'].forEach(function(element, index) {
                    var newHTML = '<tr><td>'+(index+1)+'</td><td>'+element['name']+'</td><td>'+element['age']+'</td><td>查看 -- 删除</td></tr>';
                    console.log(newHTML);
                    $("#patientList").append(newHTML);
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('Ajax连接失败');
        }
    });
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
                });
            },
            error: function(xhr, status, error) {
                console.log('连接失败');
            }
        });
    });
});
