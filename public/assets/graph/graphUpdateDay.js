function get_graph() {

    var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
    var type = $('#status').val();
    var account = $('#into_account').val();

    if (type == '') {
        type = 0;
    }

    if (account == '') {
        account = 0;
    }

    var revenueData = "";

    $.ajax({
        type: "GET",
        url: "sms-graph30days/"+date_now+"/"+type+"/"+account+"",
        datatype: "JSON",
        async: false,
        success: function(response) {
            // Sample data for watch revenue over 30 days
            revenueData = response.amount;
            
            
        }
    });
    return revenueData;
}

Chart.defaults.font.family = "Sarabun";
var revenueData = get_graph();
var today = new Date();
var labels = [];
for (var i = 29; i >= 0; i--) {
    var date_days = new Date(today);
    date_days.setDate(today.getDate() - i);
    var month = date_days.getMonth() + 1;
    var day = date_days.getDate();
    labels.push(`${month}/${day}`);
}

function formatNumber(num) {
    if (num >= 1e6) {
        return (num / 1e6).toFixed(1) + "M";
    } else if (num >= 1e3) {
        return (num / 1e3).toFixed(1) + "K";
    }
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function formatFullNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function drawRotatedText(ctx, bar, displayData, fontSize) {
    ctx.font = "normal " + (fontSize - 4) + "px Sarabun"; // Adjust font size for longer labels
    ctx.save();
    // Check media width and adjust translation offset accordingly
    var translateOffset = window.innerWidth < 768 ? -10 : -20;
    ctx.translate(bar.x, bar.y + translateOffset);
}
var valueOnTopPlugin = {
    afterDatasetsDraw: function (chart) {
        var ctx = chart.ctx;
        var fontSize = Math.min(16, Math.max(10, Math.round(chart.width / 50)));
        ctx.font = "normal " + fontSize + "px Sarabun"; // Set font size dynamically
        chart.data.datasets.forEach((dataset, i) => {
            var meta = chart.getDatasetMeta(i);
            meta.data.forEach((bar, index) => {
                var data = dataset.data[index];
                var displayData = formatNumber(data);
                if (chart.data.labels.length === 7) {
                    displayData = formatNumber(data);
                    ctx.save();
                    ctx.translate(bar.x, bar.y - 10);
                    ctx.fillStyle = "#000";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.fillText(displayData, 0, 0);
                    ctx.restore();
                } else if (chart.data.labels.length === 15) {
                    ctx.font = "normal " + (fontSize - 4) +
                        "px Sarabun"; // Adjust font size for longer labels
                    ctx.fillStyle = "#000";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.save();
                    ctx.fillText(displayData, bar.x, bar.y - 10);
                    ctx.restore();
                } else if (chart.data.labels.length === 30) {
                    ctx.font = "normal " + (fontSize - 4) +
                        "px Sarabun"; // Adjust font size for longer labels
                    ctx.save();
                    drawRotatedText(ctx, bar, displayData);
                    ctx.rotate(-Math.PI / 2);
                    ctx.fillStyle = "#000";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.fillText(displayData, 0, 0);
                    ctx.restore();
                    return;
                }
            });
        });
    },
};

var ctx = document.getElementById("revenueChart").getContext("2d");
var maxRevenueValue = Math.max(...revenueData);
var buffer = 20000; // Adding a buffer value
var yAxisMax = maxRevenueValue + buffer;
var roundingFactor = 20000;
yAxisMax = Math.ceil(yAxisMax / roundingFactor) * roundingFactor;
var revenueChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [{
            label: "Last 30 Days Revenue",
            data: revenueData,
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
                max: yAxisMax,
                ticks: {
                    stepSize: 20000,
                    callback: function (value) {
                        return formatNumber(value);
                    },
                },
            },
        },
    },
    plugins: [valueOnTopPlugin],
});

function updateChart(days) {
    var newData = [];
    var newLabels = [];
    var today = new Date();

    $('#revenueChart').prop('hidden', false);
    $('#revenueChartThisMonth').prop('hidden', true);
    $('#revenueChartCustom').prop('hidden', true);
    $('#button-graph-revenue').text('Last '+ days +' days');

    $('#btn-close-myModalGraph').click();

    for (var i = days - 1; i >= 0; i--) {
        var date_days = new Date(today);
        date_days.setDate(today.getDate() - i);
        var month = date_days.getMonth() + 1;
        var day = date_days.getDate();
        newLabels.push(`${month}/${day}`);
    }
    var startIndex = revenueData.length - days;
    for (var i = startIndex; i < revenueData.length; i++) {
        newData.push(revenueData[i]);
    }
    revenueChart.data.labels = newLabels;
    revenueChart.data.datasets[0].data = newData;
    revenueChart.data.datasets[0].label = `Last ${days} Days Revenue`;
    revenueChart.update();
}