$(document).ready(function(){
    $.ajax({
        url: '../php/getPatientData.php',
        method: 'POST',
        data: window.location.search.substring(1),
        dataType: 'json',
        success: function(response) {
            // 处理服务器返回的数据
            console.log(response);
            Msg(response, function(){
                $("#name").html(response['data']['name']);
                $("#gender").html(response['data']['gender']);
                $("#age").html(response['data']['age']);
                $("#id_number").html(response['data']['id_number']);
                $("#phone_number").html(response['data']['phone_number']);
            });
        },
        error: function(xhr, status, error) {
            console.log('连接失败');
        }
    });
    $("#btn_edit_profile").click(function(){
        $(".show_profile").hide();
        $(".edit_profile").show();
        $("#btn_edit_profile").hide();
        $("#btn_profile_submit_cancel").show();
        //编辑时附带原本信息
        $("#edit_name").val($("#name").html());
        $("#edit_gender").val($("#gender").html());
        $("#edit_age").val($("#age").html());
        $("#edit_phone_number").val($("#phone_number").html());
        $("#edit_id_number").val($("#id_number").html());
    });
    $("#btn_cancel_profile").click(function(){
        $(".edit_profile").hide();
        $("#btn_profile_submit_cancel").hide();
        $(".show_profile").show();
        $("#btn_edit_profile").show();
    });
    //提交用户修改信息
    $("#btn_submit_patient_profile").click(function(){
        if (phoneNumCheck()){
            $.ajax({
                url: '../php/editPatientProfile.php',
                method: 'POST',
                data: 'Request=Edit&'+window.location.search.substring(1)+'&'+$('#formEditPatientProfile').serialize(),
                dataType: 'json',
                success: function(response) {
                    // 处理服务器返回的数据
                    console.log(response);
                    Msg(response, function() {
                        $("#btn_cancel_profile").click();
                        $("#name").html($("#edit_name").val());
                        $("#age").html($("#edit_age").val());
                        $("#gender").html($("#edit_gender").val());
                        $("#phone_number").html($("#edit_phone_number").val());
                        $("#id_number").html($("#edit_id_number").val());
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Ajax连接失败');
                }
            });
        } else{
            Qmsg.error("失败：请检查信息格式！");
        }
    })
});
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if (pair[0] == variable) {
        return pair[1];
      }
    }
    return false;
}
//校验手机号
function phoneNumCheck(){
    var text= $("#edit_phone_number").val();
    //这个正则表达式可以匹配以1开头，第二位为3-9之间的数字，后面跟着9个数字的手机号码。
    var re = /^1[3456789]\d{9}$/;
    if(re.test(text) || !text) {
        $("#phoneNumCheckSpan").html("");
        return true;
    }else {
        $("#phoneNumCheckSpan").html("手机号格式不正确！");
        return false;
    }
}
//定义一个函数，用于获取并更新数据
// function updateData() {
//     $.ajax({
//         url: '../php/show.php', // 替换为你的 PHP 后端地址
//         method: 'GET',
//         dataType: 'json',
//         success: function(response) {
//             eegChart.setOption({
//                 xAxis: {},
//                 series: [
//                 {
//                     name: '脑电',
//                     data: response['脑电数据']
//                 }
//                 ]
//             });

//         },
//         error: function(xhr, status, error) {
//             console.log('获取数据失败');
//         }
//     });
// }

// 模拟数据
var data = [];
for (var i = 0; i < 100; i++) {
    data.push(Math.random() * 10 - 5);
}
// 模拟数据更新
setInterval(function () {
    for (var i = 0; i < 10; i++) {
        data.shift();
        data.push(Math.random() * 10 - 5);
    }
    eegChart.setOption({
        series: [
            {
                data: data
            }
        ]
    });
}, 300);
console.log("断点");
// 绘制脑电图表
var eegChart = echarts.init(document.getElementById('eeg-chart'), null, {
    width: 600,
    height: 400
});
// 绘制脉搏图表
var pulseChart = echarts.init(document.getElementById('pulse-chart'), null, {
    width: 600,
    height: 400
});
// 绘制心电图表
var ecgChart = echarts.init(document.getElementById('ecg-chart'), null, {
    width: 600,
    height: 400
});
// 绘制血压图表
var bpChart = echarts.init(document.getElementById('bp-chart'), null, {
    width: 600,
    height: 400
});
// 绘制血氧图表
var spo2Chart = echarts.init(document.getElementById('spo2-chart'), null, {
    width: 600,
    height: 400
});

// 指定图表的配置项和数据

// 脑电图配置
var eegOption = {
    color: [
        '#5470c6',
    ],
    title: {
        text: '脑电'
    },
    tooltip: {
        show: false,
        trigger: 'axis',
        axisPointer: {
            type: 'line'
        }
    },
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: []
    },
    yAxis: {
        type: 'value',
        axisLine: {
            show: false
        },
        axisTick: {
            show: false
        },
        axisLabel: {
            show: false
        },
        splitLine: {
            show: false
        }
    },
    series: [
        {
            type: 'line',
            showSymbol: false,
            hoverAnimation: false,
            data: data
        }
    ]
};

// 脉搏图配置
var pulseOption = {
    color: [
        '#5470c6',
    ],
    title: {
        text: '脉搏'
    },
    tooltip: {},
    legend: {
        data:[]
    },
    xAxis: {
        data: []
    },
    yAxis: {},
    series: [{
        name: '脉搏',
        type: 'line',
        data: [25, 20, 40, 8, 23, 15,26,10,30,7]
    }]
};

//心电图配置
var ecgOption = {
    color: [
        '#5470c6',
    ],
    title: {
        text: '心电'
    },
    tooltip: {},
    legend: {
        data:[]
    },
    xAxis: {
        data: []
    },
    yAxis: {},
    series: [{
        name: '心电',
        type: 'line',
        data: [5, 20, 36, 10, 10, 20]
    }]
};

//血压图配置
var bpOption = {
    color: [
        '#5470c6',
    ],
    title: {
        text: '血压'
    },
    tooltip: {},
    legend: {
        data:[]
    },
    xAxis: {
        data: []
    },
    yAxis: {},
    series: [{
        name: '血压',
        type: 'line',
        data: [5, 20, 36, 10, 10, 20]
    }]
};

//血氧图配置
var spo2Option = {
    color: [
        '#5470c6',
    ],
    title: {
        text: '血氧'
    },
    tooltip: {},
    legend: {
        data:[]
    },
    xAxis: {
        data: []
    },
    yAxis: {},
    series: [{
        name: '血氧',
        type: 'line',
        data: [5, 20, 36, 10, 10, 20]
    }]
};

// 使用刚指定的配置项和数据绘制各个图表。
eegChart.setOption(eegOption);
pulseChart.setOption(pulseOption);
ecgChart.setOption(ecgOption);
bpChart.setOption(bpOption);
spo2Chart.setOption(spo2Option);