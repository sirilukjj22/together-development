function get_graphThisWeek($amount) {

    var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueDataThisMonth = "";

    $.ajax({
        type: "GET",
        url: "sms-graph-thisWeek/"+date_now+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 7 days
                revenueDataThisMonth = response.amount;
        }
    });
    return revenueDataThisMonth;
}

function get_graphThisMonth($amount) {

    var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueDataThisMonth = "";

    $.ajax({
        type: "GET",
        url: "sms-graph-thisMonth/"+date_now+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 30 days
            if ($amount == 1) {
                revenueDataThisMonth = response.amount;
            } else {
                revenueDataThisMonth = response.date;
            }
        }
    });
    return revenueDataThisMonth;
}

function get_graphThisMonthByDay($amount) {

    var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueDataThisMonth = "";

    $.ajax({
        type: "GET",
        url: "sms-graph-thisMonthByDay/"+date_now+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 7 days
                revenueDataThisMonth = response.amount;
        }
    });
    return revenueDataThisMonth;
}

function get_graphYearRange($amount, $year) {

    var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueDataThisMonth = "";

    $.ajax({
        type: "GET",
        url: "sms-graph-yearRange/"+$year+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 7 days
                revenueDataThisMonth = response.amount;
        }
    });
    return revenueDataThisMonth;
}

function get_graphMonthRange($month, $to_month) {

    var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueDataByMonth = "";

    $.ajax({
        type: "GET",
        url: "sms-graph-monthRange/"+$month+"/"+$to_month+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 7 days
                revenueDataByMonth = response.amount;
        }
    });
    return revenueDataByMonth;
}

Chart.defaults.font.family = "Sarabun";
var revenueDataThisMonth = get_graphThisMonth(1);

