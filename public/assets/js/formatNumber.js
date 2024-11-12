document.addEventListener("DOMContentLoaded", function () {
  // ฟังก์ชันจัดการเบอร์โทรศัพท์ให้เป็นรูปแบบ 086-290-1111
  function formatPhoneNumber(value) {
    value = value.replace(/\D/g, ""); // เอาตัวอักษรที่ไม่ใช่ตัวเลขออก
    let formattedValue = "";

    // เช็คว่าหมายเลขขึ้นต้นด้วย "02" และยาวอย่างน้อย 9 ตัว
    if (value.startsWith("02") && value.length >= 9) {
      formattedValue += value.substring(0, 2); // 02
      if (value.length > 2) {
        formattedValue += "-" + value.substring(2, 5); // 02-000
      }
      if (value.length > 5) {
        formattedValue += "-" + value.substring(5, 9); // 02-000-0000
      }
    } else {
      // กรณีที่ไม่ใช่เบอร์ขึ้นต้นด้วย "02" ฟอร์แมทเป็น 086-290-1111
      if (value.length > 0) {
        formattedValue += value.substring(0, 3); // 086
      }
      if (value.length > 3) {
        formattedValue += "-" + value.substring(3, 6); // 086-290
      }
      if (value.length > 6) {
        formattedValue += "-" + value.substring(6, 10); // 086-290-1111
      }
    }

    return formattedValue;
  }

  // ฟังก์ชันจัดการเลขบัตรประชาชนให้เป็นรูปแบบ 1-2345-67890-34-0
  function formatIdCard(value) {
    value = value.replace(/\D/g, ""); // เอาตัวอักษรที่ไม่ใช่ตัวเลขออก
    let formattedValue = "";

    if (value.length > 0) {
      formattedValue += value.substring(0, 1); // 1
    }
    if (value.length > 1) {
      formattedValue += "-" + value.substring(1, 5); // 1-2345
    }
    if (value.length > 5) {
      formattedValue += "-" + value.substring(5, 10); // 1-2345-67890
    }
    if (value.length > 10) {
      formattedValue += "-" + value.substring(10, 12); // 1-2345-67890-34
    }
    if (value.length > 12) {
      formattedValue += "-" + value.substring(12, 13); // 1-2345-67890-34-0
    }

    return formattedValue;
  }

  // จัดการการพิมพ์ input ของเบอร์โทรศัพท์
  const phoneInputs = document.querySelectorAll(".phone");
  phoneInputs.forEach(function (input) {
    input.addEventListener("input", function () {
      this.value = formatPhoneNumber(this.value);
    });
  });

  // จัดการการพิมพ์ input ของเลขบัตรประชาชน
  const idCardInputs = document.querySelectorAll(".idcard");
  idCardInputs.forEach(function (input) {
    input.addEventListener("input", function () {
      this.value = formatIdCard(this.value);
    });
  });

  // ฟังก์ชันบันทึกข้อมูลโดยลบเครื่องหมายขีดกลางออก
  document
    .querySelector(".saveForm")
    .addEventListener("submit", function (event) {
      event.preventDefault(); // ป้องกันการ submit แบบปกติ

      phoneInputs.forEach(function (input) {
        let rawPhoneNumber = input.value.replace(/\D/g, ""); // เอา - ออกจากเบอร์โทรศัพท์
        console.log("Saved phone number:", rawPhoneNumber);
      });

      idCardInputs.forEach(function (input) {
        let rawIdCardNumber = input.value.replace(/\D/g, ""); // เอา - ออกจากเลขบัตรประชาชน
        console.log("Saved ID card number:", rawIdCardNumber);
      });

      // คุณสามารถส่งข้อมูลไปยัง backend ด้วย ajax หรือฟังก์ชันอื่นๆ ที่นี่
    });
});
