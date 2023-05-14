$(document).ready(function(){
    $.ajax({
        url: '../php/getPatientData.php',
        method: 'POST',
        data: 'pindex='+getQueryVariable('pindex'),
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
    // //定义一个函数，用于获取并更新数据
    // function updateData() {
    //     $.ajax({
    //         url: 'php/show.php', // 替换为你的 PHP 后端地址
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

    // // 模拟数据
    // var data = [];
    // for (var i = 0; i < 100; i++) {
    //     data.push(Math.random() * 10 - 5);
    // }
    // // 模拟数据更新
    // setInterval(function () {
    //     for (var i = 0; i < 10; i++) {
    //         data.shift();
    //         data.push(Math.random() * 10 - 5);
    //     }
    //     eegChart.setOption({
    //         series: [
    //             {
    //                 data: data
    //             }
    //         ]
    //     });
    // }, 300);

    // // 绘制脑电图表
    // var eegChart = echarts.init(document.getElementById('eeg-chart'), null, {
    //     width: 600,
    //     height: 400
    // });
    // // 绘制脉搏图表
    // var pulseChart = echarts.init(document.getElementById('pulse-chart'), null, {
    //     width: 600,
    //     height: 400
    // });
    // // 绘制心电图表
    // var ecgChart = echarts.init(document.getElementById('ecg-chart'), null, {
    //     width: 600,
    //     height: 400
    // });
    // // 绘制血压图表
    // var bpChart = echarts.init(document.getElementById('bp-chart'), null, {
    //     width: 600,
    //     height: 400
    // });
    // // 绘制血氧图表
    // var spo2Chart = echarts.init(document.getElementById('spo2-chart'), null, {
    //     width: 600,
    //     height: 400
    // });

    // // 指定图表的配置项和数据

    // // 脑电图配置
    // var eegOption = {
    //     color: [
    //         '#5470c6',
    //     ],
    //     title: {
    //         text: '脑电'
    //     },
    //     tooltip: {
    //         show: false,
    //         trigger: 'axis',
    //         axisPointer: {
    //             type: 'line'
    //         }
    //     },
    //     xAxis: {
    //         type: 'category',
    //         boundaryGap: false,
    //         data: []
    //     },
    //     yAxis: {
    //         type: 'value',
    //         axisLine: {
    //             show: false
    //         },
    //         axisTick: {
    //             show: false
    //         },
    //         axisLabel: {
    //             show: false
    //         },
    //         splitLine: {
    //             show: false
    //         }
    //     },
    //     series: [
    //         {
    //             type: 'line',
    //             showSymbol: false,
    //             hoverAnimation: false,
    //             data: data
    //         }
    //     ]
    // };

    // // 脉搏图配置
    // var pulseOption = {
    //     color: [
    //         '#5470c6',
    //     ],
    //     title: {
    //         text: '脉搏'
    //     },
    //     tooltip: {},
    //     legend: {
    //         data:[]
    //     },
    //     xAxis: {
    //         data: []
    //     },
    //     yAxis: {},
    //     series: [{
    //         name: '脉搏',
    //         type: 'line',
    //         data: [5, 20, 36, 10, 10, 20]
    //     }]
    // };

    // //心电图配置
    // var ecgOption = {
    //     color: [
    //         '#5470c6',
    //     ],
    //     title: {
    //         text: '心电'
    //     },
    //     tooltip: {},
    //     legend: {
    //         data:[]
    //     },
    //     xAxis: {
    //         data: []
    //     },
    //     yAxis: {},
    //     series: [{
    //         name: '心电',
    //         type: 'line',
    //         data: [5, 20, 36, 10, 10, 20]
    //     }]
    // };

    // //血压图配置
    // var bpOption = {
    //     color: [
    //         '#5470c6',
    //     ],
    //     title: {
    //         text: '血压'
    //     },
    //     tooltip: {},
    //     legend: {
    //         data:[]
    //     },
    //     xAxis: {
    //         data: []
    //     },
    //     yAxis: {},
    //     series: [{
    //         name: '血压',
    //         type: 'line',
    //         data: [5, 20, 36, 10, 10, 20]
    //     }]
    // };

    // //血氧图配置
    // var spo2Option = {
    //     color: [
    //         '#5470c6',
    //     ],
    //     title: {
    //         text: '血氧'
    //     },
    //     tooltip: {},
    //     legend: {
    //         data:[]
    //     },
    //     xAxis: {
    //         data: []
    //     },
    //     yAxis: {},
    //     series: [{
    //         name: '血氧',
    //         type: 'line',
    //         data: [5, 20, 36, 10, 10, 20]
    //     }]
    // };

    // // 使用刚指定的配置项和数据绘制各个图表。
    // eegChart.setOption(eegOption);
    // pulseChart.setOption(pulseOption);
    // ecgChart.setOption(ecgOption);
    // bpChart.setOption(bpOption);
    // spo2Chart.setOption(spo2Option);

    // // 每两秒调用updateData进行数据更新
    // setInterval(updateData, 2000);
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