function Choice(elem) {
  let aa = document.getElementById("ch-day").innerHTML;
  let ab = document.getElementById("ch-month").innerHTML;
  let ac = document.getElementById("ch-year").innerHTML;
  var box = document.getElementById("box");

  if (elem.id == "showD") {
    box.innerHTML = aa;
    $("#calendar-day").prop("hidden", true);
    $("#filter-by").val('date');
  } else if (elem.id == "showM") {
    box.innerHTML = ab;
    $("#calendar-day").prop("hidden", true);
    $("#filter-by").val('month');
  } else {
    box.innerHTML = ac;
    $("#calendar-day").prop("hidden", true);
    $("#filter-by").val('year');
  }

  $("#choice-date").val(elem.id);
}

function btn_date_confirm() {
  var days = $("#myDay").text(); // ดึงค่าจาก Tag <p>
  var month1 = $("#myMonth1").text(); // ดึงค่าจาก Tag <p>
  var month2 = $("#myMonth2").text();
  var year = $("#myYear").text(); // ดึงค่าจาก Tag <p>
  var choice = $("#choice-date").val();

  $("#input-search-date").val('day');

  if (choice == "showD" || choice == "") {
    $("#select-date").val(days);
  }
  if (choice == "showM") {
    $("#select-date").val(month1 + " - " + month2);
  }
  if (choice == "showY") {
    $("#select-date").val(year);
  }
}

function getYearValue(myYear) {
  let aa = myYear;
  document.getElementById("myYear").innerHTML = aa + "&nbsp;";
  $('#input-search-year').val(aa);
  $("#filter-by").val('year');
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

      $('#input-search-month').val(myMonth1 + 1);
    } else {
      num -= 1;
      $("#myMonth2").text(monthName[myMonth1]);
      $("#month-number2").val(myMonth1);
      $("#month-click-num").val(num);

      $('#input-search-month-to').val(myMonth1 + 1);
    }
  }
  $("#filter-by").val('month');
}

const date = new Date();

function myDaysFunction(myDay, month) {
  var myDay1 = myDay;
  var thisMont = $("#mymonth").text();
  document.getElementById("myDay").innerHTML =
    myDay1 + " " + thisMont + " " + date.getFullYear();

    $('#input-search-day').val(myDay);
    $('#input-search-month').val(month + 1);
    $("#filter-by").val('date');
}

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

  // แสดงเดือนทั้งปี
  let allMonth = "";
  for (let x in months) {
    allMonth += `<div class="" onclick="myFunction2(${x})">${months[x]}</div>`;
  }
  document.getElementById("allMonth").innerHTML = allMonth;

  document.querySelector(".date h1").innerHTML = months[date.getMonth()];

  document.querySelector(".date p").innerHTML =
    new Date().getDate() +
    " " +
    months[date.getMonth()] +
    " " +
    date.getFullYear();

  let days = "";

  for (let x = firstDayIndex; x > 0; x--) {
    days += `<div class="prev-date">${prevLastDay - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDay; i++) {
    if (i === new Date().getDate() && date.getMonth() === new Date().getMonth()) 
    {
      days += `<div class="today">${i}</div>`;
    } else {
      days += `<div onclick="myDaysFunction(${i}, ${date.getMonth()})">${i}</div>`;
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
  
  // $('#input-search-day').val(new Date().getDate());
  // $('#input-search-month').val(date.getMonth() + 1);
});

document.querySelector(".next").addEventListener("click", () => {
  date.setMonth(date.getMonth() + 1);
  renderCalendar();

  // $('#input-search-day').val(new Date().getDate());
  // $('#input-search-month').val(date.getMonth() + 1);
});

renderCalendar();