function formatNumberThisMonth(num) {
    if (num >= 1e6) {
        return (num / 1e6).toFixed(1) + "M";
    } else if (num >= 1e3) {
        return (num / 1e3).toFixed(1) + "K";
    }
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function formatFullNumberThisMonth(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function drawRotatedText(ctx, bar, displayData_thisMonth, fontSize) {
    ctx.font = "normal " + (fontSize - 4) + "px Sarabun"; // Adjust font size for longer labelsThisMonth
    ctx.save();
    // Check media width and adjust translation offset accordingly
    var translateOffset_thisMonth = window.innerWidth < 768 ? -10 : -20;
    ctx.translate(bar.x, bar.y + translateOffset_thisMonth);
}
var valueOnTopPluginThisMonth = {
    afterDatasetsDraw: function (chart) {
        var ctx = chart.ctx;
        var fontSize = Math.min(16, Math.max(12, Math.round(chart.width / 50)));
        ctx.font = "normal " + fontSize + "px Sarabun"; // Set font size dynamically
        chart.data.datasets.forEach((dataset, i) => {
            var meta = chart.getDatasetMeta(i);
            meta.data.forEach((bar, index) => {
                var data_thismonth = dataset.data[index];
                let displayData_thisMonth = formatNumberThisMonth(data_thismonth);
                    ctx.font = "normal " + (fontSize - 4) + "px Sarabun"; // Adjust font size for longer labelsThisMonth
                    ctx.save();
                    drawRotatedText(ctx, bar, displayData_thisMonth);
                    ctx.rotate(-Math.PI / 2);
                    ctx.fillStyle = "#000";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.fillText(displayData_thisMonth, 0, 0);
                    ctx.restore();
            });
        });
    },
};

var valueOnTopPluginThisMonthByDay = {
    afterDatasetsDraw: function (chart) {
        var ctx = chart.ctx;
        var fontSize = Math.min(16, Math.max(12, Math.round(chart.width / 50)));
        ctx.font = "normal " + fontSize + "px Sarabun"; // Set font size dynamically
        chart.data.datasets.forEach((dataset, i) => {
            var meta = chart.getDatasetMeta(i);
            meta.data.forEach((bar, index) => {
                var data_thismonth = dataset.data[index];
                let displayData_thisMonth = formatNumberThisMonth(data_thismonth);
                    ctx.font = "normal " + (fontSize - 4) + "px Sarabun"; // Adjust font size for longer labelsThisMonth
                    ctx.save();
                    ctx.translate(bar.x, bar.y - 10);
                    ctx.fillStyle = "#000";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.fillText(displayData_thisMonth, 0, 0);
                    ctx.restore();
            });
        });
    },
};

var ctx_thisMonth = document.getElementById("revenueChartThisMonth").getContext("2d");
var maxRevenueValue_thisMonth = Math.max(...revenueDataThisMonth);
var buffer_thisMonth = 20000; // Adding a buffer value
var yAxisMax_thisMonth = maxRevenueValue_thisMonth + buffer_thisMonth;
var roundingFactor_thisMonth = 20000;
yAxisMax_thisMonth = Math.ceil(yAxisMax_thisMonth / roundingFactor_thisMonth) * roundingFactor_thisMonth;

var revenueChart_thisMonth = new Chart(ctx_thisMonth, {
    type: "bar",
    data: {
        labels: get_graphThisMonth(0),
        datasets: [{
            label: "This Month",
            data: revenueDataThisMonth,
            backgroundColor: "#2C7F7A",
            borderWidth: 0,
            barPercentage: 0.7,
        },],
    },
    options: {
        scales: {
            x: {},
            y: {
                beginAtZero: true,
                max: yAxisMax_thisMonth,
                ticks: {
                    stepSize: 20000,
                    callback: function (value) {
                        return formatNumberThisMonth(value);
                    },
                },
            },
        },
    },
    plugins: [valueOnTopPluginThisMonth],
});

function chartThisWeek() {

    $('#revenueChart').prop('hidden', true);
    $('#revenueChartThisMonth').prop('hidden', false);
    $('#revenueChartCustom').prop('hidden', true);
    $('#btn-close-myModalGraph').click();
    
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_thisMonth, {
        type: "bar",
        data: {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: "This Week",
                data: get_graphThisWeek(1),
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_thisMonth,
                    ticks: {
                        stepSize: 20000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonthByDay],
    });
}

function chartThisMonth() {

    $('#revenueChart').prop('hidden', true);
    $('#revenueChartThisMonth').prop('hidden', false);
    $('#revenueChartCustom').prop('hidden', true);
    $('#btn-close-myModalGraph').click();
    
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_thisMonth, {
        type: "bar",
        data: {
            labels: get_graphThisMonth(0),
            datasets: [{
                label: "This Month",
                data: revenueDataThisMonth,
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_thisMonth,
                    ticks: {
                        stepSize: 20000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonth],
    });
}

function chartThisMonthByDay($year) {

    $('#revenueChart').prop('hidden', true);
    $('#revenueChartThisMonth').prop('hidden', false);
    $('#revenueChartCustom').prop('hidden', true);
    $('#btn-close-myModalGraph').click();

    var maxRevenueValue_thisByDay = Math.max(...get_graphThisMonthByDay(1));
    var buffer_thisByDay = 50000; // Adding a buffer value
    var yAxisMax_thisByDay = maxRevenueValue_thisByDay + buffer_thisByDay;
    var roundingFactor_thisByDay = 50000;
    yAxisMax_thisByDay = Math.ceil(yAxisMax_thisByDay / roundingFactor_thisByDay) * roundingFactor_thisByDay;
    
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_thisMonth, {
        type: "bar",
        data: {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: "Monthly Average By Days",
                data: get_graphThisMonthByDay(1),
                backgroundColor: "#2C7F7A",
                borderWidth: 1,
                barPercentage: 0.55,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_thisByDay,
                    ticks: {
                        stepSize: 50000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonthByDay],
    });
}

function chartYearRange($v_year) {
    
    $('#revenueChart').prop('hidden', true);
    $('#revenueChartThisMonth').prop('hidden', false);
    $('#revenueChartCustom').prop('hidden', true);
    $('#btn-close-myModalGraph').click();

    // var revenueDataThisMonth = get_graphThisMonthByDay(1);

    var maxRevenueValue_yearRange = Math.max(...get_graphYearRange(1, $v_year));
    var buffer_yearRange = 200000; // Adding a buffer value
    var yAxisMax_yearRange = maxRevenueValue_yearRange + buffer_yearRange;
    var roundingFactor_yearRange = 200000;
    yAxisMax_yearRange = Math.ceil(yAxisMax_yearRange / roundingFactor_yearRange) * roundingFactor_yearRange;
    
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_thisMonth, {
        type: "bar",
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: "Custom Year Range",
                data: get_graphYearRange(1, $v_year),
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_yearRange,
                    ticks: {
                        stepSize: 200000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonthByDay],
    });

}

function chartFilterByYear($v_year) {

    var ctx_monthRange = document.getElementById("revenueChartByMonthOrYear").getContext("2d");
    var maxRevenueValue_yearRange = Math.max(...get_graphYearRange(1, $v_year));
    var buffer_yearRange = 200000; // Adding a buffer value
    var yAxisMax_yearRange = maxRevenueValue_yearRange + buffer_yearRange;
    var roundingFactor_yearRange = 200000;
    yAxisMax_yearRange = Math.ceil(yAxisMax_yearRange / roundingFactor_yearRange) * roundingFactor_yearRange;
    
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_monthRange, {
        type: "bar",
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: "Custom Year Range",
                data: get_graphYearRange(1, $v_year),
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_yearRange,
                    ticks: {
                        stepSize: 200000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonthByDay],
    });

}

function chartMonthToMonth($month, $to_month) {

    var ctx_monthRange = document.getElementById("revenueChartByMonthOrYear").getContext("2d");
    var maxRevenueValue_monthRange = Math.max(...get_graphMonthRange($month, $to_month));
    var buffer_monthRange = 200000; // Adding a buffer value
    var yAxisMax_monthRange = maxRevenueValue_monthRange + buffer_monthRange;
    var roundingFactor_monthRange = 200000;
    yAxisMax_monthRange = Math.ceil(yAxisMax_monthRange / roundingFactor_monthRange) * roundingFactor_monthRange;
    
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_monthRange = new Chart(ctx_monthRange, {
        type: "bar",
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: "Custom Month Range",
                data: get_graphMonthRange($month, $to_month),
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_monthRange,
                    ticks: {
                        stepSize: 200000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonthByDay],
    });
}

function chartWeek() {

    $('#revenueChart').prop('hidden', true);
    $('#revenueChartThisMonth').prop('hidden', false);
    $('#revenueChartCustom').prop('hidden', true);
    $('#btn-close-myModalGraph').click();
    
    var ctx_monthRange = document.getElementById("revenueChartByMonthOrYear").getContext("2d");
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisWeek = new Chart(ctx_monthRange, {
        type: "bar",
        data: {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: "This Week",
                data: get_graphThisWeek(1),
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_thisMonth,
                    ticks: {
                        stepSize: 20000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonthByDay],
    });
}

function chartThisMonth2() {

    $('#revenueChart').prop('hidden', true);
    $('#revenueChartThisMonth').prop('hidden', false);
    $('#revenueChartCustom').prop('hidden', true);
    $('#btn-close-myModalGraph').click();
    
    var ctx_monthRange = document.getElementById("revenueChartByMonthOrYear").getContext("2d");
    revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_monthRange, {
        type: "bar",
        data: {
            labels: get_graphThisMonth(0),
            datasets: [{
                label: "This Month",
                data: revenueDataThisMonth,
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            },],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax_thisMonth,
                    ticks: {
                        stepSize: 20000,
                        callback: function (value) {
                            return formatNumberThisMonth(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPluginThisMonth],
    });
}

