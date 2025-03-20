var inputTotalElexaCharge = $('#input-total-elexa-charge-revenue').val();
var inputTotalElexaPaid = $('#input-total-elexa-paid').val();
var inputTotalAccountReceivable = Number($('#input-total-account-receivable').val());
var inputTotalPendingAccountReceivable = Number($('#input-total-pending-account-receivable').val());
var inputTotalOutstandingAll = Number($('#input-total-elexa-outstanding').val());

let selectedYear = new Date().getFullYear(); // ปีเริ่มต้น

function getGraphMonthSales(typeRevenue) {
  var revenueData = "";

  $.ajax({
      type: "GET",
      url: "/debtor-elexa-graph-month-sales",
      datatype: "JSON",
      async: false,
      success: function(response) {
          // Sample data for watch revenue over 30 days
          if (typeRevenue == 0) {
              revenueData = response.data[2024];
          } if (typeRevenue == 1) {
              revenueData = response.data[2025];
          } if (typeRevenue == 2) {
              revenueData = response.data[2026];
          }
      }
  });
  
  return revenueData;
}

function getGraphMonthCharge(year, typeRevenue) {
  var revenueData = "";

  $.ajax({
      type: "GET",
      url: "/debtor-elexa-graph-month-charge",
      datatype: "JSON",
      async: false,
      success: function(response) {
          // Sample data for watch revenue over 30 days
          if (year == 2024) { 
            if (typeRevenue == "data_sms") {
              revenueData = response.data_sms[2024];
            } if (typeRevenue == "data_sms_paid") {
              revenueData = response.data_sms_paid[2024];
            } if (typeRevenue == "data_outstanding") {
              revenueData = response.data_outstanding[2024];
            } if (typeRevenue == "data_sms_pending") {
              revenueData = response.data_sms_pending[2024];
            }
          } if (year == 2025) {
              if (typeRevenue == "data_sms") {
                revenueData = response.data_sms[2025];
              } if (typeRevenue == "data_sms_paid") {
                revenueData = response.data_sms_paid[2025];
              } if (typeRevenue == "data_outstanding") {
                revenueData = response.data_outstanding[2025];
              } if (typeRevenue == "data_sms_pending") {
                revenueData = response.data_sms_pending[2025];
              }
          } if (year == 2026) {
              if (typeRevenue == "data_sms") {
                revenueData = response.data_sms[2026];
              } if (typeRevenue == "data_sms_paid") {
                revenueData = response.data_sms_paid[2026];
              } if (typeRevenue == "data_outstanding") {
                revenueData = response.data_outstanding[2026];
              } if (typeRevenue == "data_sms_pending") {
                revenueData = response.data_sms_pending[2026];
              }
          }
      }
  });
  
  return revenueData;
}
// ฟังก์ชันช่วยแปลงตัวเลขเป็น K หรือ M
function formatNumberToKMB(value) {
  if (value >= 1000000) {
    return (value / 1000000).toFixed(1) + "M";
  } else if (value >= 1000) {
    return (value / 1000).toFixed(1) + "K";
  }
  return value.toFixed(2); // แสดงทศนิยม 2 ตำแหน่ง
}

