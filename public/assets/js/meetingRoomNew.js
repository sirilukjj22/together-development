function goBackToMasterPage() {
  window.history.back(); // กลับไปยังหน้าก่อนหน้า
}

function updateCounts() {
  $("#count-long-tables").text($(".table-long").length); // อัปเดตจำนวนโต๊ะยาว
  $("#count-round-tables").text($(".table-round").length); // อัปเดตจำนวนโต๊ะกลม
  $("#count-chairs").text($(".chair").length); // อัปเดตจำนวนเก้าอี้
  $("#count-text").text($(".text-element").length); // อัปเดตจำนวนข้อความ
  $("#count-avatars").text($(".avatar-element").length); // อัปเดตจำนวนอวตาร
}

// เพิ่มกล่องสีเหลือง
let currentEditingBox = null;

// Function to add a yellow box
function addYellowBox(x = 50, y = 50) {
  const yellowBox = $("<div></div>")
    .addClass("yellow-box")
    .css({
      left: `${x}px`,
      top: `${y}px`,
      width: "100px",
      height: "100px",
      background: "rgb(248, 215, 70)",
      border: "2px solid black",
      borderRadius: "10px",
      position: "absolute",
      cursor: "move"
    })
    .append('<div class="resize-handle"></div>')
    .appendTo("#a4-container");

  // Make the box draggable and resizable
  // yellowBox.draggable({ containment: "#a4-container" }).resizable({
  //   handles: "se"
  // });

  yellowBox.draggable({ containment: "#room" }).resizable({
    handles: "se"
  });

  // Double-click to edit
  yellowBox.on("dblclick", function () {
    currentEditingBox = yellowBox;
    $("#box-width").val(parseInt(yellowBox.css("width"), 10));
    $("#box-height").val(parseInt(yellowBox.css("height"), 10));
    $("#box-bg-color").val(rgbToHex(yellowBox.css("background-color")));
    $("#box-border-color").val(rgbToHex(yellowBox.css("border-color")));
    $("#box-border-radius").val(parseInt(yellowBox.css("border-radius"), 10));
    $("#boxEditModal").modal("show");
  });
}

// Function to convert RGB to Hex
function rgbToHex(rgb) {
  const result = rgb
    .match(/\d+/g)
    .map((num) => parseInt(num, 10).toString(16).padStart(2, "0"));
  return `#${result.join("")}`;
}

// Add new yellow box
$("#add-yellow-box").on("click", function () {
  addYellowBox();
});

// Save changes to the box
$("#save-box-edit").on("click", function () {
  if (currentEditingBox) {
    currentEditingBox.css({
      width: `${$("#box-width").val()}px`,
      height: `${$("#box-height").val()}px`,
      "background-color": $("#box-bg-color").val(),
      "border-color": $("#box-border-color").val(),
      "border-radius": `${$("#box-border-radius").val()}px`
    });
    $("#boxEditModal").modal("hide");
    currentEditingBox = null;
  }
});

// ลบสไตล์เวลาค้าง
document.getElementById("clear-selection-box").addEventListener("click", () => {
  const selectionBoxes = document.querySelectorAll(".selection-box");
  selectionBoxes.forEach((box) => {
    box.remove(); // ลบกล่อง selection-box ออกจาก DOM
  });
  console.log("All selection-box styles cleared.");
});

document.addEventListener("click", (e) => {
  const isClickInsideClearBox = e.target.closest("#clear-selection-box");
  const isClickInsideSelectionBox = e.target.closest(".selection-box");

  // หากคลิกนอก clear-selection-box และ selection-box
  if (!isClickInsideClearBox && !isClickInsideSelectionBox) {
    const selectionBoxes = document.querySelectorAll(".selection-box");
    selectionBoxes.forEach((box) => {
      box.remove(); // ลบกล่อง selection-box ออกจาก DOM
    });
    console.log("All selection-box styles cleared.");
  }
});

//หมุนรูป
function rotateSelectedElements(angle) {
  selectedElements.forEach((el) => {
    // Retrieve the current rotation of the element
    const currentRotation = parseFloat(el.getAttribute("data-rotation")) || 0;
    const newRotation = (currentRotation + angle) % 360;

    // Apply the rotation
    el.style.transform = `rotate(${newRotation}deg)`;
    el.style.transformOrigin = "center";

    // Store the new rotation
    el.setAttribute("data-rotation", newRotation);
  });
}

// Rotate buttons
$("#rotate-selected-plus45").on("click", () => rotateSelectedElements(45));
$("#rotate-selected-minus45").on("click", () => rotateSelectedElements(-45));
$("#rotate-selected-plus1").on("click", () => rotateSelectedElements(1));
$("#rotate-selected-minus1").on("click", () => rotateSelectedElements(-1));

