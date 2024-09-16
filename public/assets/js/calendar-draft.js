$(document).ready(function () {
  let today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();
  let selectedYear = null;
  let selectedMonthRange = [];
  let selectedDate = null;
  let selectedMonth = null;
  let lastSelectedValue = ""; // เก็บค่าล่าสุดที่เลือก

  const monthNames = [
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

  // ฟังก์ชันสำหรับอัปเดตกล่องแสดงค่าที่เลือก
  function updateCombinedSelectedBox(value) {
    // ถ้า value เป็น null หรือ undefined ให้ใช้วันที่วันนี้เป็นค่าเริ่มต้น
    if (!value) {
      let today = new Date();
      // ฟอร์แมตวันที่เป็น 13 September 2024
      value = today.toLocaleDateString("en-GB", {
        day: "numeric",
        month: "long",
        year: "numeric",
      });
    }

    lastSelectedValue = value; // เก็บค่าที่เลือกล่าสุด
    // console.log("Updated lastSelectedValue:", lastSelectedValue); // ตรวจสอบว่าค่าถูกอัปเดต
    $("#combined-selected-box").val(`${value}`);
    $("#date").val(`${value}`);
    $('#filter-by').val("date");
    
  }

  // updateCombinedSelectedBox(); // ไม่มีการส่งค่า ให้แสดงวันที่วันนี้โดยอัตโนมัติ

  // ฟังก์ชันเมื่อกดปุ่มส่งค่าที่เลือก
  $("#send-value-button").click(function () {
    alert(`Sending Selected Value: ${lastSelectedValue}`); // แสดงค่า
    // console.log("Sending Value:", lastSelectedValue); // ตรวจสอบค่าที่ถูกส่งใน console
  });

  // ฟังก์ชันเมื่อกดปุ่มเลือกวันนี้
  $("#select-today-button").click(function () {
    selectToday(); // เรียกใช้ฟังก์ชันเลือกวันที่วันนี้
  });

  // ฟังก์ชันสำหรับเลือกวันที่วันนี้และส่งค่า
  function selectToday() {
    selectedDate = today.getDate();
    selectedMonth = today.getMonth();
    selectedYear = today.getFullYear();

    const todayString = `${selectedDate} ${monthNames[selectedMonth]} ${selectedYear}`;
    updateCombinedSelectedBox(todayString); // อัปเดตด้วยวันที่วันนี้

    alert(`Sending Today's Date: ${todayString}`);
  }

  // ฟังก์ชันสำหรับอัปเดตกล่องแสดงวันที่ที่เลือก
  function updateSelectedDateBox() {
    if (
      selectedDate !== null &&
      selectedMonth !== null &&
      selectedYear !== null
    ) {
      const dateString = `${selectedDate} ${monthNames[selectedMonth]} ${selectedYear}`;
      $("#selected-date-box").text(`${dateString}`);
      updateCombinedSelectedBox(dateString); // แสดงค่าล่าสุดคือวันที่ที่เลือก
    } else {
      $("#selected-date-box").text("Selected Date: None");
    }
  }

  // ฟังก์ชันสำหรับอัปเดตเดือนที่เลือก
  function updateSelectedMonthBox() {
    if (selectedMonthRange.length === 2) {
      // ตรวจสอบว่าเดือนแรกและเดือนสุดท้ายเหมือนกันหรือไม่
      if (selectedMonthRange[0] === selectedMonthRange[1]) {
        // ถ้าเลือกแค่เดือนเดียว (แม้จะใช้เป็น range) ให้แสดงแค่เดือนเดียว
        const monthString = `${monthNames[selectedMonthRange[0]]
          } ${selectedYear}`;
        $("#selected-month-box").text(`${monthString}`);
        updateCombinedSelectedBox(monthString);
      } else {
        // ถ้าเลือกสองเดือนที่ต่างกัน ให้แสดงเป็นช่วงของเดือน
        const monthRangeString = `${monthNames[selectedMonthRange[0]]} - ${monthNames[selectedMonthRange[1]]
          } ${selectedYear}`;
        $("#selected-month-box").text(`${monthRangeString}`);
        updateCombinedSelectedBox(monthRangeString);
      }
    } else if (selectedMonthRange.length === 1) {
      // เมื่อเลือกเดือนเดียว ให้แสดงเฉพาะเดือนนั้น
      const monthString = `${monthNames[selectedMonthRange[0]]
        } ${selectedYear}`;
      $("#selected-month-box").text(`${monthString}`);
      updateCombinedSelectedBox(monthString);
    } else {
      // ถ้ายังไม่ได้เลือกเดือน ให้แสดงข้อความนี้
      $("#selected-month-box").text("Selected Month: None");
    }
  }

  // ฟังก์ชันสำหรับอัปเดตปีที่เลือก
  function updateSelectedYearBox() {
    if (selectedYear !== null) {
      $("#selected-year-box").text(`${selectedYear}`);
      updateCombinedSelectedBox(selectedYear); // อัปเดตค่าเมื่อเลือกปี
    } else {
      $("#selected-year-box").text("Selected Year: None");
    }
  }

  function generateDatePicker(month, year) {
    $("#month-year").text(`${monthNames[month]} ${year}`);
    $("#dates-grid").empty();

    // วันที่วันแรกของเดือนและจำนวนวันที่มีในเดือนนั้น
    let firstDay = new Date(year, month).getDay();
    let daysInMonth = 32 - new Date(year, month, 32).getDate();

    // จำนวนวันที่มีในเดือนก่อนหน้า
    let prevMonthDays = 32 - new Date(year, month - 1, 32).getDate();

    // จำนวนวันทั้งหมดในกริด (รวมวันที่จากเดือนก่อนหน้าและถัดไป)
    let totalDays = firstDay + daysInMonth;
    let totalSlots = totalDays > 35 ? 42 : 35; // กำหนดว่าจะใช้ 35 หรือ 42 ช่อง

    // แสดงวันที่จากเดือนก่อนหน้าในช่องว่างก่อนวันที่ 1
    for (let i = firstDay - 1; i >= 0; i--) {
      let prevMonthDate = prevMonthDays - i;
      $("#dates-grid").append(
        `<div class="date other-month no-select">${prevMonthDate}</div>`
      );
    }

    // แสดงวันที่ของเดือนปัจจุบัน
    for (let i = 1; i <= daysInMonth; i++) {
      let dateElement = $(`<div class="date no-select">${i}</div>`);
      if (
        i === today.getDate() &&
        month === today.getMonth() &&
        year === today.getFullYear()
      ) {
        dateElement.addClass("today");
      }
      $("#dates-grid").append(dateElement);

      dateElement.click(function () {
        $(".date").removeClass("active");
        $(this).addClass("active");
        selectedDate = i;
        selectedMonth = month;
        selectedYear = year;
        updateSelectedDateBox(); // เมื่อเลือกวันที่
      });
    }

    // แสดงวันที่จากเดือนถัดไปในช่องว่างหลังจากวันที่สุดท้ายของเดือน
    let remainingSlots = totalSlots - totalDays; // เหลือช่องว่างกี่ช่องในกริด (ขึ้นกับ 35 หรือ 42 ช่อง)
    for (let i = 1; i <= remainingSlots; i++) {
      $("#dates-grid").append(`<div class="date other-month">${i}</div>`);
    }
  }

  // ฟังก์ชันสำหรับสร้าง Month Range Picker ด้วยการสัมผัสหน้าจอ (touch)
  function generateMonthRangePicker(year) {
    $("#year").text(year);
    $(".months-grid").empty();
    selectedMonthRange = [];
    updateSelectedMonthBox();

    monthNames.forEach((month, index) => {
      let monthElement = $(
        `<div class="month" data-month="${index}">${month}</div>`
      );
      if (index === currentMonth && year === currentYear) {
        monthElement.addClass("current-month");
      }
      $(".months-grid").append(monthElement);

      // เมื่อเริ่มสัมผัสหน้าจอ
      monthElement.on("touchstart", function () {
        isTouching = true;
        startMonth = index;
        endMonth = index;
        $(".month").removeClass("selected-range"); // ลบการไฮไลท์เก่าทั้งหมด
        $(this).addClass("selected-range");
      });

      // เมื่อสัมผัสและลาก
      monthElement.on("touchmove", function (event) {
        if (isTouching) {
          event.preventDefault(); // ป้องกันการเลื่อนของหน้าจอ
          let touch = event.originalEvent.touches[0];
          let targetElement = document.elementFromPoint(
            touch.clientX,
            touch.clientY
          );
          let targetMonth = $(targetElement).data("month");
          if (targetMonth !== undefined) {
            endMonth = targetMonth;
            $(".month").removeClass("selected-range"); // ลบการไฮไลท์เก่าทั้งหมด
            if (startMonth <= endMonth) {
              for (let i = startMonth; i <= endMonth; i++) {
                $(`.month[data-month=${i}]`).addClass("selected-range");
              }
            } else {
              for (let i = endMonth; i <= startMonth; i++) {
                $(`.month[data-month=${i}]`).addClass("selected-range");
              }
            }
          }
        }
      });

      // เมื่อสิ้นสุดการสัมผัสหน้าจอ
      monthElement.on("touchend", function () {
        isTouching = false;
        selectedMonthRange = [
          Math.min(startMonth, endMonth),
          Math.max(startMonth, endMonth),
        ];
        selectedYear = year;
        updateSelectedMonthBox(); // อัปเดตกล่องแสดงค่าที่เลือก
      });
    });

    // ปล่อยการสัมผัสในพื้นที่อื่นๆ
    $(document).on("touchend", function () {
      isTouching = false;
    });
  }

  function generateMonthRangePicker(year) {
    $("#year").text(year);
    $(".months-grid").empty();
    selectedMonthRange = [];
    updateSelectedMonthBox();

    monthNames.forEach((month, index) => {
      let monthElement = $(
        `<div class="month no-select" data-month="${index}">${month}</div>`
      );
      if (index === currentMonth && year === currentYear) {
        monthElement.addClass("current-month");
      }
      $(".months-grid").append(monthElement);

      // ฟังก์ชันสำหรับเริ่มการลากเมาส์หรือสัมผัส
      function startSelection() {
        startMonth = index;
        endMonth = index;
        $(".month").removeClass("selected-range"); // ลบการไฮไลท์เก่าทั้งหมด
        monthElement.addClass("selected-range");
      }

      // ฟังก์ชันสำหรับการเลื่อนเมาส์หรือสัมผัสและเลือกเดือน
      function moveSelection(event) {
        let targetMonth;
        if (event.type === "mousemove") {
          targetMonth = $(this).data("month");
        } else if (event.type === "touchmove") {
          event.preventDefault(); // ป้องกันการเลื่อนของหน้าจอ
          let touch = event.originalEvent.touches[0];
          let targetElement = document.elementFromPoint(
            touch.clientX,
            touch.clientY
          );
          targetMonth = $(targetElement).data("month");
        }

        if (targetMonth !== undefined) {
          endMonth = targetMonth;
          $(".month").removeClass("selected-range"); // ลบการไฮไลท์เก่าทั้งหมด
          if (startMonth <= endMonth) {
            for (let i = startMonth; i <= endMonth; i++) {
              $(`.month[data-month=${i}]`).addClass("selected-range");
            }
          } else {
            for (let i = endMonth; i <= startMonth; i++) {
              $(`.month[data-month=${i}]`).addClass("selected-range");
            }
          }
        }
      }

      // ฟังก์ชันสำหรับสิ้นสุดการลากเมาส์หรือสัมผัส
      function endSelection() {
        selectedMonthRange = [
          Math.min(startMonth, endMonth),
          Math.max(startMonth, endMonth),
        ];
        selectedYear = year;
        updateSelectedMonthBox(); // อัปเดตกล่องแสดงค่าที่เลือก
      }

      // Event สำหรับการลากด้วยเมาส์
      monthElement.mousedown(function () {
        isMouseDown = true;
        startSelection();
      });

      monthElement.mousemove(function () {
        if (isMouseDown) {
          moveSelection.call(this, event);
        }
      });

      monthElement.mouseup(function () {
        isMouseDown = false;
        endSelection();
      });

      // Event สำหรับการลากด้วยการสัมผัสหน้าจอ
      monthElement.on("touchstart", function () {
        isTouching = true;
        startSelection();
      });

      monthElement.on("touchmove", function (event) {
        if (isTouching) {
          moveSelection.call(this, event);
        }
      });

      monthElement.on("touchend", function () {
        isTouching = false;
        endSelection();
      });

      // ปล่อยเมาส์หรือการสัมผัสในพื้นที่อื่นๆ
      $(document).on("mouseup touchend", function () {
        isMouseDown = false;
        isTouching = false;
      });
    });
  }

  // ฟังก์ชันสำหรับสร้าง Year Picker
  function generateYearPicker(startYear, endYear) {
    $(".years-grid").empty();
    for (let year = startYear; year <= endYear; year++) {
      let yearElement = $(`<div class="year not-select">${year}</div>`);
      if (year === currentYear) {
        yearElement.addClass("current-year");
      }
      $(".years-grid").append(yearElement);

      yearElement.click(function () {
        $(".year").removeClass("active");
        $(this).addClass("active");
        selectedYear = year;
        updateSelectedYearBox();
        generateMonthRangePicker(selectedYear);
      });
    }
  }

  // ฟังก์ชันสำหรับการนำทางระหว่างเดือนและปี
  $("#prev-month").click(function () {
    currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
    if (currentMonth === 11) currentYear--;
    generateDatePicker(currentMonth, currentYear);
  });

  $("#next-month").click(function () {
    currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
    if (currentMonth === 0) currentYear++;
    generateDatePicker(currentMonth, currentYear);
  });

  $("#prev-year").click(function () {
    currentYear--;
    generateMonthRangePicker(currentYear);
  });

  $("#next-year").click(function () {
    currentYear++;
    generateMonthRangePicker(currentYear);
  });

  // การสร้างปฏิทินเริ่มต้น
  generateDatePicker(currentMonth, currentYear);
  generateMonthRangePicker(currentYear);
  generateYearPicker(2024, 2026);

  // ฟังก์ชันสำหรับการกรอง
  $("#filter-date").on("click", function () {
    $("#date-picker-wrapper").show();
    $("#month-picker-wrapper, #year-picker-wrapper").hide();
    $('#filter-by').val("date");
  });

  $("#filter-month").on("click", function () {
    $("#month-picker-wrapper").show();
    $("#date-picker-wrapper, #year-picker-wrapper").hide();
    $('#filter-by').val("month");
  });

  $("#filter-year").on("click", function () {
    $("#year-picker-wrapper").show();
    $("#date-picker-wrapper, #month-picker-wrapper").hide();
    $('#filter-by').val("year");
  });
});

// tooltip
var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