// ฟังก์ชันช่วยแปลงตัวเลขเป็น K หรือ M
function formatNumberCol(value) {
  return value.toLocaleString("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}

// Pie Chart
new Chart(document.getElementById("salesPieChart"), {
  type: "pie",
  data: {
    labels: [
      "Account Receivable",
      "Pending Account Receivable",
      "Agoda Outstanding",
    ],
    datasets: [
      {
        data: [inputTotalAccountReceivable, inputTotalPendingAccountReceivable, inputTotalOutstandingAll], // ตัวเลขสำหรับแต่ละส่วน
        backgroundColor: [
          "#4cb659",
          "rgb(150, 150, 149)",
          "rgb(247, 161, 100)",
        ],
      },
    ],
  },
  options: {
    responsive: true, // ให้กราฟปรับขนาดได้
    plugins: {
      title: {
        display: true,
        text: "Agoda Account Receivable & Outstanding",
        font: {
          size: 14,
        },
      },
      legend: {
        display: true, // แสดง Legend
        position: "bottom", // ตำแหน่งของ Legend
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            const value = context.raw || 0;
            return `${formatNumberCol(value)} THB`; // แสดงตัวเลขแบบย่อใน Tooltip
          },
        },
      },
      datalabels: {
        padding: 6, // เพิ่มระยะห่างรอบข้อความ
        display: true,
        color: "black",
        backgroundColor: "rgba(255, 255, 255, 0.648)",
        borderRadius: 5,
        formatter: (value) => formatNumberToKMB(value),
        anchor: "end",
        align: "start",
        offset: 10,
        font: {
          size: 12,
        },
      },
    },
  },
  plugins: [ChartDataLabels], // ใช้ Chart.js Plugin
});

// Line Chart for monthly sales
new Chart(document.getElementById("salesLineChart"), {
  type: "line",
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [
      {
        label: "Sales 2024",
        data: getGraphMonthSales(0),
        fill: false,
        borderColor: "#007bff",
        tension: 0.5, // ปรับความโค้งของเส้น
      },
      {
        label: "Sales 2025",
        data: getGraphMonthSales(1),
        fill: false,
        borderColor: "#28a745",
        tension: 0.5, // ปรับความโค้งของเส้น
      },
      {
        label: "Sales 2026",
        data: getGraphMonthSales(2),
        fill: false,
        borderColor: "#ff5733",
        tension: 0.5, // ปรับความโค้งของเส้น
      },
    ],
  },
  options: {
    plugins: {
      title: {
        display: true,
        text: "Monthly Sales Comparison (2024, 2025, 2026)",
        font: {
          size: 14,
        },
      },
    },
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});

// กราฟกับการ์ด
// ข้อมูลตัวอย่างสำหรับแต่ละปี
const dataByYear = {
  2024: {
    paid: getGraphMonthCharge(2024, 'data_sms_paid'),
    pending: getGraphMonthCharge(2024, 'data_sms_pending'),
    outstanding: getGraphMonthCharge(2024, 'data_outstanding'),
  },
  2025: {
    paid: getGraphMonthCharge(2025, 'data_sms_paid'),
    pending: getGraphMonthCharge(2025, 'data_sms_pending'),
    outstanding: getGraphMonthCharge(2025, 'data_outstanding'),
  },
  2026: {
    paid: getGraphMonthCharge(2026, 'data_sms_paid'),
    pending: getGraphMonthCharge(2026, 'data_sms_pending'),
    outstanding: getGraphMonthCharge(2026, 'data_outstanding'),
  },
};