// ฟังก์ชันสำหรับเคลื่อนย้ายกล่อง
function moveSelectedElements(dx, dy) {
  selectedElements.forEach((el) => {
    const currentLeft = parseInt(el.style.left, 10) || 0;
    const currentTop = parseInt(el.style.top, 10) || 0;

    el.style.left = `${currentLeft + dx}px`;
    el.style.top = `${currentTop + dy}px`;
  });
}

// ลบทุกวัตถุที่อยู่ใน selectedElements ผ่านปุ่ม Delete
$("#delete-selected").on("click", function () {
  selectedElements.forEach((el) => $(el).remove());
  selectedElements.clear();
  updateCounts();
});

let copiedElements = []; // เก็บองค์ประกอบที่ถูกคัดลอก
// ฟังก์ชันสำหรับคัดลอกองค์ประกอบที่เลือก
function copySelectedElements() {
  if (selectedElements.size === 0) {
    alert("No elements selected to copy!");
    return;
  }
  copiedElements = Array.from(selectedElements).map((el) => {
    const newClone = el.cloneNode(true);
    newClone.classList.remove("selected");
    return newClone;
  });
}

function calculateRoomScale() {
  const roomWidthPx = $("#room").width();
  const roomConfig = rooms[$("#roomSelector").val()];
  return roomWidthPx / roomConfig.width; // คืนค่า roomScale
}

function convertToPx(value, roomScale) {
  return value * roomScale; // แปลงค่า (เช่น radius หรือ padding) เป็น pixel
}

// ฟังก์ชันสำหรับวางองค์ประกอบที่คัดลอก
function pasteCopiedElements() {
  if (copiedElements.length === 0) {
    alert("No elements copied!");
    return;
  }

  const roomScale = calculateRoomScale();

  const container = document.getElementById("a4-container");
  // Clear all previously selected elements
  selectedElements.forEach((el) => el.classList.remove("selected"));
  selectedElements.clear();
  copiedElements.forEach((el) => {
    const pastedElement = el.cloneNode(true); // Clone อีกครั้งเพื่อให้เป็นองค์ประกอบใหม่
    pastedElement.style.position = "absolute"; // ตั้งค่าให้เป็น Absolute Position
    pastedElement.style.top = `${parseInt(el.style.top, 10)}px`;
    // ขยับตำแหน่งเล็กน้อย
    // ค้นหาองค์ประกอบที่อยู่ภายใน table-set และตรวจสอบว่าเป็น round-table หรือ long-table
    const tableType = el
      .closest(".table-set")
      ?.querySelector(".table-round, .table-long");

    if (tableType && tableType.classList.contains("table-round")) {
      pastedElement.style.left = `${
        parseInt(el.style.left, 10) - 3 * roomScale
      }px`;
    } else if (tableType && tableType.classList.contains("table-long")) {
      pastedElement.style.left = `${
        parseInt(el.style.left, 10) - 1.8 * roomScale
      }px`;
    } else {
      pastedElement.style.top = `${
        parseInt(el.style.top, 10) + 2 * roomScale
      }px`;
      pastedElement.style.left = `${parseInt(el.style.left, 10)}px`;
    }
    // เพิ่มองค์ประกอบใหม่ใน a4-container
    container.appendChild(pastedElement);
    // เพิ่มใน selectedElements
    selectedElements.add(pastedElement);
    pastedElement.classList.add("selected"); // เพิ่มคลาส selected
    // ทำให้องค์ประกอบใหม่สามารถลากได้
    makeDraggable(pastedElement);
  });
}

// ปุ่ม Copy
document.getElementById("copy-selected").addEventListener("click", () => {
  copySelectedElements();
});

// ปุ่ม Paste
document.getElementById("paste-copied").addEventListener("click", () => {
  pasteCopiedElements();
  updateCounts();
});

