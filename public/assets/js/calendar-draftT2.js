$(document).ready(function () {
  let today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();
  let displayMonth = currentMonth;
  let displayYear = currentYear;
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
    
    if (!value) {
      let today = new Date();
      value = today.toLocaleDateString("en-GB", {
        day: "numeric",
        month: "long",
        year: "numeric",
      });
    }
    lastSelectedValue = value;

    if (typeof value === "string") {
        const parts = value.split("-");
        
        if (parts.length == 2) {
          $('#filter-by').val('customRang');
        }
    }
    // console.log("Updated lastSelectedValue:", lastSelectedValue);
    $("#combined-selected-box").val(`${value}`);
    $("#date").val(`${value}`);
  }

  updateCombinedSelectedBox(); // แสดงวันที่วันนี้โดยอัตโนมัติ

  // ฟังก์ชันเมื่อกดปุ่มส่งค่าที่เลือก
  $("#send-value-button").click(function () {
    alert(`Sending Selected Value: ${lastSelectedValue}`);
    // console.log("Sending Value:", lastSelectedValue);

    // ใช้ selectedMonth และ selectedYear แทน currentMonth และ currentYear
    displayMonth = selectedMonth !== null ? selectedMonth : currentMonth;
    displayYear = selectedYear !== null ? selectedYear : currentYear;

    // รีเฟรชปฏิทินโดยใช้ displayMonth และ displayYear
    generateDatePicker(displayMonth, displayYear);
  });

  // ฟังก์ชันเมื่อกดปุ่มเลือกวันนี้
  $("#select-today-button").click(function () {
    selectToday();
    displayMonth = today.getMonth();
    displayYear = today.getFullYear();
    generateDatePicker(displayMonth, displayYear);
  });

  // ฟังก์ชันสำหรับเลือกวันที่วันนี้และส่งค่า
  function selectToday() {
    selectedDate = today.getDate();
    selectedMonth = today.getMonth();
    selectedYear = today.getFullYear();

    const todayString = `${selectedDate} ${monthNames[selectedMonth]} ${selectedYear}`;
    updateCombinedSelectedBox(todayString);

    localStorage.setItem("selectedDate", selectedDate);
    localStorage.setItem("selectedMonth", selectedMonth);
    localStorage.setItem("selectedYear", selectedYear);

    generateDatePicker(selectedMonth, selectedYear);

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
      updateCombinedSelectedBox(dateString);

      localStorage.setItem("selectedDate", selectedDate);
      localStorage.setItem("selectedMonth", selectedMonth);
      localStorage.setItem("selectedYear", selectedYear);
    } else {
      $("#selected-date-box").text("Selected Date: None");
    }
  }

  function loadStoredDateRange() {
    if (
      localStorage.getItem("selectedDate") !== null &&
      localStorage.getItem("selectedMonth") !== null &&
      localStorage.getItem("selectedYear") !== null
    ) {
      selectedDate = parseInt(localStorage.getItem("selectedDate"));
      selectedMonth = parseInt(localStorage.getItem("selectedMonth"));
      selectedYear = parseInt(localStorage.getItem("selectedYear"));

      const dateString = `${selectedDate} ${monthNames[selectedMonth]} ${selectedYear}`;
      $("#selected-date-box").text(dateString);
      updateCombinedSelectedBox(dateString);

      displayMonth = selectedMonth;
      displayYear = selectedYear;
      generateDatePicker(displayMonth, displayYear);
    } else {
      generateDatePicker(currentMonth, currentYear);
    }
  }

  $(document).ready(function () {
    loadStoredDateRange();
  });

  // ฟังก์ชันสำหรับอัปเดตเดือนที่เลือก
  function updateSelectedMonthBox() {
    if (selectedMonthRange.length === 2) {
      // ตรวจสอบว่าเดือนแรกและเดือนสุดท้ายเหมือนกันหรือไม่
      if (selectedMonthRange[0] === selectedMonthRange[1]) {
        // ถ้าเลือกแค่เดือนเดียว (แม้จะใช้เป็น range) ให้แสดงแค่เดือนเดียว
        const monthString = `${
          monthNames[selectedMonthRange[0]]
        } ${selectedYear}`;
        $("#selected-month-box").text(`${monthString}`);
        updateCombinedSelectedBox(monthString);
      } else {
        // ถ้าเลือกสองเดือนที่ต่างกัน ให้แสดงเป็นช่วงของเดือน
        const monthRangeString = `${monthNames[selectedMonthRange[0]]} - ${
          monthNames[selectedMonthRange[1]]
        } ${selectedYear}`;
        $("#selected-month-box").text(`${monthRangeString}`);
        updateCombinedSelectedBox(monthRangeString);
      }
    } else if (selectedMonthRange.length === 1) {
      // เมื่อเลือกเดือนเดียว ให้แสดงเฉพาะเดือนนั้น
      const monthString = `${
        monthNames[selectedMonthRange[0]]
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

    let firstDay = new Date(year, month).getDay();
    let daysInMonth = 32 - new Date(year, month, 32).getDate();
    let prevMonthDays = 32 - new Date(year, month - 1, 32).getDate();
    let totalDays = firstDay + daysInMonth;
    let totalSlots = totalDays > 35 ? 42 : 35;

    // แสดงวันที่จากเดือนก่อนหน้าในช่องว่างก่อนวันที่ 1
    for (let i = firstDay - 1; i >= 0; i--) {
      let prevMonthDate = prevMonthDays - i;
      $("#dates-grid").append(
        `<div class="date other-month no-select">${prevMonthDate}</div>`
      );
    }

    let startDate = null;
    let endDate = null;
    let isDragging = false;
    let isTouching = false;

    // แสดงวันที่ของเดือนปัจจุบัน
    for (let i = 1; i <= daysInMonth; i++) {
      let dateElement = $(
        `<div class="date no-select" data-date="${i}">${i}</div>`
      );
      if (
        i === today.getDate() &&
        month === today.getMonth() &&
        year === today.getFullYear()
      ) {
        dateElement.addClass("today");
      }

      if (
        selectedDate === i &&
        selectedMonth === month &&
        selectedYear === year
      ) {
        dateElement.addClass("selected-range");
      }
      $("#dates-grid").append(dateElement);

      // เริ่มการลากเมาส์
      dateElement.mousedown(function () {
        isDragging = true;
        startDate = i;
        endDate = i;
        $(".date").removeClass("selected-range");
        $(this).addClass("selected-range");
      });

      // เริ่มการลากสัมผัส
      dateElement.on("touchstart", function () {
        isTouching = true;
        startDate = i;
        endDate = i;
        $(".date").removeClass("today selected-range");
        $(this).addClass("selected-range");
      });

      // ลากเมาส์เพื่อเลือกวันที่
      dateElement.mousemove(function () {
        if (isDragging) {
          endDate = i;
          $(".date").removeClass("selected-range");

          // เลือกวันที่ระหว่าง startDate และ endDate
          let start = Math.min(startDate, endDate);
          let end = Math.max(startDate, endDate);

          for (let j = start; j <= end; j++) {
            $(`.date[data-date=${j}]`).addClass("selected-range");
          }
        }
      });

      // ลากสัมผัสเพื่อเลือกวันที่
      dateElement.on("touchmove", function (event) {
        if (isTouching) {
          event.preventDefault(); // ป้องกันการเลื่อนของหน้าจอ
          let touch = event.originalEvent.touches[0];
          let targetElement = document.elementFromPoint(
            touch.clientX,
            touch.clientY
          );
          let targetDate = $(targetElement).data("date");

          if (targetDate !== undefined) {
            endDate = targetDate;
            $(".date").removeClass("selected-range");

            let start = Math.min(startDate, endDate);
            let end = Math.max(startDate, endDate);

            for (let j = start; j <= end; j++) {
              $(`.date[data-date=${j}]`).addClass("selected-range");
            }
          }
        }
      });

      // สิ้นสุดการลากเมาส์
      dateElement.mouseup(function () {
        if (isDragging) {
          isDragging = false;

          // อัปเดต selectedDate เป็นช่วงที่ถูกเลือก
          let start = Math.min(startDate, endDate);
          let end = Math.max(startDate, endDate);
          selectedDate = `${start} - ${end}`;
          selectedMonth = month;
          selectedYear = year;

          updateSelectedDateBox(); // อัปเดตกล่องแสดงค่าที่เลือก
        }
      });

      // สิ้นสุดการลากสัมผัส
      dateElement.on("touchend", function () {
        if (isTouching) {
          isTouching = false;

          // อัปเดต selectedDate เป็นช่วงที่ถูกเลือก
          let start = Math.min(startDate, endDate);
          let end = Math.max(startDate, endDate);
          selectedDate = `${start} - ${end}`;
          selectedMonth = month;
          selectedYear = year;

          updateSelectedDateBox(); // อัปเดตกล่องแสดงค่าที่เลือก
        }
      });

      // กดเลือกวันที่เพื่อเลือกวันเดียว
      dateElement.click(function () {
        if (!isDragging && !isTouching) {
          $(".date").removeClass("selected-range");
          $(this).addClass("selected-range");
          selectedDate = i;
          selectedMonth = month;
          selectedYear = year;
          updateSelectedDateBox(); // เมื่อเลือกวันที่
        }
      });
    }

    // แสดงวันที่จากเดือนถัดไปในช่องว่างหลังจากวันที่สุดท้ายของเดือน
    let remainingSlots = totalSlots - totalDays;
    for (let i = 1; i <= remainingSlots; i++) {
      $("#dates-grid").append(`<div class="date other-month">${i}</div>`);
    }

    // ปล่อยเมาส์หรือการสัมผัสในพื้นที่อื่นๆ
    $(document).on("mouseup touchend", function () {
      if (isDragging || isTouching) {
        isDragging = false;
        isTouching = false;
      }
    });
  }

  $("#prev-month").click(function () {
    displayMonth = displayMonth === 0 ? 11 : displayMonth - 1;
    if (displayMonth === 11) displayYear--;
    generateDatePicker(displayMonth, displayYear);
  });

  $("#next-month").click(function () {
    displayMonth = displayMonth === 11 ? 0 : displayMonth + 1;
    if (displayMonth === 0) displayYear++;
    generateDatePicker(displayMonth, displayYear);
  });

  function generateMonthRangePicker(year) {
    $("#year").text(year);
    $(".months-grid").empty();
    selectedMonthRange = [];
    updateSelectedMonthBox();

    let isDragging = false; // Track whether dragging is happening
    let dragStartIndex = null; // Track the start of the drag

    monthNames.forEach((month, index) => {
      let monthElement = $(
        `<div class="month no-select" data-month="${index}">${month}</div>`
      );

      // Mark the current month
      if (index === currentMonth && year === currentYear) {
        monthElement.addClass("current-month");
      }

      $(".months-grid").append(monthElement);

      // Start selection on mouse down or touch start
      monthElement.on("mousedown touchstart", function (event) {
        isDragging = true;
        dragStartIndex = index;
        selectedMonthRange = [index];
        updateCombinedSelectedBox(`${monthNames[index]} ${year}`);
        monthElement.addClass("selected-range");
        event.preventDefault(); // Prevent default touch behavior
      });

      // Select range on mouse enter or touch move
      monthElement.on("mouseenter touchmove", function (event) {
        if (isDragging) {
          let targetIndex = index;

          // If touch, calculate target month from touch position
          if (event.type === "touchmove") {
            const touch = event.originalEvent.touches[0];
            const targetElement = document.elementFromPoint(
              touch.clientX,
              touch.clientY
            );
            const targetMonth = $(targetElement).data("month");
            if (targetMonth !== undefined) {
              targetIndex = targetMonth;
            }
          }

          const start = Math.min(dragStartIndex, targetIndex);
          const end = Math.max(dragStartIndex, targetIndex);
          selectedMonthRange = [];

          $(".month").removeClass("selected-range");
          for (let i = start; i <= end; i++) {
            selectedMonthRange.push(i);
            $(`.month[data-month="${i}"]`).addClass("selected-range");
          }

          // Update the combined selected box with the range
          const rangeText = `${monthNames[start]} - ${monthNames[end]} ${year}`;
          updateCombinedSelectedBox(rangeText);
        }
      });

      // Finish selection on mouse up or touch end
      monthElement.on("mouseup touchend", function () {
        isDragging = false;
        selectedYear = year;
        updateSelectedMonthBox();
      });
    });

    // Reset dragging when mouse or touch is released anywhere
    $(document).on("mouseup touchend", function () {
      isDragging = false;
    });
  }

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

  $("#prev-year").click(function () {
    currentYear--;
    generateMonthRangePicker(currentYear);
  });

  $("#next-year").click(function () {
    currentYear++;
    generateMonthRangePicker(currentYear);
  });

  generateDatePicker(displayMonth, displayYear);
  generateMonthRangePicker(currentYear);
  generateYearPicker(2024, 2026);

  $("#filter-date").on("click", function () {
    $("#date-picker-wrapper").show();
    $("#month-picker-wrapper, #year-picker-wrapper").hide();
  });

  $("#filter-month").on("click", function () {
    $("#month-picker-wrapper").show();
    $("#date-picker-wrapper, #year-picker-wrapper").hide();
  });

  $("#filter-year").on("click", function () {
    $("#year-picker-wrapper").show();
    $("#date-picker-wrapper, #month-picker-wrapper").hide();
  });
});