// ฟังก์ชันคำนวณ Total Charge Data
function calculateTotalChargeData(year) {
  return dataByYear[year].paid.map(
    (value, index) =>
      value +
      dataByYear[year].pending[index] +
      dataByYear[year].outstanding[index]
  );
}
// ฟังก์ชันช่วยแปลงตัวเลขเป็น K หรือ M
function formatNumberToKMB(value) {
  if (value >= 1000000) {
    return (value / 1000000).toFixed(1) + "M";
  } else if (value >= 1000) {
    return (value / 1000).toFixed(1) + "K";
  }
  return value.toFixed(2); // แสดงทศนิยม 2 ตำแหน่ง
}
// ฟังก์ชันช่วยแปลงตัวเลขเป็น K หรือ M
function formatNumberCol(value) {
  return value.toLocaleString("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}
// ฟังก์ชันอัปเดตข้อมูลในส่วนเดือน
function updateMonthValues(data, color = "", backgroundColor = "") {
  const buttons = document.querySelectorAll(".card-month-agoda p");
  buttons.forEach((button, index) => {
    const valueElement = button.querySelector(".month-value");
    if (valueElement) {
      // const formattedValue = formatNumberToKMB(data[index]);
      const formattedValue = formatNumberCol(data[index]);
      valueElement.textContent = formattedValue;
    }
    const firstChild = button
      .closest(".chart-button")
      ?.querySelector(":nth-child(1)");
    if (firstChild) {
      firstChild.style.color = color || "";
      firstChild.style.backgroundColor = backgroundColor || "";
    }
  });
}
// ฟังก์ชันอัปเดต Summary Totals
function updateSummaryTotals() {
  const accountReTotal = dataByYear[selectedYear].paid.reduce(
    (sum, value) => sum + value,
    0
  );
  const pendingTotal = dataByYear[selectedYear].pending.reduce(
    (sum, value) => sum + value, 0
  );

  const outstandingTotal = dataByYear[selectedYear].outstanding.reduce(
    (sum, value) => sum + value, 0
  );

  const paidTotal = accountReTotal + pendingTotal;
  const totalRevenue = paidTotal + outstandingTotal;
  document.querySelector("#AccountRe span:nth-child(2)").textContent =
    formatNumberCol(accountReTotal);
  document.querySelector("#agodaCharge span:nth-child(2)").textContent =
    formatNumberCol(parseFloat(inputTotalElexaCharge).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
  document.querySelector("#agodaPaid span:nth-child(2)").textContent =
    formatNumberCol(paidTotal);
  document.querySelector("#pendingAccount span:nth-child(2)").textContent =
    formatNumberCol(pendingTotal);
  // document.querySelector("#outstandingBalance span:nth-child(2)").textContent =
  //   formatNumberCol(outstandingTotal);
}
// ฟังก์ชันอัปเดตข้อมูลกราฟ
function updateChart(datasets, title) {
  salesChart.data.datasets = datasets;
  salesChart.options.plugins.title.text = title;
  salesChart.update();
}
// สร้างกราฟเริ่มต้น
const ctx = document.getElementById("salesLineChart2").getContext("2d");
let salesChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        label: "Paid",
        data: dataByYear[selectedYear].paid,
        backgroundColor: "#4abb74",
      },
      {
        label: "Pending",
        data: dataByYear[selectedYear].pending,
        backgroundColor: "rgb(150, 150, 149)",
      },
      {
        label: "Outstanding",
        data: dataByYear[selectedYear].outstanding,
        backgroundColor: "rgb(247, 161, 100)",
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      datalabels: {
        display: function (context) {
          // ซ่อน datalabel ถ้าค่าเป็น 0
          return context.dataset.data[context.dataIndex] !== 0;
        },
        color: "black",
        anchor: "top",
        align: "top",
        formatter: formatNumberToKMB,
        backgroundColor: function (context) {
          return context.dataset.data[context.dataIndex] === 0
            ? "rgba(0, 0, 0, 0)" // โปร่งใส
            : "rgba(255, 255, 255, 0.648)"; // สีพื้นหลังปกติ
        },
        borderRadius: 5,
        font: {
          size: 12,
        },
      },
      title: {
        display: true,
        text: `Monthly Agoda Charge for ${selectedYear}`,
        font: {
          size: 14,
        },
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        stacked: true,
        ticks: {
          callback: formatNumberToKMB,
        },
      },
      x: {
        stacked: true,
      },
    },
  },
  plugins: [ChartDataLabels],
});
// ฟังก์ชันจัดการ Event Click
function handleCardClick(type) {
  const datasetMap = {
    agodaCharge: {
      datasets: [
        {
          label: "Account Receivable",
          data: getGraphMonthCharge(selectedYear, "data_sms_paid"),
          backgroundColor: "#4abb74",
        },
        {
          label: "Pending",
          data: getGraphMonthCharge(selectedYear, "data_sms_pending"),
          backgroundColor: "rgb(150, 150, 149)",
        },
        {
          label: "Outstanding",
          data: getGraphMonthCharge(selectedYear, "data_outstanding"),
          backgroundColor: "rgb(247, 161, 100)",
        },
      ],
      monthBackgroundColor: "#509691",
      title: `Monthly Agoda Charge for ${selectedYear}`,
    },
    agodaPaid: {
      datasets: [
        {
          label: "Agoda Paid(Bank Transfer)",
          data: getGraphMonthCharge(selectedYear, "data_sms"),
          backgroundColor: "rgb(95, 154, 182)",
        },
      ],
      monthBackgroundColor: "rgb(95, 154, 182)",
      title: `Monthly Agoda Paid(Bank Transfer) for ${selectedYear}`,
    },
    AccountRe: {
      datasets: [
        {
          label: "Account Receivable",
          data: getGraphMonthCharge(selectedYear, "data_sms_paid"),
          backgroundColor: "#4abb74",
        },
      ],
      monthBackgroundColor: "#4abb74",
      title: `Monthly Account Receivable for ${selectedYear}`,
    },
    pendingAccount: {
      datasets: [
        { 
          label: "Pending Account Receivable",
          data: getGraphMonthCharge(selectedYear, "data_sms_pending"),
          backgroundColor: "rgb(150, 150, 149)",
        },
      ],
      monthBackgroundColor: "rgb(150, 150, 149)",
      title: `Monthly Pending Account Receivable for ${selectedYear}`,
    },
    outstandingBalance: {
      datasets: [
        {
          label: "Agoda Outstanding Balance",
          data: getGraphMonthCharge(selectedYear, "data_outstanding"),
          backgroundColor: "rgb(247, 161, 100)",
        },
      ],
      monthBackgroundColor: "rgb(247, 161, 100)",
      title: `Monthly Outstanding Balance for ${selectedYear}`,
    },
  };
  const dataset = datasetMap[type];
  if (!dataset) {
    console.error(`Invalid type: ${type}`);
    return;
  }
  // Check if 'agodaCharge' is clicked and update Month Values with combined data
  if (type === "agodaCharge") {
    updateMonthValues(
      calculateTotalChargeData(selectedYear),
      "white",
      dataset.monthBackgroundColor
    );
  } else {
    updateMonthValues(
      dataset.datasets[0].data,
      "white",
      dataset.monthBackgroundColor
    );
  }
  updateChart(dataset.datasets, dataset.title);
  // updateMonthValues(dataset.datasets[0].data, "white", dataset.monthBackgroundColor);
}
// Event Listeners สำหรับการ์ด
[
  "agodaCharge",
  "agodaPaid",
  "AccountRe",
  "pendingAccount",
  "outstandingBalance",
].forEach((id) => {
  const element = document.getElementById(id);
  if (element) {
    element.addEventListener("click", function () {
      handleCardClick(id);
    });
  } else {
    console.warn(`Element with ID "${id}" not found.`);
  }
});
// Event Listener สำหรับ Dropdown ปี
document.getElementById("yearSelect").addEventListener("change", function () {
  selectedYear = this.value;
  updateSummaryTotals();
  const totalChargeData = calculateTotalChargeData(selectedYear);
  updateMonthValues(totalChargeData);
  const updatedDatasets = [
    {
      label: "Paid",
      data: dataByYear[selectedYear].paid,
      backgroundColor: "#4abb74",
    },
    {
      label: "Pending",
      data: dataByYear[selectedYear].pending,
      backgroundColor: "rgb(150, 150, 149)",
    },
    {
      label: "Outstanding",
      data: dataByYear[selectedYear].outstanding,
      backgroundColor: "rgb(247, 161, 100)",
    },
  ];
  updateChart(updatedDatasets, `Monthly Agoda Charge for ${selectedYear}`);
});
// ค่า Default เมื่อโหลดหน้า
document.addEventListener("DOMContentLoaded", function () {
  updateSummaryTotals();
  const totalChargeData = calculateTotalChargeData(selectedYear);
  updateMonthValues(totalChargeData);
});