// ข้อความ
$(function () {
  let isAddingText = false;
  let currentEditingElement = null;

  // เปิด Modal เพื่อเพิ่มหรือแก้ไขข้อความ
  const showModal = (textElement = null) => {
    isAddingText = !textElement;
    currentEditingElement = textElement;
    // กำหนดค่าใน Modal
    $("#edit-text").val(textElement ? $(textElement).text() : "");
    $("#edit-color").val(
      textElement ? rgbToHex($(textElement).css("color")) : "#333333"
    );
    $("#edit-bg-color").val(
      textElement ? rgbToHex($(textElement).css("backgroundColor")) : "#ffffff"
    );
    $("#edit-padding").val(textElement ? $(textElement).css("padding") : "5px");
    $("#edit-font-size").val(
      textElement ? $(textElement).css("fontSize") : "14px"
    );
    // แสดง Modal
    $("#editModal").modal("show");
  };

  // ปิด Modal
  const closeModal = () => {
    $("#editModal").modal("hide");
    isAddingText = false;
    currentEditingElement = null;
  };

  // Helper: Convert RGB to Hex
  const rgbToHex = (rgb) => {
    const rgba = rgb
      .replace(/^rgba?\(|\s+|\)$/g, "")
      .split(",")
      .map(Number);
    return `#${((1 << 24) + (rgba[0] << 16) + (rgba[1] << 8) + rgba[2])
      .toString(16)
      .slice(1)}`;
  };
  // Event: คลิกปุ่ม Add Text
  $("#add-text").on("click", () => showModal());

  // Event: บันทึกข้อความใหม่หรือแก้ไข
  $("#save-edit").on("click", function () {
    const newText = $("#edit-text").val().trim();
    const style = {
      color: $("#edit-color").val(),
      backgroundColor: $("#edit-bg-color").val(),
      padding: $("#edit-padding").val(),
      fontSize: $("#edit-font-size").val(),
      position: "absolute",
      left: "50px",
      top: "50px",
      cursor: "move"
    };

    if (isAddingText && newText) {
      // เพิ่มข้อความใหม่
      const textElement = $("<div></div>")
        .addClass("text-element")
        .text(newText)
        .css(style)
        .on("dblclick", function () {
          showModal(this); // ดับเบิลคลิกเพื่อแก้ไข
        });
      $("#a4-container").append(textElement);
      makeDraggable(textElement[0]);
      updateCounts();
    } else if (currentEditingElement) {
      // แก้ไขข้อความเดิม
      $(currentEditingElement).text(newText).css(style);
    }

    closeModal();
  });
});

// อัปโหลดรูป
$(function () {
  let currentEditingImage = null;
  // เปิด Modal สำหรับเพิ่มรูปภาพใหม่
  $("#add-uploaded-image").on("click", function () {
    currentEditingImage = null; // รีเซ็ตการแก้ไขรูปภาพ
    resetImageModal(); // รีเซ็ตค่าใน Modal
    $("#imageModal").modal("show");
  });

  // บันทึกหรืออัปเดตรูปภาพ
  $("#save-image").on("click", function () {
    const imageFile = $("#image-upload")[0].files[0]; // ไฟล์จาก input
    const imageUrl = $("#image-url").val().trim(); // URL จาก input
    const borderRadius = $("#image-border-radius").val().trim() || "0px";

    if (imageFile || imageUrl) {
      const image = new Image();

      const createNewImage = (src) => {
        image.src = src;
        image.onload = () => {
          // คำนวณอัตราส่วนภาพ
          const originalWidth = image.naturalWidth;
          const originalHeight = image.naturalHeight;
          const aspectRatio = originalWidth / originalHeight;

          const imageWidth = 100; // กำหนดความกว้างเริ่มต้นเป็น 100px
          const imageHeight = Math.round(imageWidth / aspectRatio); // คำนวณความสูงตามอัตราส่วน

          if (currentEditingImage) {
            // อัปเดตรูปภาพที่มีอยู่
            updateImageElement(
              currentEditingImage,
              src,
              imageWidth,
              imageHeight,
              borderRadius
            );
          } else {
            // เพิ่มรูปภาพใหม่
            addNewImageElement(src, imageWidth, imageHeight, borderRadius);
          }

          $("#imageModal").modal("hide"); // ปิด Modal
        };
      };

      if (imageFile) {
        // ใช้ FileReader สำหรับไฟล์
        const reader = new FileReader();
        reader.onload = function (e) {
          createNewImage(e.target.result); // ใช้ Base64 URL
        };
        reader.readAsDataURL(imageFile); // อ่านไฟล์เป็น Base64
      } else if (imageUrl) {
        // ใช้ URL โดยตรง
        createNewImage(imageUrl);
      }
    } else {
      alert("กรุณาอัปโหลดรูปภาพหรือใส่ URL."); // แจ้งเตือนเมื่อไม่มีไฟล์หรือ URL
    }
  });

  // อัปเดตรูปภาพที่มีอยู่
  function updateImageElement(imageElement, src, width, height, borderRadius) {
    $(imageElement)
      .attr("src", src)
      .css({
        width: `${width}px`,
        height: `${height}px`,
        borderRadius: borderRadius
      });
  }

  // เพิ่มรูปภาพใหม่
  function addNewImageElement(src, width, height, borderRadius) {
    const imageElement = $("<img>")
      .addClass("image-element")
      .attr("src", src)
      .css({
        position: "absolute",
        left: "50px",
        top: "50px",
        border: "2px dashed red", // เพิ่ม border เริ่มต้น
        width: `${width}px`,
        height: `${height}px`,
        borderRadius: borderRadius,
        padding: "2px"
      })
      .on("dblclick", function () {
        enableResizable(this); // ดับเบิลคลิกเพื่อเปิด/ปิด Resizable
      });

    $("#a4-container").append(imageElement); // เพิ่มรูปในห้อง
    makeDraggable(imageElement); // ทำให้ลากได้
    makeResizable(imageElement, width / height); // ส่งอัตราส่วนไปยัง makeResizable
  }

  // ฟังก์ชันทำให้ลากได้
  function makeDraggable(element) {
    $(element).draggable({
      start: function () {
        $(this).css("border", "2px dashed green");
      },
      stop: function () {
        $(this).css("border", "none");
      }
    });
  }

  // ดับเบิลคลิกเพื่อเปิด/ปิด Resizable
  function enableResizable(imageElement) {
    const $image = $(imageElement);
    if ($image.resizable("instance")) {
      $image.resizable("destroy"); // ปิด Resizable
      $image.css("border", "none"); // ลบ border เมื่อปิด Resizable
    } else {
      const aspectRatio = $image.width() / $image.height();
      makeResizable($image, aspectRatio); // เปิด Resizable พร้อมอัตราส่วน
      $image.css("border", "2px dashed red"); // เพิ่ม border เมื่อเปิด Resizable
    }
  }

  // ฟังก์ชันทำให้ปรับขนาดได้
  function makeResizable(element, aspectRatio) {
    $(element).resizable({
      containment: "#a4-container", // จำกัดการปรับขนาดให้อยู่ภายใน #a4-container
      handles: "n, e, s, w, ne, nw, se, sw", // กำหนดจุดจับมุม
      aspectRatio: aspectRatio // รักษาอัตราส่วนของรูปต้นฉบับ
    });
  }

  // รีเซ็ตค่าใน Modal
  function resetImageModal() {
    $("#image-url").val("");
    $("#image-upload").val("");
    $("#image-border-radius").val("0px");
  }
});

