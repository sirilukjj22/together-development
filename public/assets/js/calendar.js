$(document).ready(function () {
  // เมื่อเริ่มการทำงานในอ่านค่าใน Ready ก่อน ** Function ready จะอ่านค่าตอนเริ่มต้นแค่ครั้งเดียวจะมีผลเมื่อ Refreash หน้าจอ
  $("#select-month-year").text(new Date().getFullYear());
  $("#by-month-year").val(new Date().getFullYear());
});

function myFunction(myYear) {
  let aa = myYear;
  document.getElementById("myYear").innerHTML = aa + "&nbsp;";
  // $('#text-days').val(ac);
}

function myFunction2(myMonth) {
  let myMonth1 = myMonth;
  var num = parseInt($("#month-click-num").val());
  var monthName = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ]; // ชื่อเดือน

  if (num == 0) {
    $("#month-number1").val(myMonth1);
  } else {
    $("#month-number2").val(myMonth1);
  }

  if (
    $("#month-number1").val() > $("#month-number2").val() &&
    $("#month-number2").val() != 0
  ) {
    // ถ้าเดือนที่เริ่มมากกว่า เดือนสิ้นสุดและไม่เท่ากับ 0 ให้เข้าเงื่อนไข
    alert("Error โปรดเลือกเดือนใหม่ ให้ถูกต้องตามลำดับเดือน");
    $("#month-number2").val(0);
  } else {
    if (num == 0) {
      num += 1;
      $("#myMonth1").text(monthName[myMonth1]);
      $("#month-number1").val(myMonth1);
      $("#month-click-num").val(num);
    } else {
      num -= 1;
      $("#myMonth2").text(monthName[myMonth1]);
      $("#month-number2").val(myMonth1);
      $("#month-click-num").val(num);
    }
  }
}

const date = new Date();
const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];
function myDaysFunction(myDay) {
  let myDay1 = myDay;
  var thisMont = $("#mymonth").text();
  document.getElementById("myDay").innerHTML =
    myDay1 + " " + thisMont + " " + date.getFullYear();
  // $('#text-days').val(ac);
}

$(document).on("click", "#btn-save-date", function () {
  var days = $("#myDay").text(); // ดึงค่าจาก Tag <p>
  var month1 = $("#myMonth1").text(); // ดึงค่าจาก Tag <p>
  var month2 = $("#myMonth2").text();
  var year = $("#myYear").text(); // ดึงค่าจาก Tag <p>
  $("#select-date").val(days + "/" + month1 + "/" + month2 + "/" + year);
});

// funtion ใช้เรียก วันเดือนปี ให้แสดง
function Choice(elem) {
  let aa = document.getElementById("showDays").innerHTML;
  let ab = document.getElementById("showMonths").innerHTML;
  let ac = document.getElementById("showYears").innerHTML;
  var box = document.getElementById("box");
  if (elem.id == "showD") {
    box.innerHTML = aa;
  } else if (elem.id == "showM") {
    box.innerHTML = ab;
  } else if (elem.id == "showY") {
    box.innerHTML = ac;
  } else {
    box.innerHTML = "not found please refresh your browser";
  }
}

// แสดงเดือนทั้งปี
let allMonth = "";
for (let x in months) {
  allMonth += `<div class="my-1 text-center hover:bg-[#2C7F7A]/30 duration-300 p-2 m-0 border-2 border-transparent hover:border-teal-800/50" onclick="myFunction2(${x})">${months[x]}</div>`;
}
document.getElementById("allMonth").innerHTML = allMonth;

const renderCalendar = () => {
  date.setDate(1);

  const monthDays = document.querySelector(".days");

  const lastDay = new Date(
    date.getFullYear(),
    date.getMonth() + 1,
    0
  ).getDate();

  const prevLastDay = new Date(
    date.getFullYear(),
    date.getMonth(),
    0
  ).getDate();

  const firstDayIndex = date.getDay();

  const lastDayIndex = new Date(
    date.getFullYear(),
    date.getMonth() + 1,
    0
  ).getDay();

  const nextDays = 7 - lastDayIndex - 1;

  document.querySelector(".thisMont").innerHTML = months[date.getMonth()];

  // document.querySelector(".date p").innerHTML = new Date().toDateString();
  document.querySelector(".date .dateShose").innerHTML =
    new Date().getDate() +
    " " +
    months[date.getMonth()] +
    " " +
    date.getFullYear();
  // var date_current = new Date();
  // $('.date-current').text(date_current.getDay());

  // แสดงเวันทั้งเดือน
  let days = "";
  for (let x = firstDayIndex; x > 0; x--) {
    days += `<div class="prev-date ">${prevLastDay - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDay; i++) {
    if (
      i === new Date().getDate() &&
      date.getMonth() === new Date().getMonth()
    ) {
      days += `<div class="today">${i}</div>`;
    } else {
      days += `<div onclick="myDaysFunction(${i})">${i} </div>`;
    }
  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="next-date">${j}</div>`;
  }
  monthDays.innerHTML = days;
};

document.querySelector(".prev").addEventListener("click", () => {
  date.setMonth(date.getMonth() - 1);
  renderCalendar();
});

document.querySelector(".next").addEventListener("click", () => {
  date.setMonth(date.getMonth() + 1);
  renderCalendar();
});

document.querySelector(".prev-year").addEventListener("click", () => {
  var year = parseInt($("#by-month-year").val());
  $("#select-month-year").text(year - 1);
  $("#by-month-year").val(year - 1);
});

document.querySelector(".next-year").addEventListener("click", () => {
  var year = parseInt($("#by-month-year").val());
  $("#select-month-year").text(year + 1);
  $("#by-month-year").val(year + 1);
});

renderCalendar();
