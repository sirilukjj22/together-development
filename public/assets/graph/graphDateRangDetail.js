function get_graphDateRang($startDate, $endDate, $amount) {

    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueDataDateRang = "";

    $.ajax({
        type: "GET",
        url: "/sms-graph-daterang/"+$startDate+"/"+$endDate+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 30 days
            if ($amount == 1) {
                revenueDataDateRang = response.amount;
            } else {
                revenueDataDateRang = response.date;
            }
        }
    });
    
    return revenueDataDateRang;
}

Chart.defaults.font.family = "Sarabun";

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

function chartDateRang(startDate, endDate) {

    var revenueDataDateRang = get_graphDateRang(startDate, endDate, 1);
    
    var ctx_dateRange = document.getElementById("revenueChartByMonthOrYear").getContext("2d");
    // revenueChart_thisMonth.destroy(); // Destroy the current chart
    revenueChart_thisMonth = new Chart(ctx_dateRange, {
        type: "bar",
        data: {
            labels: get_graphDateRang(startDate, endDate, 0),
            datasets: [{
                label: moment(startDate).format('DD MMMM YYYY')+" - "+moment(endDate).format('DD MMMM YYYY'),
                data: revenueDataDateRang,
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