// กำหนดระยะห่าง

$(document).on("keydown", (e) => {
  const step = 2; // ระยะที่เคลื่อนที่ในแต่ละครั้ง (พิกเซล)

  if (e.ctrlKey || e.metaKey) {
    // กรณีที่กด Ctrl + ลูกศร หมุนวัตถุ
    switch (e.key) {
      case "ArrowUp": // Ctrl + ลูกศรขึ้น หมุน +45 องศา
        rotateSelectedElements(45);
        e.preventDefault();
        break;
      case "ArrowDown": // Ctrl + ลูกศรลง หมุน -45 องศา
        rotateSelectedElements(-45);
        e.preventDefault();
        break;
      case "ArrowRight": // Ctrl + ลูกศรขวา หมุน +1 องศา
        rotateSelectedElements(1);
        e.preventDefault();
        break;
      case "ArrowLeft": // Ctrl + ลูกศรซ้าย หมุน -1 องศา
        rotateSelectedElements(-1);
        e.preventDefault();
        break;
    }
  } else {
    // กรณีที่กดลูกศรธรรมดา เคลื่อนย้ายวัตถุ
    switch (e.key) {
      case "ArrowUp":
        moveSelectedElements(0, -step);
        e.preventDefault();
        break;
      case "ArrowDown":
        moveSelectedElements(0, step);
        e.preventDefault();
        break;
      case "ArrowLeft":
        moveSelectedElements(-step, 0);
        e.preventDefault();
        break;
      case "ArrowRight":
        moveSelectedElements(step, 0);
        e.preventDefault();
        break;
    }
  }
});

// รองรับการกดปุ่ม Delete หรือ Backspace บนคีย์บอร์ด
document.addEventListener("keydown", (e) => {
  const isInA4Container =
    e.target.closest("#a4-container") !== null ||
    document.activeElement.closest("#a4-container") !== null;

  if (
    (e.key === "Delete" || e.key === "Backspace") &&
    !e.metaKey &&
    !e.ctrlKey
  ) {
    selectedElements.forEach((el) => $(el).remove());
    selectedElements.clear();
    updateCounts();
    if (isInA4Container) {
      e.preventDefault(); // ป้องกันการวางข้อความทั่วไปเฉพาะใน #a4-container
    }
  }
});

// คีย์บอร์ด Copy และ Paste

document.addEventListener("keydown", (event) => {
  const isInA4Container =
    event.target.closest("#a4-container") !== null ||
    document.activeElement.closest("#a4-container") !== null;

  if (event.ctrlKey || event.metaKey) {
    switch (event.key.toLowerCase()) {
      case "c":
        copySelectedElements();
        if (isInA4Container) {
          event.preventDefault(); // ป้องกันการคัดลอกข้อความทั่วไปเฉพาะใน #a4-container
        }
        break;
      case "v":
        pasteCopiedElements();
        updateCounts();
        if (isInA4Container) {
          event.preventDefault(); // ป้องกันการวางข้อความทั่วไปเฉพาะใน #a4-container
        }
        break;
    }
  }
});
