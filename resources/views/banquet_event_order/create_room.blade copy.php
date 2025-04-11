<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Room Designer with Group Rotation</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

        <!-- Bootstrap CSS -->
        <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        />
        <!-- jQuery UI CSS -->
        <link
        rel="stylesheet"
        href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css"
        />

        <!-- google font -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet"
        />

        <!-- font -->
        <!-- icon font -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



        <!-- เชื่อมโยง CSS ของ UICONS -->
        <link
        rel="stylesheet"
        href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css"
        />

        <!-- icon font -->
        <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet"
        />
        <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        />

        <link rel="stylesheet" href="{{ asset('assets/src/global.css') }}?v={{ time() }}" />

        <style>
            body {
                font-family: "KoHo", sans-serif;
            }

            .table-set {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .no-select {
                user-select: none;
                pointer-events: none; /* ป้องกันการคลิก */
            }

            .room {
                transition: all 0.5s ease;
                margin: auto;
                position: relative;
                background-color:white;
                border: 1px solid rgb(235, 233, 233);
            }




            .recording-room {
                white-space: wrap;
                transform: rotate(0deg);
            }

            .recording-room-text {
                white-space: nowrap;
                width: max-content;
            }

            .table-long,
            .table-round,
            .chair {
                position: absolute;
                font-size: 12px;
                cursor: pointer;
                user-select: none;
                border: 1px solid #001801;
            }


            .image-element {
                position: absolute;
                cursor: move;
                object-fit: cover; /* ทำให้ภาพปรับขนาดแบบไม่ผิดสัดส่วน */
                border-radius: 5px;
            }


            .selected {
                border: 1px dashed #04626e !important;
            }

            .selection-box {
                position: absolute;
                border: 1px dashed orange;
                background: rgba(255, 166, 0, 0.212);
                display: none;
            }

            .text-element {
                overflow-wrap: break-word;
                white-space: nowrap;
                z-index: 3;
                border:1px solid grey;
                border-radius: 7px;
            }

            .group-bt {
                display: flex;
                gap: 0.3em;
                margin-bottom: 1em;
            }

            .group-bt > button {
                flex-grow: 1;
                border: none;
                background-color: white;
                border-radius: 5px;
                text-align: center;
                user-select: none;
                border: rgb(134, 170, 161) 1px solid;
                padding: 10px;
                background-color: #ffffff;
            }
            .group-bt > button:focus {
                transform: translateY(-2px);
            }


            .card-sum {
                border: rgb(28, 1, 1) 1px solid;
                color:black;
                padding: 5px;
                border-radius: 7px;
                background-color: white;
                margin-bottom: 1em;
                display: flex;
            }

            .card-sum p {
                display: grid;
                margin: 0px auto;
                text-align: center;
            }


            header {
                background-color: #2C7F7A;
                color: white;
                padding: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            header h1 {
                margin: 0;
                font-size: 1.5rem;
            }

                footer {
                background-color: #2C7F7A;
                color: white;
                text-align: center;
                padding: 20px;
                font-size: 0.9rem;
            }

            .sidebar {
                width: 380px;
                background-color: #a1cfcf;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                padding: 20px;
                overflow-y: auto;

            }

            .room-container {
                flex: 1;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f4f7fc;
                position: relative;
            }


            #a4-container {
                width: 1123px;
                height: 794px;
                background: white;
                border: 2px solid #ccc;
                position: relative;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .door
            {
                position: absolute;
                border: 2px solid #d3d3d3;
                display: flex;
                justify-content: center;
                align-items: center;
                font-weight: bold;
                color: #1e6262;
                background-color: #a16306;
                color: black;
            }
            .stage:hover {
                cursor: grab; /* เปลี่ยนเคอร์เซอร์เมื่อ hover */
                }

                .stage:active {
                cursor: grabbing; /* เปลี่ยนเคอร์เซอร์เมื่อคลิก */
                }



            /* Grid lines */
            .line-grid-A4 {
                position: absolute;

                width: 100%;
                height: 100%;
                left: 50%;
                transform: translateX(-50%);
                background-size: 50px 50px; /* Grid cell size */
                background-image: linear-gradient(
                    to right,
                    #85d6bd 1px,
                    transparent 1px
                ),
                linear-gradient(to bottom, #ddd 1px, transparent 1px) !important;
                pointer-events: none;
                z-index: 8;
                border: #2C7F7A 2px solid;
            }

            .sss {
            display: flex;
            flex-grow: 1;
            gap:0px;
            border:1px solid grey;
            border-radius: 5px;
            overflow: hidden;
            }

            .sss input {
            border:none;
            }

            .sss > input:nth-child(1) {
            border-right:rgb(161, 159, 159) 1px solid;
            }
        </style>
        <style>
            .aa {
            display: flex;
            gap: 0.4em;
            }

            .aa > label {
            border: rgb(4, 171, 126) 2px solid;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background-color: rgba(222, 244, 241, 0.323);
            color:rgb(78, 75, 75);

            font-weight: 450;
            }

            .aa > label input {
            display: none;
            }




            .aa > label input:checked + span {
            color: white;
            color: #035c51;
            }

            .aa > label input:focus + span {
            /* border: 2px solid #007bff; */
            }

        </style>
    </head>
    <body>
        <header class="d-flex justify-content-between">
            <h1>Room Layout Manager</h1>
            <div>
                <button type="button" onclick="goBackToMasterPage()" class="p-2"  style="border: none; border-radius: 5px">Back</button>
                <button type="button" style="border: none; border-radius: 5px"  id="print-pdf" class="p-2"> Save</button>
            </div>
        </header>
        <div class="d-flex">
            <section class="sidebar" style="position: relative;">
                <button  type="button"  class="ss"  data-bs-toggle="modal" data-bs-target="#manualModal"style=" position: absolute;bottom: 5px;right: 5px;border: 1px grey solid; background-color: #71b6b1; color: white;">
                    คู่มือการใช้โปรแกรม
                </button>
                <div>
                    <label for="roomSelector no-select" style="font-weight: 650;">Select Room:</label>
                    <input type="checkbox" id="tg-lengeAndPoint" checked>
                    Show Lengths and Points
                    <select id="roomSelector" class="form-select py-2 text-bold" style="height: max-content;font-size: 1.2em;">
                        <option value="room1">Room 1</option>
                        <option value="room2">Room 2</option>
                        <option value="room3">Room 3</option>
                        <option value="room4">Custom Room</option>
                        <option value="roomCircle">Circle Room</option>
                    </select>
                    <div class="d-flex gap-1 my-2">
                        <button type="button" id="addRoomButton" class="btn btn-success flex-grow-1">Add Room</button>
                        <button type="button" id="editRoomButton" class="btn btn-warning flex-grow-1">Edit Room</button>
                        <button type="button" id="deleteRoomButton" class="btn btn-danger flex-grow-1">Delete Room</button>
                    </div>
                    <div><strong class="no-select">Summary:</strong></div>
                    <div class="card-sum no-select">
                        <p>
                            <span id="count-long-tables">0</span>
                            <img src="{{ asset('image/meetingRoom/side-table.png') }}" alt="" width="30" />
                        </p>
                        <p>
                            <span id="count-round-tables">0</span>
                            <img src="{{ asset('image/meetingRoom/round-table.png') }}" alt="" width="30" />
                        </p>
                        <p>
                            <span id="count-chairs">0</span>
                            <img src="{{ asset('image/meetingRoom/dining.png') }}" alt="" width="30" />
                        </p>
                    </div>
                    <div><strong class="no-select">Add:</strong></div>
                    <div class="group-bt">
                        <button id="add-long-tables" type="button">
                            <span>
                                <img src="{{ asset('image/meetingRoom/side-table.png') }}" alt="" width="30" />
                            </span>
                        </button>
                        <button id="add-round-tables" type="button">
                            <span>
                                <img src="{{ asset('image/meetingRoom/round-table.png') }}" alt="" width="30" />
                            </span>
                        </button>
                        <button id="add-text" type="button">
                            <span>
                                <i class="fi fi-rr-text" style="font-size: 24px"></i>
                            </span>
                        </button>
                        <button id="add-uploaded-image" type="button">
                            <img src="{{ asset('image/meetingRoom/add-image.png') }}" alt="" width="30" />
                        </button>
                        <button id="add-yellow-box" style="background-color: rgb(248, 215, 70); color: black">
                            Add Box
                        </button>
                    </div>
                    <div class="group-bt">
                        <button type="button" id="copy-selected">Copy</button>
                        <button type="button" id="paste-copied">Paste</button>
                    </div>
                    <div class="group-bt">
                        <button type="button" id="clear-selection-box" style="border: 3px dashed orange; background: rgb(247, 225, 185);">Clear drag</button>
                        <button type="button" id="delete-selected" style="color:red;border:red 2px solid;">Delete Select</button>
                        <button type="button" id="clear-all" style="background-color: #ff6347; color: white">Clear All</button>
                        <button type="button" id="select-all" style="background-color: #0f793d; color: white">Select All</button>
                    </div>
                    <div><strong class="no-select">manage:</strong></div>
                    <div class="group-bt">
                        <button id="rotate-selected-plus45" type="button">
                            <i class="fas fa-sync-alt" style="font-size: 12px; color: black"></i>+45°
                        </button>
                        <button id="rotate-selected-minus45" type="button">
                            <i class="fas fa-sync-alt" style="font-size: 12px; color: black"></i>-45°
                        </button>
                        <button id="rotate-selected-plus1" type="button">
                            <i class="fas fa-sync-alt"style="font-size: 12px; color: black"></i>+1°
                        </button>
                        <button id="rotate-selected-minus1" type="button">
                            <i class="fas fa-sync-alt" style="font-size: 12px; color: black"></i>-1°
                        </button>
                    </div>
                    <div>
                        <div>
                            <b class="no-select">การจัดแถวและช่องว่าง:</b>
                        </div>
                        <div class="group-bt w-100">
                            <input type="number" id="spacing" value="0"style="border-radius: 5px; outline: none; border: none; width: 60px" class="flex-grow-1 text-center" placeholder="ระยะห่าง (เมตร)"/>
                            <button type="button" class="flex-grow-1" onclick="arrangeElementsInCircle(parseFloat(document.getElementById('spacing').value))">
                                Circle
                            </button>
                            <button type="button" class="flex-grow-1" onclick="arrangeElementsInXAxis(parseFloat(document.getElementById('spacing').value))">
                                แนวนอน
                            </button>
                            <button type="button" class="flex-grow-1" onclick="arrangeElementsInYAxis(parseFloat(document.getElementById('spacing').value))">
                                แนวตั้ง
                            </button>
                        </div>
                        <div class="group-bt mb-0">
                            <button type="button" id="toggle-line-grid-A4">แสดงเส้น grid</button>
                        </div>
                    </div>
                </div>
            </section>
            <div class="room-container" >
                <div id="a4-container">
                    <div style="position: absolute;top:1em;" > <img src="{{ asset('image/Logo-tg2.png') }}" alt="" width="70"></div>
                    <div class="line-grid-A4" id="line-grid-A4"></div>
                    <div id="room" class="room"></div>
                    <div class="px-4 w-100" style="position: absolute;bottom:5px">
                        <div class="d-grid mb-2" style="grid-template-columns: 1fr 160px;">
                            <div>
                                <li><strong>Event Time:</strong> <span id="event-time"></span></li>
                                <li><strong>Place:</strong> <span id="place"></span></li>
                                <li><strong>Set Up:</strong> <span id="setup"></span></li>
                            </div>
                            <div style="font-size: 14px;">
                                <div class="text-start">
                                    <div class="text-center">Customer Signature</div>
                                        <li class="border-bottom" style="min-height: 43px"></li>
                                        <li>Date:</li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <p class="mb-0">
                Copyright
                <span class="d-none d-sm-inline-block">
                <script>
                    document.write(/\d{4}/.exec(Date())[0]);
                </script>
                © Together Development.
                </span>
            </p>
        </footer>
        <!-- Modal สำหรับเพิ่ม/แก้ไขห้อง -->
        <div id="editRoomModal" class="modal fade" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoomModalTitle">Edit Room Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div>
                        <!-- ชื่อห้อง -->
                        <div class="mb-3">
                            <label for="roomName" class="form-label">Room Name</label>
                            <input type="text" id="roomName" class="form-control" placeholder="Enter room name">
                        </div>
                        <div class="mb-3">
                            <label for="roomType" class="form-label">RoomType</label>
                            <select id="roomType" class="form-select">
                                <option value="rectangle">Rectangle</option>
                                <option value="polygon">Custom</option>
                                <option value="circle">Room Circle</option>
                            </select>
                        </div>
                        <!-- ขนาดห้อง -->
                        <div class="d-grid-2column">
                            <div class="mb-3">
                                    <label for="roomWidth" class="form-label">Room Width (meters)</label>
                                <input type="number" id="roomWidth" class="form-control" placeholder="Enter room width">
                            </div>
                            <div class="mb-3">
                                <label for="roomHeight" class="form-label">Room Height (meters)</label>
                                <input type="number" id="roomHeight" class="form-control" placeholder="Enter room height">
                            </div>
                        </div>
                        <!-- Edit/Add Room Modal -->
                        <div id="polygonPointsContainer" class="mb-3 p-2" style="display: none;background-color: #b4e4e1;">
                            <div class="flex-between nowrap">
                                <h5 style="color: #014a4a;">กำหนดจุด มุมห้อง</h5>
                                <div class="d-flex gap-2">
                                    <h5 for="doorList" class="form-label" style="color:#04626e;"><button type="button" id="addPointButton" class="btn text-white" style="background-color: #07a1a1;">+ Add มุมห้อง</button></h5>
                                    <h5 type="button" id="openHowToModalButton" class="btn btn-secondary">How to</h5>
                                </div>
                            </div>
                            <div id="pointsList" class="d-grid-2column gap-2"></div>
                        </div>
                        <div class="mb-3">
                            <h5 for="stageList" class="form-label" style="color:#04626e;">
                                <button type="button" id="addStageButton" class="btn mt-2 text-white" style="background-color: #07a1a1;">+ Add Stage</button>
                            </h5>
                            <div id="stageList"></div>
                        </div>
                        <!-- ประตู -->
                        <div class="mb-3">
                            <h5 for="doorList" class="form-label" style="color:#04626e;"><button type="button" id="addDoorButton" class="btn mt-2 text-white" style="background-color: #07a1a1;">+ Add Door</button></h5>
                        <div id="doorList"></div>

                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveRoomChanges" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal คูมือ -->
        <div class="modal fade"id="manualModal"tabindex="-1"aria-labelledby="manualModalLabel"aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header" style="background-color: #2C7F7A;">
                        <h4 class="modal-title text-white">คู่มือการใช้งาน Room Layout Manager</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"aria-label="Close"></button>
                    </div>
                    <!-- Body -->
                    <div class="modal-body in-modal">
                        <div class="container">
                            <!-- Title -->
                            <h5 class="text-center mb-4">
                                คู่มือการใช้งาน Room Layout Manager
                            </h5>

                            <!-- Overview -->
                            <section>
                            <h5 class="section-title">1. ภาพรวมของโปรแกรม</h5>
                            <p>
                                Room Layout Manager เป็นโปรแกรมที่ช่วยคุณจัดการและออกแบบผังห้อง
                                เหมาะสำหรับการจัดประชุม สัมมนา หรือกิจกรรมที่ต้องการรูปแบบผังห้องที่กำหนดเอง
                            </p>
                            </section>

                            <style>
                            .bt-manual Button {
                                border-radius: 7px;
                                margin-bottom: 7px;
                                background-color: #04626e;
                                color:white;
                            }
                            </style>

                            <!-- Buttons Explanation -->
                            <section class="bt-manual">
                            <h5 class="section-title">2. รายละเอียดปุ่มการใช้งาน</h5>

                            <!-- Room Management Buttons -->
                            <h5>2.1 ปุ่มจัดการห้อง</h5>
                            <ul>
                                <li><Button>Select Room</Button> เลือกห้องที่ต้องการจากรายการ</li>
                                <li><Button>Add Room</Button> เพิ่มห้องใหม่และกำหนดขนาดห้อง พร้อมทั้งกำหนดขนาดเวที ประตูและตำแหน่งที่ประตูอยู่</li>
                                <li><Button>Edit Room</Button> แก้ขนาดห้อง เวที และตำแหน่งประตู</li>
                                <li><Button>Delete Room</Button> ลบห้องที่เลือก</li>
                            </ul>

                            <!-- Add Elements -->
                            <h5>2.2 ปุ่มเพิ่มองค์ประกอบ</h5>
                            <ul>
                                <li><strong><img src="{{ asset('image/meetingRoom/side-table.png') }}" alt="" width="30" /></strong> เพิ่มโต๊ะยาวพร้อมเก้าอี้ / โต๊ะอย่างเดี่ยว / เก้าอี้อย่างเดี่ยว </li>
                                <li><strong><img src="{{ asset('image/meetingRoom/round-table.png') }}" alt="" width="30" /></strong> เพิ่มโต๊ะกลมพร้อมเก้าอี้  / โต๊ะอย่างเดี่ยว / เก้าอี้อย่างเดี่ยว</li>
                                <li><strong><i class="fi fi-rr-text" style="font-size: 24px"></i></strong> เพิ่มกล่องข้อความ กด Double click หากต้องการแก้ไขข้อความ</li>
                                <li><strong><img src="{{ asset('image/meetingRoom/add-image.png') }}" alt="" width="30" /></strong> เพิ่มรูปภาพ เมื่อเพิ่มภาพเสร็จแล้วกด Double click เพื่อเปลี่ยน <span style="color: #02498b;">โหมดลาก</span> หรือ <span style="color: #02498b;">โหมดแก้ไขขนาด</span> </li>
                                <li class="ml-3"><span style="color:#814505;">ข้อสังเกต เมื่อรูปอยู่ในโหมดแก้ไขจะมีเส้นรอยประสีแดงครอบรูป และไม่สามารถลากเพื่อย้ายที่ได้</span></li>
                                <li class="text-dark"><button style="background-color: yellow;"class="text-dark">Add Box</button><span class="mx-2"> เพิ่มกล่อง หลังจากเพิ่มแล้วต้องการแก้ไขให้ Double click ที่กล่องเพื่อแก้ไขสีพื้นหลัง และ ความโค้งของกล่อง </span></li>

                            </ul>
                            <div style="border:1px solid rgb(98, 151, 151);background-color: rgba(127, 207, 201, 0.232);border-radius: 7px;" class="mb-4 p-3">
                                <li style="color: #04626e;"><strong>เลือกรายการเดียว : </strong>กดตรง element ค้างไว้แล้วลากได้เลย</li>
                                <li style="color: #04626e;"><strong>เลือกเป็นกลุ่ม : </strong>กดลากเมาส์เพื่อคลุม Element ที่ต้องการเลือก หรือกด Ctrl ค้างไว้ แล้ว Click ตรง Element ที่ต้องการเลือกเสร็จแล้วปล่อย Ctrl ก็จะได้ Group Element ไปยังตำแหน่งที่ต้องการ</li>
                                <li><b>เมื่อ element ถูกเลือกแล้วจะสังเกตเห็นเส้นเป็นรอยประ</b></li>
                                </div>

                            <!-- Manage Elements -->
                            <h5>2.3 ปุ่มจัดการองค์ประกอบ</h5>
                            <ul>
                                <li><Button>Copy</Button> or (Ctrl/Cmd + C) คัดลอกองค์ประกอบ</li>
                                <li><Button>Past</Button> or (Ctrl/Cmd + V) วางองค์ประกอบ</li>
                                <li><Button>Delete Selected</Button> or (Delete) :  ลบองค์ประกอบที่เลือก</li>
                                <li><Button>Clear Drag</Button> ลบการลากค้าง</li>
                                <li><Button>Clear All</Button> ลบทุกองค์ประกอบ</li>
                            </ul>

                            <!-- Rotate Elements -->
                            <h5>2.4 ปุ่มหมุนองค์ประกอบ</h5>
                            <ul>
                                <li><Button>+45°</Button> or (Ctrl/Cmd + ลูกศรขึ้น) หมุน +45 องศา</li>
                                <li><Button>-45°</Button> or (Ctrl/Cmd + ลูกศรลง) หมุน -45 องศา</li>
                                <li><Button>+1°</Button> or (Ctrl/Cmd + ลูกศรขวา) หมุน +1 องศา</li>
                                <li><Button>-1°</Button> or (Ctrl/Cmd + ลูกศรซ้าย) หมุน -1 องศา</li>
                            </ul>

                            <!-- Rotate Elements -->
                            <h5>2.5 เลื่อนองค์ประกอบ</h5>
                            <ul>
                                <li>(ลูกศรขึ้น) เลื่อนขึ้น</li>
                                <li>(ลูกศรลง) เลื่อนลง</li>
                                <li>(ลูกศรขวา) เลื่อนขวา</li>
                                <li>(ลูกศรซ้าย) เลื่อนซ้าย</li>
                            </ul>

                            <!-- Grid and Alignment -->
                            <h5>2.6 การจัดระเบียบและเส้น Grid</h5>
                            <ul>
                                <li><Button>แสดงเส้น Grid</Button> ช่วยให้จัดองค์ประกอบได้ง่าย โดยแต่ละช่องกริดจะมีหน่วยเป็น 1m</li>
                                <li><Button>Circle</Button> / <Button>แนวนอน</Button> / <Button>แนวตั้ง</Button> </strong> จัดแถวให้อยู่ในรูปแบบวงกลม แนวนอนหรือแนวตั้ง โดยกำหนดช่องว่างในช่อง input ข้างหน้า Button</li>
                            </ul>

                            <!-- Save and Export -->
                            <h5>2.7 การบันทึกและส่งออก</h5>
                            <ul>
                                <li><strong>Save:</strong> บันทึกข้อมูลและการจัดวาง</li>
                                <li><strong>Example PDF:</strong> สร้างตัวอย่าง PDF</li>
                            </ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Table -->
        <div class="modal fade"id="addItemModal"tabindex="-1"aria-labelledby="addItemModalLabel"aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Add Items</h5>
                        <button type="button"  class="btn-close"  data-bs-dismiss="modal"aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="aa">
                            <label>
                                <input type="radio" name="addType" value="table-and-chair" checked />
                                <span>Set Table</span>
                            </label>
                            <label>
                                <input type="radio" name="addType" value="table-only" />
                                <span>Table Only</span>
                            </label>
                            <label>
                                <input type="radio" name="addType" value="chair-only" />
                                <span>Chair Only</span>
                            </label>
                        </div>

                        <div id="itemCountWrapper" class="my-3">
                            <label for="itemCount" class="form-label">Number of Tables</label>
                            <input type="number" class="form-control"  id="itemCount" placeholder="Enter number of tables"/>
                        </div>
                            <div id="chairCountWrapper" class="mb-3">
                            <label for="chairCount" class="form-label">Number of Chairs per Table</label>
                            <input type="number" class="form-control"  id="chairCount" placeholder="Enter number of chairs"/>
                        </div>
                        <div id="chairOnlyCountWrapper" class="mb-3" style="display: none">
                            <label for="chairOnlyCount" class="form-label">Number of Only Chairs</label>
                            <input type="number" class="form-control"  id="chairOnlyCount" placeholder="Enter number of standalone chairs"/>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tableColor">Table Color:</label>
                            <input type="color" id="tableColor" value="#ffffff" />
                            <label for="chairColor">Chair Color:</label>
                            <input type="color" id="chairColor" value="#ffffff" />
                        </div>

                        <div class="form-group">
                            <label class="mb-2" for="gapValue">ระยะห่างระหว่างโต๊ะ (px):</label>
                            <input type="number" class="form-control"  id="gapValue" placeholder="ระบุระยะห่าง"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"class="btn btn-secondary">Cancel</button>
                        <button type="button" class="btn btn-primary" id="addItemConfirm">Add Items</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal แก้ไขข้อความ -->
        <div class="modal fade"id="editModal"tabindex="-1"aria-labelledby="editModalLabel"aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Add/Edit Text</h5>
                        <button type="button"class="btn-close"data-bs-dismiss="modal"aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-text" class="form-label">Text</label>
                            <input type="text"id="edit-text"class="form-control"placeholder="Enter text"/>
                        </div>
                        <div class="d-flex">
                            <div class="mb-3 flex-grow-1">
                                <label for="edit-color" class="form-label">Text Color</label>
                                <input type="color"id="edit-color"class="form-control form-control-color "placeholder="Enter text"/>
                            </div>
                            <div class="mb-3 flex-grow-1">
                                <label for="edit-bg-color" class="form-label">Background Color</label>
                                <input type="color" id="edit-bg-color" class="form-control form-control-color"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-font-size" class="form-label">Font Size</label>
                            <input type="text" id="edit-font-size"class="form-control"placeholder="24px"/>
                        </div>
                        <div class="mb-3">
                            <label for="edit-padding" class="form-label">Padding</label>
                            <input type="text" id="edit-padding" class="form-control" placeholder="5px"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save-edit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal แก้ไขภาพ -->
        <div class="modal fade"id="imageModal"tabindex="-1"aria-labelledby="imageModalLabel"aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Add/Edit Image</h5>
                        <button type="button"class="btn-close"data-bs-dismiss="modal"aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="image-url" class="form-label">Image URL</label>
                            <input type="text"id="image-url"class="form-control"placeholder="Enter image URL"/>
                        </div>
                        <div class="mb-3">
                            <label for="image-upload" class="form-label">Upload Image</label>
                            <input type="file" id="image-upload" class="form-control" />
                        </div>
                        <div class="mb-3" hidden>
                            <label for="image-width" class="form-label">Width</label>
                            <input type="text"id="image-width"class="form-control"placeholder="e.g., 100px"/>
                        </div>
                        <div class="mb-3" hidden>
                            <label for="image-height" class="form-label">Height</label>
                            <input type="text"id="image-height"class="form-control"placeholder="e.g., 100px"/>
                        </div>
                        <div class="mb-3">
                            <label for="image-padding" class="form-label">Padding</label>
                            <input type="text" id="image-padding" class="form-control" placeholder="e.g., 100px"/>
                        </div>
                        <div class="mb-3">
                            <label for="image-border-radius" class="form-label">Border Radius</label>
                            <input type="text" id="image-border-radius" class="form-control" placeholder="e.g., 10px"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save-image" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal สำหรับการอัปโหลด -->
        <div id="imageModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Image</h5>
                        <button type="button" class="close"data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" id="image-upload" accept="image/*" />
                        <input type="text" id="image-url" placeholder="Or enter image URL"/>
                        <input type="text"id="image-border-radius"placeholder="Border Radius (e.g., 10px)" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save-image" class="btn btn-primary">Save</button>
                        <button type="button"class="btn btn-secondary"data-dismiss="modal">  Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal สำหรับแก้ไขกล่อง -->
        <div class="modal fade"id="boxEditModal"tabindex="-1"aria-labelledby="boxEditModalLabel"aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="boxEditModalLabel">Edit Yellow Box</h5>
                        <button  type="button" class="btn-close" data-bs-dismiss="modal"aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="box-width" class="form-label">Width (px)</label>
                            <input type="number" id="box-width" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="box-height" class="form-label">Height (px)</label>
                            <input type="number" id="box-height" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="box-bg-color" class="form-label">Background Color</label>
                            <input type="color" id="box-bg-color" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="box-border-color" class="form-label">Border Color</label>
                            <input type="color"id="box-border-color"class="form-control"/>
                        </div>
                        <div class="mb-3">
                            <label for="box-border-radius" class="form-label">มุมโค้งกล่อง (px)</label>
                            <input type="number"id="box-border-radius"class="form-control"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"class="btn btn-secondary"data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save-box-edit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal สำหรับอธิบายวิธีการ -->
        <div class="modal fade" id="howToModal" tabindex="-1" aria-labelledby="howToModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="howToModalLabel">วิธีการใส่จุดสำหรับการกำหนดรูปทรงห้อง</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body ">
                        <img src="{{ asset('image/meetingRoom/ex1.png') }}" alt="">
                        <p><strong>กดปุ่ม “+ จุดมุมห้อง”</strong></p>
                        <p>
                        เมื่อกดปุ่มนี้ จะเพิ่มช่องกรอกข้อมูลสำหรับระบุพิกัดของจุดในรูปทรงห้อง<br />
                        ช่องจะประกอบด้วยพิกัด X (m) และ Y (m) ในหน่วยเมตร
                        </p>
                        <p><strong>กรอกค่าพิกัด X และ Y</strong></p>
                        <ul>
                        <li>พิกัด X (m) คือระยะในแนวนอนจากต้นกำเนิด (0,0) ของพื้นที่ห้อง</li>
                        <li>พิกัด Y (m) คือระยะในแนวตั้งจากต้นกำเนิด (0,0) ของพื้นที่ห้อง</li>
                        </ul>
                        <p><strong>ใส่จุดตามลำดับ นับตามเข็มนาฬิกา</strong></p>
                        <ul>
                        <li>จุดที่ 1 จะเริ่มที่ตำแหน่ง (0,0) เสมอ</li>
                        <li>จุดต่อไปสามารถระบุพิกัดเพื่อสร้างรูปทรงห้องที่ต้องการ เช่น สี่เหลี่ยม หรือรูปหลายเหลี่ยม</li>
                        </ul>
                        <p><strong>ตรวจสอบความถูกต้อง</strong></p>
                        <ul>
                        <li>ตรวจสอบให้แน่ใจว่าพิกัดที่กรอกสอดคล้องกับรูปทรงห้องที่ต้องการ</li>
                        <li>หากต้องการลบจุดที่กรอกผิด สามารถกดปุ่มลบด้านข้างจุดนั้นได้  <span class="p-1" style="background-color: rgb(209, 214, 209);border-radius: 5px;"><i class="fa fa-trash-o"></i></span></li>
                        </ul>
                        <p><strong>ตัวอย่างการกำหนดจุด</strong></p>
                        <ul>
                        <li><strong style="color: #058181;">ตัวอย่างขนาดห้อง :: ห้องกว้าง 24m ยาว 8m</strong></li>
                        <li>
                            <strong>สี่เหลี่ยมผืนผ้า:</strong> กรอกพิกัด X, Y ตามมุมห้องทั้ง 4 จุด เช่น (0,0), (24,0), (24,8), (0,8)
                        </li>

                        <img
                            src="{{ asset('image/meetingRoom/exRoom2.png') }}"
                            alt="Meeting Room"
                            id="previewImage"
                            style="width: 100%; cursor: pointer;"
                        >
                        <li><strong style="color: #058181;">ตัวอย่างขนาดห้อง :: ห้องกว้าง 100m ยาว 30m</strong></li>
                        <li>
                            <strong>ห้องหลายเหลี่ยม:</strong> กรอกพิกัด X, Y ตามมุมห้องทั้ง 11 จุด เช่น (0,0), (100,0),(100,30), (80,0),(80,22),(64,22),(64,30),(40,30),(64,25),(25,25),(0,30)
                        </li>

                        <h6 class="mt-2">Example 2</h6>
                        <img
                            src="{{ asset('image/meetingRoom/exRoom1.png') }}"
                            alt="Meeting Room"
                            id="previewImage"
                            style="width: 100%; cursor: pointer;"
                        >

                        </ul>
                        <p><strong>กด “Save Changes” เมื่อกรอกข้อมูลครบถ้วน</strong></p>



                        <p>
                        ระบบจะบันทึกข้อมูลจุดเหล่านี้เพื่อใช้ในการสร้างหรือแก้ไขห้องตามที่คุณออกแบบ
                        </p>
                        <p><strong>เมื่อได้ผังห้องแล้วหากต้องการแก้ไขเพิ่ม  </strong></p>
                        <p>
                        1. สามารถเข้ามาแก้ไขโดยกด <button class="btn  bg-warning">Edint Button</button> แล้วแก้ไขค่า
                        </p>
                        <p>
                        2. สามารถลากจุดไปยังจุดที่ต้องการได้เลย <i class="fa fa-circle" style="color:purple;"></i>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery, Bootstrap JS, and jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="{{ asset('assets/js/meetingRoomNew.js') }}"></script>
    {{-- <script>
        document.getElementById("openHowToModalButton").addEventListener("click", function () {
            const howToModal = new bootstrap.Modal(document.getElementById("howToModal"));
            howToModal.show();
        });
        document.getElementById("print-pdf").addEventListener("click", () => {
            const container = document.getElementById("a4-container");
            // Ensure the container matches A4 dimensions
            container.style.width = "29.7cm"; // A4 width in cm
            container.style.height = "21cm"; // A4 height in cm
            html2canvas(container, { scale: 2,useCORS: true }).then((canvas) => {
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jspdf.jsPDF({
                    orientation: "landscape",
                    unit: "cm", // Use centimeters as the unit
                    format: [29.7, 21] // A4 dimensions in cm (landscape)
                });
                pdf.addImage(
                    imgData,
                    "PNG",
                    0,
                    0,
                    29.7, // A4 width in cm
                    21 // A4 height in cm
                );
                pdf.save("room-layout.pdf");
            });
        });
        const room = document.getElementById("room");
        const selectedElements = new Set();
        let isSelecting = false;
        let selectionBox = null;
        let startX = 0,
        startY = 0;
        let isDragging = false;
        function makeDraggable(element)
        {
            let offsetX = 0,
                offsetY = 0;

            element.addEventListener("mousedown", (e) => {
                e.preventDefault();
                if (e.ctrlKey || e.metaKey) {
                    selectedElements.has(element)
                        ? selectedElements.delete(element)
                        : selectedElements.add(element);
                    element.classList.toggle("selected");
                    return;
                }

                // const container = element.closest("#room") || document.getElementById("a4-container");
                const container = element.closest("#a4-container"); // Use #room if inside it
                const containerRect = container.getBoundingClientRect();
                startX = e.clientX - containerRect.left;
                startY = e.clientY - containerRect.top;

                if (selectedElements.has(element))
                {
                    isDragging = true;
                    const initialPositions = Array.from(selectedElements).map((el) => ({
                            el,
                            startLeft: parseInt(el.style.left, 10) || 0,
                            startTop: parseInt(el.style.top, 10) || 0,
                            width: el.offsetWidth,
                            height: el.offsetHeight
                    }));

                    document.onmousemove = (e) => {
                        e.preventDefault();
                        const dx = e.clientX - containerRect.left - startX;
                        const dy = e.clientY - containerRect.top - startY;

                        initialPositions.forEach(
                            ({ el, startLeft, startTop, width, height }) => {
                                const newLeft = Math.max(
                                0,
                                Math.min(containerRect.width - width, startLeft + dx)
                                );
                                const newTop = Math.max(
                                0,
                                Math.min(containerRect.height - height, startTop + dy)
                                );

                                el.style.left = `${newLeft}px`;
                                el.style.top = `${newTop}px`;
                            }
                        );
                    };
                } else {
                    // Drag a single element
                    offsetX = element.offsetLeft;
                    offsetY = element.offsetTop;
                    document.onmousemove = (e) => {
                        e.preventDefault();
                        const newLeft = Math.max(
                        0,
                        Math.min(
                            containerRect.width - element.offsetWidth,
                            offsetX + e.clientX - containerRect.left - startX
                        )
                        );
                        const newTop = Math.max(
                        0,
                        Math.min(
                            containerRect.height - element.offsetHeight,
                            offsetY + e.clientY - containerRect.top - startY
                        )
                        );

                        element.style.left = `${newLeft}px`;
                        element.style.top = `${newTop}px`;
                    };
                }
                // Stop dragging on mouse up
                document.onmouseup = () => {
                document.onmousemove = null;
                isDragging = false;
                };
            });
        }
        const container = document.getElementById("a4-container");
        const roomContainer = document.getElementById("room");
        container.addEventListener("mousedown", (e) => {
        e.preventDefault();
            if (e.target === container || e.target === roomContainer || e.target instanceof SVGElement ) {
                isSelecting = true;

                const containerRect = container.getBoundingClientRect();
                startX = e.clientX - containerRect.left;
                startY = e.clientY - containerRect.top;

                // Create selection box
                selectionBox = document.createElement("div");
                selectionBox.className = "selection-box";
                selectionBox.style.left = `${startX}px`;
                selectionBox.style.top = `${startY}px`;
                selectionBox.style.display = "block";
                container.appendChild(selectionBox);

                // Clear previous selection
                selectedElements.forEach((el) => el.classList.remove("selected"));
                selectedElements.clear();
            }
        });
        container.addEventListener("mousemove", (e) => {
        e.preventDefault();
            if (isSelecting) {
                const containerRect = container.getBoundingClientRect();
                const currentX = e.clientX - containerRect.left;
                const currentY = e.clientY - containerRect.top;

                const width = Math.abs(currentX - startX);
                const height = Math.abs(currentY - startY);

                selectionBox.style.width = `${width}px`;
                selectionBox.style.height = `${height}px`;

                if (currentX < startX) selectionBox.style.left = `${currentX}px`;
                if (currentY < startY) selectionBox.style.top = `${currentY}px`;
            }
        });
        container.addEventListener("mouseup", () => {
            if (isSelecting) {
                const selectionRect = selectionBox.getBoundingClientRect();
                document
                .querySelectorAll(".table-set,.text-element, .image-element, .yellow-box")
                .forEach((item) => {
                    const itemRect = item.getBoundingClientRect();
                    if (selectionRect.left < itemRect.right && selectionRect.right > itemRect.left && selectionRect.top < itemRect.bottom &&selectionRect.bottom > itemRect.top) {
                        item.classList.add("selected");
                        selectedElements.add(item);
                    }
                });
                if (selectionBox) {
                    selectionBox.remove();
                    selectionBox = null;
                }
                isSelecting = false;
            }
        });
        container.addEventListener("click", (e) => {
            if ((e.target === container || e.target === document.getElementById("room")) &&!isDragging) {
                selectedElements.forEach((el) => el.classList.remove("selected"));
                selectedElements.clear();

                if (selectionBox) {
                selectionBox.remove();
                selectionBox = null;
                }
            }
        });
        const rooms = {
            room1: {
                width: 24,
                height: 8,
                stages: [{ width: 4, height: 8, left: 20, top: 0 }],
                doors: [{ width: 0.2, height: 3, left: 0, top: 2 }]
            },
            room2: {
                width: 28,
                height: 8,
                stages: [{ width: 4, height: 8, left: 24, top: 0 }],
                doors: [
                    { width: 0.2, height: 2.8, left: 0, top: 4.2 }
                    // { width: 2, height: 3, left: 26, top: 4 },
                ],
                recordingRoom: {
                    width: 2,
                    height: 4,
                    position: { top: 1, left: 2 }
                }
            },
            room3: {
                width: 32,
                height: 16,
                stages: [{ width: 4, height: 8, left: 28, top: 4 }],
                doors: [
                    { width: 0.2, height: 3, left: 0, top: 0 },
                    { width: 0.2, height: 3, left: 31.7, top: 0 },
                    { width: 0.2, height: 3, left: 0, top: 12.9 }
                ]
            },
            room4: {
                type: "polygon",
                width: 28,
                height: 8,
                points: [
                    { x: 0, y: 0 },
                    { x: 28, y: 0 },
                    { x: 28, y: 8 },
                    { x: 20, y: 8 },
                    { x: 20, y: 6 },
                    { x: 15, y: 6 },
                    { x: 15, y: 8 },
                    { x: 5, y: 8 },
                    { x: 5, y: 7 },
                    { x: 0, y: 8 }
                ],
                stages: [{ width: 4, height: 8, left: 24, top: 0 }],
                doors: [{ width: 0.2, height: 2, left: 0, top: 2 }]
            },
            roomCircle: {
                type: "circle", // รูปแบบวงกลม
                width: 15, // เส้นผ่านศูนย์กลาง
                height: 15, // ใช้ความสูงเป็นเส้นผ่านศูนย์กลาง
                stages: [{ width: 3, height: 6, left: 11, top: 4 }],
                doors: [{ width: 0.2, height: 2, left: 0, top: 6 }]
            }
        };
        $(document).ready(function () {
            const a4WidthPx = 1123; // A4 width in pixels
            const a4HeightPx = 794; // A4 height in pixels
            const margin = 10; // Margin of 10px
            function setupRoom(roomConfig) {
                // Calculate scaling factor to fit the A4 container with margins
                const roomScale = Math.min(
                    (a4WidthPx - 120) / roomConfig.width,
                    (a4HeightPx - 230) / roomConfig.height // Adjust for top and bottom margins
                );

                const roomWidthPx = roomConfig.width * roomScale;
                const roomHeightPx = roomConfig.height * roomScale;

                // Set room size and style
                $("#room")
                .css({
                    width: `${roomWidthPx}px`,
                    height: `${roomHeightPx}px`,
                    margin: "auto",
                    position: "relative",
                    marginRight: "20px",

                    border: "1px solid grey",
                    clipPath: "none",
                    WebkitClipPath: "none"
                })
                .empty();

                if (roomConfig.type === "polygon" && roomConfig.points) {
                    $("#room").css({
                        border: "none"
                    });

                    const scaledPoints = roomConfig.points.map((point) => {
                        return `${point.x * roomScale},${point.y * roomScale}`;
                    });

                    const svgNamespace = "http://www.w3.org/2000/svg";

                    // สร้าง SVG Element
                    const svg = document.createElementNS(svgNamespace, "svg");
                    svg.setAttribute("width", `${roomWidthPx}`);
                    svg.setAttribute("height", `${roomHeightPx}`);
                    svg.style.overflow = "visible";

                    // สร้าง Polygon Element
                    const polygon = document.createElementNS(svgNamespace, "polygon");
                    polygon.setAttribute("points", scaledPoints.join(" "));
                    polygon.setAttribute("style", "fill:white; stroke:grey; stroke-width:1");
                    svg.appendChild(polygon);

                    // เพิ่มข้อความความยาวและองศาของแต่ละด้าน
                    roomConfig.points.forEach((point, index) => {
                    const nextIndex = (index + 1) % roomConfig.points.length;
                    const startX = point.x * roomScale;
                    const startY = point.y * roomScale;
                    const endX = roomConfig.points[nextIndex].x * roomScale;
                    const endY = roomConfig.points[nextIndex].y * roomScale;

                    // คำนวณความยาวระหว่างจุด
                    const length = Math.sqrt(
                        Math.pow(roomConfig.points[nextIndex].x - point.x, 2) +
                        Math.pow(roomConfig.points[nextIndex].y - point.y, 2)
                    ).toFixed(2); // เก็บทศนิยม 2 ตำแหน่ง

                    // คำนวณตำแหน่งของข้อความ (ตรงกลางของเส้น)
                    const textX = (startX + endX) / 2;
                    const textY = (startY + endY) / 2;

                    // คำนวณองศา (Angle)
                    const deltaX = roomConfig.points[nextIndex].x - point.x;
                    const deltaY = roomConfig.points[nextIndex].y - point.y;
                    const angle = (Math.atan2(deltaY, deltaX) * 180) / Math.PI; // แปลง radians เป็น degrees
                    const formattedAngle = angle.toFixed(2); // เก็บทศนิยม 2 ตำแหน่ง

                    updateLengths(svg, roomConfig, roomScale);
                    });

                    // สร้างจุด `<circle>` ที่ลากได้สำหรับแต่ละจุด
                    roomConfig.points.forEach((point, index) => {
                    const circle = document.createElementNS(svgNamespace, "circle");
                    const cx = point.x * roomScale;
                    const cy = point.y * roomScale;
                    const radius = 5;
                    circle.setAttribute("cx", cx);
                    circle.setAttribute("cy", cy);
                    circle.setAttribute("r", radius);
                    circle.setAttribute("fill", "red");
                    circle.setAttribute("class", "draggable-point");
                    circle.setAttribute("data-index", index); // เก็บ Index ของจุดไว้

                    // เพิ่ม Event Listener สำหรับการลากจุด
                    makePointDraggable(circle, polygon, roomConfig, roomScale, svg);
                    svg.appendChild(circle);
                    });

                    $("#room").append(svg);
                }

                // // ฟังก์ชันทำให้จุดลากได้
                function makePointDraggable(circle, polygon, roomConfig, roomScale, svg) {
                    let isDragging = false;

                    circle.addEventListener("mousedown", (e) => {
                        isDragging = true;
                    });

                    document.addEventListener("mousemove", (e) => {
                        if (!isDragging) return;

                        const rect = $("#room")[0].getBoundingClientRect();
                        let x = e.clientX - rect.left;
                        let y = e.clientY - rect.top;

                        // ปัดตำแหน่ง x และ y ให้เป็น step 0.1
                        x = (Math.round((x / roomScale) * 10) / 10) * roomScale;
                        y = (Math.round((y / roomScale) * 10) / 10) * roomScale;

                        const index = parseInt(circle.getAttribute("data-index"), 10);
                        circle.setAttribute("cx", x);
                        circle.setAttribute("cy", y);

                        // อัปเดตตำแหน่งของจุดใน roomConfig
                        roomConfig.points[index].x = Math.round((x / roomScale) * 10) / 10;
                        roomConfig.points[index].y = Math.round((y / roomScale) * 10) / 10;

                        // อัปเดตตำแหน่ง Polygon
                        const newPoints = roomConfig.points
                        .map((point) => `${point.x * roomScale},${point.y * roomScale}`)
                        .join(" ");
                        polygon.setAttribute("points", newPoints);

                        // อัปเดตข้อความความยาว
                        updateLengths(svg, roomConfig, roomScale);
                    });

                    document.addEventListener("mouseup", () => {
                        isDragging = false;
                    });
                }

                // // ฟังก์ชันอัปเดตข้อความความยาว, องศา, และจุดพิกัด
                function updateLengths(svg, roomConfig, roomScale) {
                    // ลบข้อความเก่าทั้งหมด
                    svg.querySelectorAll("text").forEach((text) => text.remove());
                    roomConfig.points.forEach((point, index) => {
                        const nextIndex = (index + 1) % roomConfig.points.length;
                        const startX = point.x * roomScale;
                        const startY = point.y * roomScale;
                        const endX = roomConfig.points[nextIndex].x * roomScale;
                        const endY = roomConfig.points[nextIndex].y * roomScale;

                        // คำนวณความยาวระหว่างจุด
                        const length = Number(
                        Math.sqrt(
                            Math.pow(roomConfig.points[nextIndex].x - point.x, 2) +
                            Math.pow(roomConfig.points[nextIndex].y - point.y, 2)
                            ).toFixed(2) // เก็บทศนิยม 2 ตำแหน่ง
                        );

                        // คำนวณองศา (Angle)
                        const deltaX = roomConfig.points[nextIndex].x - point.x;
                        const deltaY = roomConfig.points[nextIndex].y - point.y;
                        const angle = (Math.atan2(deltaY, deltaX) * 180) / Math.PI; // แปลง radians เป็น degrees
                        const formattedAngle = angle.toFixed(2); // เก็บทศนิยม 2 ตำแหน่ง

                        // คำนวณตำแหน่งข้อความความยาว (กลางเส้น)
                        const textX = (startX + endX) / 2;
                        const textY = (startY + endY) / 2;

                        // สร้าง Text Element สำหรับความยาว
                        const lengthText = document.createElementNS(
                            "http://www.w3.org/2000/svg",
                            "text"
                        );
                        lengthText.setAttribute("x", textX);
                        lengthText.setAttribute("y", textY - 5); // ตำแหน่งเหนือข้อความมุม
                        lengthText.setAttribute("fill", "green");
                        lengthText.setAttribute("font-size", "12px");
                        lengthText.setAttribute("text-anchor", "middle");
                        lengthText.setAttribute("class", "no-select");
                        lengthText.textContent = `${Number(length)}m`;
                        svg.appendChild(lengthText);

                        // สร้าง Text Element สำหรับองศา
                        // const angleText = document.createElementNS("http://www.w3.org/2000/svg", "text");
                        // angleText.setAttribute("x", textX);
                        // angleText.setAttribute("y", textY + 10); // ตำแหน่งใต้ข้อความความยาว
                        // angleText.setAttribute("fill", "green");
                        // angleText.setAttribute("font-size", "12px");
                        // angleText.setAttribute("text-anchor", "middle");
                        // angleText.setAttribute("class", "no-select");
                        // angleText.textContent = `${formattedAngle}°`;
                        // svg.appendChild(angleText);

                        // สร้างข้อความระบุจุด (Point X: (x, y))
                        const pointX = point.x.toFixed(1); // ตัดให้เหลือทศนิยม 1 ตำแหน่ง
                        const pointY = point.y.toFixed(1); // ตัดให้เหลือทศนิยม 1 ตำแหน่ง
                        const formattedX = Number(point.x.toFixed(1));
                        const formattedY = Number(point.y.toFixed(1));
                        const positionText = document.createElementNS(
                            "http://www.w3.org/2000/svg",
                            "text"
                        );
                        positionText.setAttribute("x", startX - 45); // วางข้อความด้านขวาของจุด
                        positionText.setAttribute("y", startY - 5); // วางข้อความเหนือจุด
                        positionText.setAttribute("fill", "rgb(49, 47, 47)");
                        positionText.setAttribute("font-size", "12px");
                        positionText.setAttribute("text-anchor", "start");
                        positionText.setAttribute("class", "no-select");
                        positionText.textContent = `Point ${
                        index + 1
                        }: (${formattedX}, ${formattedY})`;
                        svg.appendChild(positionText);
                    });
                }

                if (roomConfig.type === "circle") {
                    $("#room").css({
                        margin: "auto",
                        border: "none",
                        position: "relative"
                    });
                    $("#line-grid-A4").css({
                        position: "absolute",
                        left: "50%"
                    });
                    // คำนวณค่ากึ่งกลางและรัศมี
                    const ellipseCx = roomWidthPx / 2; // จุดกึ่งกลางแกน X
                    const ellipseCy = roomHeightPx / 2; // จุดกึ่งกลางแกน Y
                    const ellipseRx = roomWidthPx / 2; // รัศมีตามแกน X
                    const ellipseRy = roomHeightPx / 2; // รัศมีตามแกน Y
                    // วาดวงรีหรือวงกลม
                    const ellipseSVG = `
                                        <svg width="${roomWidthPx}" height="${roomHeightPx}" style="overflow: hidden;">
                                        <ellipse cx="${ellipseCx}" cy="${ellipseCy}" rx="${ellipseRx}" ry="${ellipseRy}"
                                            style="fill:white; stroke:black; stroke-width:1;overflow: hidden;" />
                                        </svg>
                                    `;
                    $("#room").append(ellipseSVG);
                }

                // // เรียกใช้ addStage
                (roomConfig.stages || []).forEach((stage) => {
                    addStage(roomWidthPx, roomHeightPx, stage, roomScale);
                });

                // // เรียกใช้ addDoor
                (roomConfig.doors || []).forEach((door) => {
                    addDoor(roomWidthPx, roomHeightPx, door, roomScale);
                });

                // // เพิ่มห้องควบคุมเสียง (ถ้ามี)
                if (roomConfig.recordingRoom) {
                    addRecordingRoom(roomConfig.recordingRoom, roomScale);
                }

                // // แสดงกล่องจุดและความยาวของ customroom
                function toggleVisibility(isVisible) {
                    const visibility = isVisible ? "visible" : "hidden";
                    $("svg .draggable-point").css(
                    "display",
                    visibility === "visible" ? "block" : "none"
                    ); // ซ่อนหรือแสดงจุด
                    $("svg text").css("display", visibility === "visible" ? "block" : "none"); // ซ่อนหรือแสดงข้อความความยาว
                }
                $("#tg-lengeAndPoint").on("change", function () {
                    const isChecked = $(this).is(":checked");
                    toggleVisibility(isChecked);
                });

                $(document).ready(function () {
                    const isChecked = $("#tg-lengeAndPoint").is(":checked");
                    toggleVisibility(isChecked); // ซิงค์สถานะเริ่มต้นของ checkbox
                });

                // // แสดงกล่องที่จะเลือก customroom
                const roomType = roomConfig.type;
                console.log(roomType);

                // if (roomType === "polygon") {
                // $("#tg-lengeAndPoint").parent().show();
                // } else {
                // $("#tg-lengeAndPoint").parent().hide();
                // }
            }
            // สร้างเวที
            function addStage(roomWidthPx, roomHeightPx, stage, roomScale) {
                const stageWidthPx = stage.width * roomScale;
                const stageHeightPx = stage.height * roomScale;

                const stageLeft = stage.left * roomScale;
                const stageTop = stage.top * roomScale;

                const stageElement = document.createElement("div");
                stageElement.className = "stage";
                stageElement.style.width = `${stageWidthPx}px`;
                stageElement.style.height = `${stageHeightPx}px`;
                stageElement.style.position = "absolute";
                stageElement.style.left = `${stageLeft}px`; // ตำแหน่งที่ปรับแล้ว
                stageElement.style.top = `${stageTop}px`; // ตำแหน่งที่ปรับแล้ว
                stageElement.style.backgroundColor = "rgba(141, 87, 25, 0.946)";
                stageElement.style.color = "white";
                stageElement.style.textAlign = "center";
                stageElement.style.cursor = "move";
                stageElement.style.lineHeight = `${stageHeightPx}px`;
                stageElement.innerHTML = "<span class='no-select'>Stage</span>";
                document.getElementById("room").appendChild(stageElement);
                // makeDraggable(stageElement);
            }

            function addDoor(roomWidthPx, roomHeightPx, door, roomScale) {
                const doorWidthPx = door.width * roomScale;
                const doorHeightPx = door.height * roomScale;

                const doorLeftPx = door.left * roomScale;
                const doorTopPx = door.top * roomScale;

                const doorElement = document.createElement("div");
                doorElement.className = "door";
                doorElement.style.width = `${doorWidthPx}px`;
                doorElement.style.height = `${doorHeightPx}px`;
                doorElement.style.position = "absolute";
                doorElement.style.left = `${doorLeftPx}px`;
                doorElement.style.top = `${doorTopPx}px`;
                doorElement.style.zIndex = `1`;
                doorElement.style.backgroundColor = "brown";
                doorElement.style.cursor = "move";
                document.getElementById("room").appendChild(doorElement);
            }
            function addRecordingRoom(recordingRoom, roomScale) {
                const recWidthPx = recordingRoom.width * roomScale;
                const recHeightPx = recordingRoom.height * roomScale;
                const recLeft = recordingRoom.left * roomScale;
                const recTop = recordingRoom.top * roomScale;

                // สร้าง div สำหรับห้องควบคุมเสียง
                const recordingRoomElement = document.createElement("div");
                recordingRoomElement.className = "recording-room no-select";
                recordingRoomElement.style.position = "absolute";
                recordingRoomElement.style.width = `${recWidthPx}px`;
                recordingRoomElement.style.height = `${recHeightPx}px`;
                recordingRoomElement.style.left = `${recLeft}px`;
                recordingRoomElement.style.top = `${recTop}px`;
                recordingRoomElement.style.backgroundColor = "#d7d1dc";
                recordingRoomElement.style.textAlign = "center";
                recordingRoomElement.style.lineHeight = `${recHeightPx}px`;

                const textElement = document.createElement("p");
                textElement.className = "recording-room-text no-select";
                textElement.textContent = "Control Room";

                recordingRoomElement.appendChild(textElement);
                document.getElementById("room").appendChild(recordingRoomElement);
            }
            $("#clear-all").on("click", function () {
                clearCurrentRoom();
            });

            // ล้างห้องทั้งหมด
            function clearCurrentRoom() {
                $("#a4-container")
                .find(".table-set, .text-element, .image-element, .yellow-box")
                .remove();
                updateCounts();
            }

            $("#select-all").on("click", function () {
                const elements = $("#a4-container").find(
                    ".table-set, .text-element, .image-element, .yellow-box"
                );

                // เพิ่มทุกองค์ประกอบใน selectedElements และเพิ่มคลาส selected
                elements.each(function () {
                    $(this).addClass("selected");
                    selectedElements.add(this);
                });
            });
            // เมื่อเปลี่ยนห้อง
            $("#roomSelector").on("change", function () {
                const selectedRoom = $(this).val();
                currentRoomKey = selectedRoom;
                clearCurrentRoom();
                setupRoom(rooms[selectedRoom]);
                // loadPolygonPointsForRoom(rooms[selectedRoom]);
                console.log("Room setup completed for:", selectedRoom);
            });
            let isEditMode = true; // ใช้ตัวแปรนี้เพื่อตรวจสอบว่าเป็น Add หรือ Edit
            // เรียกห้องเริ่มต้นเมื่อโหลดหน้า
            setupRoom(rooms.room1);
            // โหลดข้อมูลห้อง
            function loadRoomDetails(roomKey) {
                // Reset modal fields
                $("#roomName").val("");
                $("#roomType").val("");
                $("#roomWidth").val("");
                $("#roomHeight").val("");
                $("#doorList").empty();
                $("#stageList").empty();
                $("#pointsList").empty();
                $("#polygonPointsContainer").hide(); // Hide polygon container by default

                // Load the selected room configuration
                const roomConfig = rooms[roomKey];
                if (!roomConfig) return;

                $("#roomName").val(roomKey);
                $("#roomType").val(roomConfig.type || "rectangle");
                $("#roomWidth").val(roomConfig.width); // Convert from px to meters
                $("#roomHeight").val(roomConfig.height);

                // Load doors
                const $doorList = $("#doorList").empty();
                (roomConfig.doors || []).forEach((door, index) => {
                $doorList.append(`
                                <div class="door-item mb-2 d-grid-5column" data-index="${index}">
                                <div class="center">
                                    <button type="button" class="btn btn-danger rounded-circle btn-sm remove-door-button">
                                    <i style="font-size:14px" class="fa">&#xf068;</i>
                                    </button>
                                </div>
                                <div>
                                    <label>Door Width</label>
                                    <input type="number" class="form-control mb-1 door-width" placeholder="Width (m)" value="${door.width}">
                                </div>
                                <div>
                                    <label>Door Height</label>
                                    <input type="number" class="form-control mb-1 door-height" placeholder="Height (m)" value="${door.height}">
                                </div>
                                <div>
                                    <label>Door Left</label>
                                    <input type="number" class="form-control mb-1 door-left" placeholder="Left Position (m)" value="${door.left}">
                                </div>
                                <div>
                                    <label>Door Top</label>
                                    <input type="number" class="form-control door-top" placeholder="Top Position (m)" value="${door.top}">
                                </div>
                                </div>
                            `);
                });

                // Load stages
                const $stageList = $("#stageList").empty();
                (roomConfig.stages || []).forEach((stage, index) => {
                $stageList.append(`
                                <div class="stage-item mb-2 d-grid-5column" data-index="${index}">
                                <div class="center">
                                    <button type="button" class="btn btn-danger rounded-circle btn-sm remove-stage-button">
                                    <i style="font-size:14px" class="fa">&#xf068;</i>
                                    </button>
                                </div>
                                <div>
                                    <label>Stage Width</label>
                                    <input type="number" class="form-control mb-1 stage-width" placeholder="Width (m)" value="${stage.width}">
                                </div>
                                <div>
                                    <label>Stage Height</label>
                                    <input type="number" class="form-control mb-1 stage-height" placeholder="Height (m)" value="${stage.height}">
                                </div>
                                <div>
                                    <label>Stage Left</label>
                                    <input type="number" class="form-control mb-1 stage-left" placeholder="Left Position (m)" value="${stage.left}">
                                </div>
                                <div>
                                    <label>Stage Top</label>
                                    <input type="number" class="form-control stage-top" placeholder="Top Position (m)" value="${stage.top}">
                                </div>
                                </div>
                            `);
                });

                // Load polygon points if the room is a polygon
                if (roomConfig.type === "polygon") {
                const $pointsList = $("#pointsList").empty();
                (roomConfig.points || []).forEach((point, index) => {
                    $pointsList.append(`
                                <div class="point-item mb-2">
                                    <label>Point ${index + 1}</label>
                                    <div class="sss" style="background-color:grey;">
                                    <input style="border-radius:0px;" type="number" class="form-control point-x" placeholder="X (m)" value="${
                                        point.x
                                    }">
                                    <input style="border-radius:0px;" type="number" class="form-control point-y" placeholder="Y (m)" value="${
                                        point.y
                                    }">
                                    <button class="btn btn-sm remove-point center"><i class="fa fa-trash-o text-white" style="font-size:18px mt-2"></i></button>
                                    </div>
                                </div>
                                `);
                });
                // Show the polygon points container
                $("#polygonPointsContainer").show();
                }
            }
            $("#addStageButton").on("click", function () {
                const $stageList = $("#stageList");
                $stageList.append(`
                            <div class="stage-item mb-2 d-grid-5column">
                                <div class="center">
                                <button type="button" class="btn btn-danger rounded-circle btn-sm remove-stage-button">
                                    <i style="font-size:14px" class="fa">&#xf068;</i>
                                </button>
                                </div>
                                <div>
                                <label>Stage Width</label>
                                <input type="number" class="form-control mb-1 stage-width" placeholder="Width (m)">
                                </div>
                                <div>
                                <label>Stage Height</label>
                                <input type="number" class="form-control mb-1 stage-height" placeholder="Height (m)">
                                </div>
                                <div>
                                <label>Stage Left</label>
                                <input type="number" class="form-control mb-1 stage-left" placeholder="Left Position (m)">
                                </div>
                                <div>
                                <label>Stage Top</label>
                                <input type="number" class="form-control stage-top" placeholder="Top Position (m)">
                                </div>
                            </div>
                            `);
            });
            $(document).on("click", ".remove-stage-button", function () {
                $(this).closest(".stage-item").remove();
            });
            $("#addDoorButton").on("click", function () {
                const $doorList = $("#doorList");
                $doorList.append(`
                                <div class="door-item mb-2 d-grid-5column">

                                <div class="center">
                                    <button type="button" class="btn btn-danger rounded-circle btn-sm remove-door-button" ><i style="font-size:14px" class="fa">&#xf068;</i></button>
                                </div>

                                <div>
                                    <label>Door Width</label>
                                <input type="number" class="form-control mb-1 door-width" placeholder="Width (m)">
                                </div>

                                <div>
                                    <label>Door Height</label>
                                <input type="number" class="form-control mb-1 door-height" placeholder="Height (m)">
                                </div>

                                <div>
                                    <label>Door Left</label>
                                <input type="number" class="form-control mb-1 door-left" placeholder="Left Position (m)">
                                </div>

                                <div>
                                    <label>Door Top</label>
                                <input type="number" class="form-control door-top" placeholder="Top Position (m)">
                                </div>

                                </div>
                            `);
            });
            $(document).on("click", ".remove-door-button", function () {
                $(this).closest(".door-item").remove();
            });

            let currentRoomKey = $("#roomSelector").val();

            // เปิด Modal สำหรับเพิ่มห้อง
            $("#addRoomButton").on("click", function () {
                isEditMode = false; // กำหนดเป็นโหมด Add
                $("#editRoomModalTitle").text("Add New Room");
                // รีเซ็ตค่าในฟอร์ม
                $("#roomType").val("rectangle");
                $("#roomName").val("");
                $("#roomWidth").val("");
                $("#roomHeight").val("");
                $("#stageList").empty();
                $("#doorList").empty();
                $("#pointsList").empty();
                $("#editRoomModal").modal("show");
                $("#editRoomModal").modal("show");
            });
            $("#editRoomButton").on("click", function () {
                isEditMode = true; // กำหนดเป็นโหมด Edit
                $("#editRoomModalTitle").text("Edit Room Details");
                // เติมข้อมูลในฟอร์มสำหรับห้องปัจจุบัน
                loadRoomDetails(currentRoomKey);
                $("#editRoomModal").modal("show");
            });
            $("#saveRoomChanges").on("click", function () {
                const roomName = $("#roomName").val().trim();
                const roomType = $("#roomType").val(); // Get selected type
                const roomWidth = parseFloat($("#roomWidth").val());
                const roomHeight = parseFloat($("#roomHeight").val());

                if (!roomName || isNaN(roomWidth) || isNaN(roomHeight)) {
                    alert("Please fill in all required fields.");
                    return;
                }

                // Prepare room config
                const roomConfig = {
                    type: roomType,
                    width: roomWidth,
                    height: roomHeight,
                    stages: [],
                    doors: []
                };

                // Collect stage data
                const stages = [];
                $("#stageList .stage-item").each(function () {
                    const stageWidth = parseFloat($(this).find(".stage-width").val());
                    const stageHeight = parseFloat($(this).find(".stage-height").val());
                    const stageLeft = parseFloat($(this).find(".stage-left").val());
                    const stageTop = parseFloat($(this).find(".stage-top").val());

                    if (
                        !isNaN(stageWidth) &&
                        !isNaN(stageHeight) &&
                        !isNaN(stageLeft) &&
                        !isNaN(stageTop)
                    ) {
                        stages.push({
                        width: stageWidth,
                        height: stageHeight,
                        left: stageLeft,
                        top: stageTop
                        });
                    }
                });
                roomConfig.stages = stages; // Add stages to room config

                // Add doors
                $("#doorList .door-item").each(function () {
                    const doorWidth = parseFloat($(this).find(".door-width").val());
                    const doorHeight = parseFloat($(this).find(".door-height").val());
                    const doorLeft = parseFloat($(this).find(".door-left").val());
                    const doorTop = parseFloat($(this).find(".door-top").val());

                    if (
                        !isNaN(doorWidth) &&
                        !isNaN(doorHeight) &&
                        !isNaN(doorLeft) &&
                        !isNaN(doorTop)
                    ) {
                        roomConfig.doors.push({
                        width: doorWidth,
                        height: doorHeight,
                        left: doorLeft,
                        top: doorTop
                        });
                    }
                });

                // Add points for polygons
                if (roomType === "polygon") {
                    roomConfig.points = [];
                    roomConfig.points.push({ x: 0, y: 0 }); // Add the first point as (0, 0)
                    $("#pointsList .point-item").each(function (index) {
                        if (index === 0) return; // Skip the first point
                        const pointX = parseFloat($(this).find(".point-x").val());
                        const pointY = parseFloat($(this).find(".point-y").val());
                        if (!isNaN(pointX) && !isNaN(pointY)) {
                        roomConfig.points.push({ x: pointX, y: pointY });
                        }
                    });
                }

                // Add or update room
                if (!isEditMode) {
                // Add new room
                rooms[roomName] = roomConfig;
                    $("#roomSelector").append(
                        `<option value="${roomName}">${roomName}</option>`
                    );
                } else {
                // Edit existing room
                const originalRoomName = currentRoomKey; // เก็บชื่อห้องเดิมไว้
                    if (roomName !== originalRoomName) {
                        // ถ้าชื่อห้องถูกเปลี่ยน
                        delete rooms[originalRoomName]; // ลบชื่อห้องเก่า
                        rooms[roomName] = roomConfig; // เพิ่มชื่อห้องใหม่
                        $(`#roomSelector option[value="${originalRoomName}"]`)
                        .val(roomName)
                        .text(roomName); // อัปเดต Dropdown
                    } else {
                        rooms[roomName] = roomConfig; // อัปเดตข้อมูลห้อง
                    }
                }

                // อัปเดตค่าที่เลือกใน Dropdown
                $("#roomSelector").val(roomName);

                // Close modal and refresh room
                currentRoomKey = roomName; // ตั้งค่าห้องปัจจุบันเป็นห้องที่เพิ่งบันทึก
                setupRoom(rooms[currentRoomKey]);
                $("#editRoomModal").modal("hide");
            });
            $("#roomType").on("change", function () {
                if ($(this).val() === "polygon") {
                $("#polygonPointsContainer").show();
                } else {
                $("#polygonPointsContainer").hide();
                }
            });
            $("#addPointButton").on("click", function () {
            const index = $("#pointsList .point-item").length + 1;
                $("#pointsList").append(`
                    <div class="point-item mb-2" data-index="${index}">
                    <label>Point ${index}</label>
                    <div class="sss d-flex" style="background-color:grey;">
                        <input style="border-radius:0px;" type="number" class="form-control point-x" placeholder="X (m)">
                        <input style="border-radius:0px;" type="number" class="form-control point-y" placeholder="Y (m)">
                        <button class="btn btn-sm remove-point center"><i class="fa fa-trash-o text-white" style="font-size:18px mt-2"></i></button>
                    </div>
                    </div>
                `);
            });
            $("#pointsList").on("click", ".remove-point", function () {
                $(this).closest(".point-item").remove();

                // อัปเดตหมายเลขจุดใหม่หลังจากลบ
                $("#pointsList .point-item").each(function (index) {
                $(this).attr("data-index", index + 1); // อัปเดต data-index
                $(this)
                    .find("label")
                    .text(`Point ${index + 1}`); // อัปเดต label
                });
            });
            $("#deleteRoomButton").on("click", function () {
                if (!currentRoomKey) {
                    alert("กรุณาเลือกห้องที่ต้องการลบ");
                    return;
                }

                const confirmation = confirm(
                    `คุณต้องการลบห้อง "${currentRoomKey}" หรือไม่?`
                    );
                if (confirmation) {
                    // ลบห้องออกจากออบเจกต์ rooms
                    delete rooms[currentRoomKey];

                    // ลบตัวเลือกใน Dropdown
                    $(`#roomSelector option[value="${currentRoomKey}"]`).remove();

                    // ตั้งค่าให้ห้องถัดไปเป็นห้องที่เลือกอยู่
                    const nextRoomKey = Object.keys(rooms)[0]; // ห้องแรกในออบเจกต์
                    if (nextRoomKey) {
                        currentRoomKey = nextRoomKey;
                        setupRoom(rooms[nextRoomKey]);
                        $("#roomSelector").val(nextRoomKey);
                    } else {
                        // ถ้าไม่มีห้องเหลือ
                        $("#room").empty(); // เคลียร์หน้าจอ
                        currentRoomKey = null;
                    }
                }
            });
            // ฟังก์ชันเปิด Modal เพื่อเพิ่มออบเจกต์
            function openAddItemModal(className, top) {
                currentClassName = className;
                const defaultGap = className === "table-long" ? 0 : 0.8;
                if (className === "table-long") {
                    $("#itemCount").val(1); // จำนวนโต๊ะดีฟอลต์สำหรับโต๊ะยาว
                    $("#chairCount").val(3); // จำนวนเก้าอี้ต่อโต๊ะดีฟอลต์สำหรับโต๊ะยาว
                } else if (className === "table-round") {
                    $("#itemCount").val(1); // จำนวนโต๊ะดีฟอลต์สำหรับโต๊ะกลม
                    $("#chairCount").val(8); // จำนวนเก้าอี้ต่อโต๊ะดีฟอลต์สำหรับโต๊ะกลม
                }
                $("#gapValue").val(defaultGap);
                $("#addItemModal").modal("show");
            }
            // เมื่อกดปุ่มยืนยันใน Modal
            $("#addItemConfirm").on("click", function () {
                const tableCount = parseInt($("#itemCount").val()) || 0;
                const chairCount = parseInt($("#chairCount").val()) || 0;
                const tableColor = $("#tableColor").val();
                const chairColor = $("#chairColor").val();
                const gap =
                parseInt($("#gapValue").val()) ||
                (currentClassName === "table-long" ? 0 : 0.8);

                const addType = $("input[name='addType']:checked").val();

                let addChairsOnly = false;
                let addTableOnly = false;

                if (addType === "chair-only") addChairsOnly = true;
                if (addType === "table-only") addTableOnly = true;

                if (addChairsOnly && chairCount === 0) {
                    alert("กรุณาใส่จำนวนเก้าอี้!");
                    return;
                }

                if (tableCount > 0) {
                    addElement(
                        currentClassName,
                        tableCount,
                        startTop,
                        chairCount,
                        tableColor,
                        chairColor,
                        gap,
                        addChairsOnly,
                        addTableOnly
                    );
                    $("#addItemModal").modal("hide");
                } else {
                    alert("กรุณาใส่จำนวนที่ถูกต้อง!");
                }
            });
            function addElement(
                className,
                tableCount,
                startTop,
                chairCount,
                tableColor,
                chairColor,
                gap,
                addChairsOnly = false,
                addTableOnly = false){
                    const roomWidthPx = $("#room").width();
                    const roomHeightPx = $("#room").height();
                    const roomConfig = rooms[$("#roomSelector").val()];
                    const roomScale = roomWidthPx / roomConfig.width;

                    const roundTableDiameterPx = 1.5 * roomScale;
                    const longTableWidthPx = 1.8 * roomScale;
                    const longTableHeightPx = 0.45 * roomScale;
                    const chairSizePx = 0.7 * roomScale;
                    const minWidth8Px = 0.8 * roomScale;

                let gapPx; // กำหนดตัวแปร gapPx ภายนอก
                if (addTableOnly || addChairsOnly) {
                    gap = Math.max(gap, 0.8);
                    gapPx = gap * roomScale; // คำนวณ gap สำหรับกรณี addTableOnly หรือ addChairsOnly
                } else {
                    gap = Math.max(gap, 0.8);
                    const gapPxL = gap * roomScale; // คำนวณ gap สำหรับโต๊ะยาว
                    const gapPxR = (gap * roomScale) + chairSizePx; // คำนวณ gap สำหรับโต๊ะกลม
                    gapPx = className === "table-long" ? gapPxL : gapPxR; // เลือก gap ตามประเภทโต๊ะ
                }

                let currentLeft = 50; // เริ่มจากตำแหน่งซ้าย
                let currentTop = startTop; // เริ่มจากตำแหน่งบน

                for (let i = 0; i < tableCount; i++) {
                    const tableWidthPx =
                        className === "table-long" ? longTableWidthPx : roundTableDiameterPx;
                    const tableHeightPx =
                        className === "table-long" ? longTableHeightPx : roundTableDiameterPx;
                    if (currentLeft + tableWidthPx > roomWidthPx) {
                        currentLeft = 50; // รีเซ็ตตำแหน่งซ้าย
                        currentTop += tableHeightPx  + gapPx; // ย้ายลงมาด้านล่าง
                    }
                    if (currentTop + tableHeightPx > roomHeightPx) {
                        alert("Not enough space to add more tables!");
                        break;
                    }
                    if (addChairsOnly) {
                        // Add chairs only, each in a separate container
                        for (let j = 0; j < chairCount; j++) {
                        const roomWidthPx = $("#room").width();
                        const roomHeightPx = $("#room").height();
                        const roomConfig = rooms[$("#roomSelector").val()];
                        const roomScale = roomWidthPx / roomConfig.width;


                        const chairWidth = 0.4 * roomScale;
                        const chairHeight = 0.25 * roomScale;

                        const chairContainer = document.createElement("div");
                        chairContainer.className = "table-set";
                        chairContainer.style.position = "absolute";
                        chairContainer.style.left = `${currentLeft}px`;
                        chairContainer.style.top = `${currentTop}px`;
                        // chairContainer.style.border = "1px solid red";
                        chairContainer.style.minWidth = `${chairWidth + 2}px`;
                        chairContainer.style.minHeight = `${chairHeight + 2}px`;

                        const chairElement = document.createElement("div");
                        chairElement.className = "chair";
                        chairElement.style.position = "absolute";
                        chairElement.style.width = `${chairWidth}px`;
                        chairElement.style.height = `${chairHeight}px`;
                        chairElement.style.backgroundColor = chairColor;
                        chairElement.style.borderRadius = "15px 15px 0 0"; // Rounded top
                        chairElement.style.transform = "rotate(0deg)"; // Default rotation
                        chairElement.style.transformOrigin = "50% 100%"; // Rotation pivot point

                        // Adjust chairContainer size based on table type
                        if (className === "table-long") {
                            chairContainer.style.width = `${chairWidth}px`;
                            chairContainer.style.height = `${chairHeight}px`;
                        } else if (className === "table-round") {
                            chairContainer.style.width = `${chairWidth}px`;
                            chairContainer.style.height = `${chairHeight}px`;
                        }

                        chairContainer.appendChild(chairElement); // Add chair to the container
                        $("#a4-container").append(chairContainer); // Add container to the room
                        makeDraggable(chairContainer); // Make draggable
                        updateCounts();

                        // Update position for the next chair
                        currentLeft += chairWidth + gapPx;
                        if (currentLeft + chairWidth > roomWidthPx) {
                            currentLeft = 50;
                            currentTop += chairHeight + gapPx;
                        }
                        }
                        return; // Skip the rest of the loop for `addChairsOnly`
                    }

                    // สร้าง div สำหรับ set (โต๊ะและเก้าอี้)
                    const setContainer = document.createElement("div");
                    setContainer.className = "table-set";
                    setContainer.style.position = "absolute";
                    setContainer.style.left = `${currentLeft}px`;
                    setContainer.style.top = `${currentTop}px`;

                    // setContainer.style.border= '1px solid green'

                    // กำหนดขนาด setContainer ตามประเภทของโต๊ะและกรณีที่เลือก
                    if (addTableOnly) {
                        if (className === "table-long") {
                        setContainer.style.width = `${tableWidthPx+ minWidth8Px}px`;
                        setContainer.style.height = `${(chairSizePx   / 1.4)+ minWidth8Px}px`;
                        } else if (className === "table-round") {
                        setContainer.style.width = `${tableWidthPx + minWidth8Px}px`;
                        setContainer.style.height = `${tableWidthPx + minWidth8Px}px`;
                        }
                    } else {
                        if (className === "table-long") {
                        setContainer.style.width = `${tableWidthPx}px`; // รวมระยะเก้าอี้ซ้ายและขวา
                        setContainer.style.height = `${tableHeightPx +(chairSizePx/2) + minWidth8Px +(0.2*roomScale)}px`; // รวมระยะเก้าอี้ด้านบนและล่าง
                        } else if (className === "table-round") {
                        setContainer.style.width = `${tableWidthPx + chairSizePx + minWidth8Px}px`; // รวมระยะเก้าอี้รอบด้าน
                        setContainer.style.height = `${tableHeightPx + chairSizePx + minWidth8Px}px`; // รวมระยะเก้าอี้รอบด้าน
                        setContainer.style.borderRadius = `50%`;
                        }
                    }
                    // เพิ่มโต๊ะ
                    if (!addChairsOnly) {
                        const tableElement = document.createElement("div");
                        tableElement.className = `${className}`;
                        tableElement.style.position = "absolute";
                        tableElement.style.width = `${tableWidthPx}px`;
                        tableElement.style.height = `${tableHeightPx}px`;
                        tableElement.style.borderRadius =
                        className === "table-round" ? "50%" : "0";
                        tableElement.style.backgroundColor = tableColor;

                        if (addTableOnly) {
                            if (className === "table-round") {
                                tableElement.style.top = `${0 * roomScale}px`;
                                tableElement.style.left = `${0 * roomScale}px`;
                            }
                        } else {
                            if (className === "table-long") {
                                tableElement.style.bottom = `${0.4 * roomScale}px`;
                                tableElement.style.left = `${(0 * roomScale-1)}px`;
                            } else if (className === "table-round") {
                                tableElement.style.top = `${0.75 * roomScale}px`;
                                tableElement.style.left = `${0.75 * roomScale}px`;
                            }
                        }
                        setContainer.appendChild(tableElement);
                    }
                // เพิ่มเก้าอี้
                if (!addTableOnly && chairCount > 0) {
                    if (className === "table-round") {
                        const { chairPositions, rotationAngles } = calculateRoundTableChairs(
                            currentLeft,
                            currentTop,
                            roundTableDiameterPx,
                            chairCount
                        );
                    addChairs(chairPositions, chairColor, rotationAngles, setContainer);
                    } else if (className === "table-long") {
                        const { chairPositions, rotationAngles } = calculateLongTableChairs(
                            currentLeft,
                            currentTop,
                            longTableWidthPx,
                            longTableHeightPx,
                            chairCount
                        );
                    addChairs(chairPositions, chairColor, rotationAngles, setContainer);
                    }
                }

                // เพิ่ม container ลงใน room
                $("#a4-container").append(setContainer);
                makeDraggable(setContainer);
                currentLeft += tableWidthPx + gapPx; // เพิ่มตำแหน่งซ้ายพร้อม gap
                }
                updateCounts();
            }
            function calculateRoundTableChairs(left, top, diameter, chairCount) {
                const roomWidthPx = $("#room").width();
                const roomHeightPx = $("#room").height();
                const roomConfig = rooms[$("#roomSelector").val()];
                const roomScale = roomWidthPx / roomConfig.width;
                const roundTableDiameterPx = 1.5 * roomScale;
                const chairPositions = [];
                const rotationAngles = [];
                const radius = diameter / 2 + 0.12 * roomScale; // เพิ่มระยะห่างจากโต๊ะ;
                const centerX = diameter / 2 + 0.76 * roomScale;
                const centerY = diameter / 2 + 0.73 * roomScale;

                for (let i = 0; i < chairCount; i++) {
                    const angle = (2 * Math.PI * i) / chairCount; // มุมในเรเดียน
                    const x = centerX + radius * Math.cos(angle) - 15 * (0.015 * roomScale);
                    const y = centerY + radius * Math.sin(angle) - 15 * (0.015 * roomScale);
                    const rotationAngle = (angle * 180) / Math.PI + 90;
                    chairPositions.push([x, y]);
                    rotationAngles.push(rotationAngle);
                }
                return { chairPositions, rotationAngles };
            }
            function calculateLongTableChairs(
                left,
                top,
                width,
                height,
                chairCount,
                addChairsOnly = false){
                const roomWidthPx = $("#room").width();
                const roomHeightPx = $("#room").height();
                const roomConfig = rooms[$("#roomSelector").val()];
                const roomScale = roomWidthPx / roomConfig.width;

                const chairPositions = [];
                const rotationAngles = [];
                const chairPadding = 0.225 * roomScale; // ระยะห่างระหว่างเก้าอี้
                const chairWidth = 0.38 * roomScale;
                const chairHeight = 0.35 * roomScale;
                const minWidth8Px = 0.8 * roomScale;
                const gapPxC = 0.07 * roomScale;

                // กรณีเพิ่มเฉพาะเก้าอี้
                if (addChairsOnly) {
                    const totalChairWidth =
                        chairCount * chairWidth + (chairCount - 1) * chairPadding; // ความกว้างรวมของเก้าอี้
                    const startX = 3; // เริ่มจากจุดกลางโต๊ะไปทางซ้าย
                    const y = height - 0.7 * roomScale; // ตำแหน่ง Y ให้อยู่ด้านบน

                    for (let i = 0; i < chairCount; i++) {
                        const x = startX + i * (chairWidth + chairPadding); // กระจายตำแหน่ง X ของเก้าอี้
                        chairPositions.push([x, y]);
                        rotationAngles.push(180); // หมุนเก้าอี้ (หันไปข้างหน้า)
                    }
                    return { chairPositions, rotationAngles };
                }

                // คำนวณจุดเริ่มต้นให้เก้าอี้อยู่กึ่งกลางโต๊ะ
                const totalChairWidth =
                chairCount * chairWidth + (chairCount - 1) * chairPadding; // ความกว้างรวมของเก้าอี้
                const startX = (width - totalChairWidth -2) / 2; // เริ่มจากจุดที่กึ่งกลางโต๊ะ
                for (let i = 0; i < chairCount; i++) {
                    const x = startX + i * (chairWidth + chairPadding); // ตำแหน่ง X ของเก้าอี้แต่ละตัว
                    const y = (0.15 * roomScale)+(minWidth8Px/2)-gapPxC; // ตำแหน่ง Y ของเก้าอี้ (ด้านบนของโต๊ะ)
                    chairPositions.push([x, y]);
                    rotationAngles.push(0); // หมุนเก้าอี้ (หันไปข้างหน้า)
                }
                return { chairPositions, rotationAngles };
            }
            function addChairs(chairPositions, chairColor, rotationAngles, container) {
                const roomWidthPx = $("#room").width();
                const roomHeightPx = $("#room").height();
                const roomConfig = rooms[$("#roomSelector").val()];
                const roomScale = roomWidthPx / roomConfig.width;

                const chairWidth = 0.4 * roomScale;
                const chairHeight = 0.25 * roomScale;

                chairPositions.forEach(([x, y], index) => {
                const chairElement = document.createElement("div");
                chairElement.className = "chair";
                chairElement.style.position = "absolute";
                chairElement.style.left = `${x}px`;
                chairElement.style.top = `${y}px`;
                chairElement.style.width = `${chairWidth}px`;
                chairElement.style.height = `${chairHeight}px`;
                chairElement.style.backgroundColor = chairColor;
                chairElement.style.borderRadius = "15px 15px 0 0"; // ทำให้ครึ่งวงกลมด้านบน
                chairElement.style.transform = `rotate(${rotationAngles[index]}deg)`; // หมุนเก้าอี้
                chairElement.style.transformOrigin = "50% 100%"; // จุดหมุนอยู่ด้านล่างกลาง

                container.appendChild(chairElement);
                // updateCounts();
                });
            }
            // ผูกปุ่มกับฟังก์ชัน
            $("#add-long-tables").on("click", () => openAddItemModal("table-long", 50));
            $("#add-round-tables").on("click", () =>
                openAddItemModal("table-round", 100)
            );
            const gridElement = document.getElementById("line-grid-A4");
            const toggleGridButton = document.getElementById("toggle-line-grid-A4");

            // Toggle grid visibility
            toggleGridButton.addEventListener("click", () => {
                const isGridVisible = gridElement.style.display !== "none";
                gridElement.style.display = isGridVisible ? "none" : "block";
            });
            gridElement.style.display = "none";
            function adjustGridSize() {
                const room = document.getElementById("room");
                const roomWidth = room.offsetWidth;
                const roomHeight = room.offsetHeight;

                const gridElement = document.getElementById("line-grid-A4");
                const selectedRoomKey = document.getElementById("roomSelector").value; // Get current room key
                const roomConfig = rooms[selectedRoomKey]; // Get current room configuration

                // Update grid dimensions to match the room
                gridElement.style.width = `${roomWidth}px`;
                gridElement.style.height = `${roomHeight}px`;

                // Calculate room scale (px per meter)
                const roomScale = roomWidth / roomConfig.width;

                // Calculate grid gap for 1 meter
                const gridGap = 0.5 * roomScale; // 1 meter in pixels
                gridElement.style.backgroundSize = `${gridGap}px ${gridGap}px`;

                // Calculate offset position of #room relative to #a4-container
                const roomRect = room.getBoundingClientRect();
                const containerRect = document
                .getElementById("a4-container")
                .getBoundingClientRect();
                const offsetX = roomRect.left - containerRect.left;
                const offsetY = roomRect.top - containerRect.top;

                // Adjust grid position
                gridElement.style.position = "absolute";

                if (roomConfig.type === "circle") {
                    // Center grid for circle room
                    gridElement.style.left = "50%";
                    gridElement.style.top = "50%";
                    gridElement.style.transform = "translate(-50%, -50%)";
                } else {
                    // Default position for other room types
                    gridElement.style.left = `${offsetX - 1}px`;
                    gridElement.style.top = `${offsetY - 1}px`;
                    gridElement.style.transform = "none";
                }
            }
            // Adjust grid size on window load and resize
            window.addEventListener("load", adjustGridSize);
            window.addEventListener("resize", adjustGridSize);

            // Update grid size whenever the room size changes dynamically
            const roomResizeObserver = new ResizeObserver(() => {
                adjustGridSize();
            });
            roomResizeObserver.observe(document.getElementById("room"));
        });
        $("input[name='addType']").on("change", function () {
            const selectedType = $("input[name='addType']:checked").val();

            if (selectedType === "table-only") {
                // ซ่อนฟิลด์จำนวนเก้าอี้
                $("label[for='chairCount']").hide();
                $("#chairCount").hide();

                // แสดงฟิลด์จำนวนโต๊ะ
                $("label[for='itemCount']").show();
                $("#itemCount").show();

                // คืนค่า label เดิมสำหรับจำนวนเก้าอี้
                $("label[for='chairCount']").text("Number of Chairs per Table");
            } else if (selectedType === "chair-only") {
                // ซ่อนฟิลด์จำนวนโต๊ะ
                $("label[for='itemCount']").hide();
                $("#itemCount").hide();

                // แสดงฟิลด์จำนวนเก้าอี้
                $("label[for='chairCount']").show();
                $("#chairCount").show();

                // เปลี่ยน label สำหรับจำนวนเก้าอี้
                $("label[for='chairCount']").text("Number of Chairs");
            } else if (selectedType === "table-and-chair") {
                // แสดงทั้งฟิลด์จำนวนโต๊ะและเก้าอี้
                $("label[for='itemCount']").show();
                $("#itemCount").show();
                $("label[for='chairCount']").show();
                $("#chairCount").show();

                // คืนค่า label เดิมสำหรับจำนวนเก้าอี้
                $("label[for='chairCount']").text("Number of Chairs per Table");
            }
        });
        function calculateRoomScale() {
            const roomWidthPx = $("#room").width();
            const roomConfig = rooms[$("#roomSelector").val()];
            return roomWidthPx / roomConfig.width; // คืนค่า roomScale
        }
        function convertToPx(value, roomScale) {
            return value * roomScale; // แปลงค่า (เช่น radius หรือ padding) เป็น pixel
        }
        function arrangeElementsInCircle(radius = 1) {
            if (selectedElements.size === 0) {
                alert("Please select some elements!");
                return;
            }
            const roomScale = calculateRoomScale();
            const radiusPx = convertToPx(radius, roomScale);

            console.log(radius);
            const centerX = 300; // Center X coordinate
            const centerY = 300; // Center Y coordinate
            const totalElements = selectedElements.size;
            const angleStep = (2 * Math.PI) / totalElements; // Angle step

            let currentAngle = 0;
            selectedElements.forEach((el) => {
                const x = centerX + radiusPx * Math.cos(currentAngle); // X position
                const y = centerY + radiusPx * Math.sin(currentAngle); // Y position

                el.style.left = `${x - el.offsetWidth / 2}px`; // Adjust X
                el.style.top = `${y - el.offsetHeight / 2}px`; // Adjust Y

                // Rotate the element to face the center
                const rotationAngle = (currentAngle * 180) / Math.PI + 90; // Convert to degrees and add 90
                el.style.transform = `rotate(${rotationAngle}deg)`; // Apply rotation
                el.style.transformOrigin = "50% 50%"; // Rotate from the center

                currentAngle += angleStep; // Increment angle
            });
        }
        function arrangeElementsInXAxis(padding = 1) {
            if (selectedElements.size === 0) {
                alert("Please select some elements!");
                return;
            }

            const roomScale = calculateRoomScale();
            const paddingPx = convertToPx(padding, roomScale);

            let minY = Infinity; // คำนวณตำแหน่ง Y ที่ต่ำที่สุด
            selectedElements.forEach((el) => {
                const elTop = parseInt(el.style.top, 10) || 0;
                minY = Math.min(minY, elTop);
            });

            let currentX = 50; // ตำแหน่งเริ่มต้นในแกน X
            selectedElements.forEach((el) => {
                el.style.left = `${currentX}px`; // ตั้งตำแหน่งในแกน X
                el.style.top = `${minY}px`; // ตำแหน่งในแกน Y (เท่าเดิม)
                currentX += el.offsetWidth + paddingPx; // เพิ่มตำแหน่งแกน X พร้อม padding
            });
        }
        function arrangeElementsInYAxis(padding = 1) {
            if (selectedElements.size === 0) {
            alert("Please select some elements!");
            return;
            }

            const roomScale = calculateRoomScale();
            const paddingPx = convertToPx(padding, roomScale);


            let minX = Infinity; // คำนวณตำแหน่ง X ที่ต่ำที่สุด
            selectedElements.forEach((el) => {
            const elLeft = parseInt(el.style.left, 10) || 0;
            minX = Math.min(minX, elLeft);
            });

            let currentY = 50; // ตำแหน่งเริ่มต้นในแกน Y
            selectedElements.forEach((el) => {
            el.style.top = `${currentY}px`; // ตั้งตำแหน่งในแกน Y
            el.style.left = `${minX}px`; // ตำแหน่งในแกน X (เท่าเดิม)
            currentY += el.offsetWidth + paddingPx; // เพิ่มตำแหน่งแกน Y พร้อม padding
            });
        }
    </script> --}}
    <script>
        // ดึงข้อมูลจาก URL
        const urlParams = new URLSearchParams(window.location.search);
        const selectedSetup = {
          id: urlParams.get("id"),
          eventTime: urlParams.get("eventTime"),
          room: urlParams.get("room"),
          details: urlParams.get("details"),
        };
        // แสดงข้อมูลที่หน้าใหม่
        document.getElementById("event-time").textContent = selectedSetup.eventTime;
        document.getElementById("place").textContent = selectedSetup.room;
        document.getElementById("setup").textContent = selectedSetup.details;
        console.log("Setup Details:", selectedSetup);
      </script>

    <script>
        // เปิด Modal "How to Add Points"
      document.getElementById("openHowToModalButton").addEventListener("click", function () {
        const howToModal = new bootstrap.Modal(document.getElementById("howToModal"));
        howToModal.show();
      });

      </script>
    <script>

        document.getElementById("print-pdf").addEventListener("click", () => {
          const container = document.getElementById("a4-container");

          // Ensure the container matches A4 dimensions
          container.style.width = "29.7cm"; // A4 width in cm
          container.style.height = "21cm"; // A4 height in cm

          html2canvas(container, {
            scale: 2, // Improve rendering quality
            useCORS: true // Allow cross-origin images if any
          }).then((canvas) => {
            const imgData = canvas.toDataURL("image/png");

            const pdf = new jspdf.jsPDF({
              orientation: "landscape",
              unit: "cm", // Use centimeters as the unit
              format: [29.7, 21] // A4 dimensions in cm (landscape)
            });

            pdf.addImage(
              imgData,
              "PNG",
              0,
              0,
              29.7, // A4 width in cm
              21 // A4 height in cm
            );
            pdf.save("room-layout.pdf");
          });
        });



        const room = document.getElementById("room");
        const selectedElements = new Set();
        let isSelecting = false;
        let selectionBox = null;
        let startX = 0,
          startY = 0;
        let isDragging = false;
        function makeDraggable(element) {
          let offsetX = 0,
            offsetY = 0;

          element.addEventListener("mousedown", (e) => {
            e.preventDefault();
            if (e.ctrlKey || e.metaKey) {
              selectedElements.has(element)
                ? selectedElements.delete(element)
                : selectedElements.add(element);
              element.classList.toggle("selected");
              return;
            }

            // const container = element.closest("#room") || document.getElementById("a4-container");
            const container = element.closest("#a4-container"); // Use #room if inside it
            const containerRect = container.getBoundingClientRect();
            startX = e.clientX - containerRect.left;
            startY = e.clientY - containerRect.top;

            if (selectedElements.has(element)) {
              // Drag a group of selected elements
              isDragging = true;
              const initialPositions = Array.from(selectedElements).map((el) => ({
                el,
                startLeft: parseInt(el.style.left, 10) || 0,
                startTop: parseInt(el.style.top, 10) || 0,
                width: el.offsetWidth,
                height: el.offsetHeight
              }));

              document.onmousemove = (e) => {
                e.preventDefault();
                const dx = e.clientX - containerRect.left - startX;
                const dy = e.clientY - containerRect.top - startY;

                initialPositions.forEach(
                  ({ el, startLeft, startTop, width, height }) => {
                    const newLeft = Math.max(
                      0,
                      Math.min(containerRect.width - width, startLeft + dx)
                    );
                    const newTop = Math.max(
                      0,
                      Math.min(containerRect.height - height, startTop + dy)
                    );

                    el.style.left = `${newLeft}px`;
                    el.style.top = `${newTop}px`;
                  }
                );
              };
            } else {
              // Drag a single element
              offsetX = element.offsetLeft;
              offsetY = element.offsetTop;
              document.onmousemove = (e) => {
                e.preventDefault();
                const newLeft = Math.max(
                  0,
                  Math.min(
                    containerRect.width - element.offsetWidth,
                    offsetX + e.clientX - containerRect.left - startX
                  )
                );
                const newTop = Math.max(
                  0,
                  Math.min(
                    containerRect.height - element.offsetHeight,
                    offsetY + e.clientY - containerRect.top - startY
                  )
                );

                element.style.left = `${newLeft}px`;
                element.style.top = `${newTop}px`;
              };
            }
            // Stop dragging on mouse up
            document.onmouseup = () => {
              document.onmousemove = null;
              isDragging = false;
            };
          });
        }

        // Selection logic for A4 container and room
        const container = document.getElementById("a4-container");
        const roomContainer = document.getElementById("room");
        container.addEventListener("mousedown", (e) => {
          e.preventDefault();
          if (
            e.target === container ||
            e.target === roomContainer ||
            e.target instanceof SVGElement // ตรวจสอบว่า e.target เป็น SVG
          ) {
            isSelecting = true;

            const containerRect = container.getBoundingClientRect();
            startX = e.clientX - containerRect.left;
            startY = e.clientY - containerRect.top;

            // Create selection box
            selectionBox = document.createElement("div");
            selectionBox.className = "selection-box";
            selectionBox.style.left = `${startX}px`;
            selectionBox.style.top = `${startY}px`;
            selectionBox.style.display = "block";
            container.appendChild(selectionBox);

            // Clear previous selection
            selectedElements.forEach((el) => el.classList.remove("selected"));
            selectedElements.clear();
          }
        });

        container.addEventListener("mousemove", (e) => {
          e.preventDefault();
          if (isSelecting) {
            const containerRect = container.getBoundingClientRect();
            const currentX = e.clientX - containerRect.left;
            const currentY = e.clientY - containerRect.top;

            const width = Math.abs(currentX - startX);
            const height = Math.abs(currentY - startY);

            selectionBox.style.width = `${width}px`;
            selectionBox.style.height = `${height}px`;

            if (currentX < startX) selectionBox.style.left = `${currentX}px`;
            if (currentY < startY) selectionBox.style.top = `${currentY}px`;
          }
        });

        container.addEventListener("mouseup", () => {
          if (isSelecting) {
            const selectionRect = selectionBox.getBoundingClientRect();
            document
              .querySelectorAll(".table-set,.text-element, .image-element, .yellow-box")
              .forEach((item) => {
                const itemRect = item.getBoundingClientRect();
                if (
                  selectionRect.left < itemRect.right &&
                  selectionRect.right > itemRect.left &&
                  selectionRect.top < itemRect.bottom &&
                  selectionRect.bottom > itemRect.top
                ) {
                  item.classList.add("selected");
                  selectedElements.add(item);
                }
              });

            if (selectionBox) {
              selectionBox.remove();
              selectionBox = null;
            }

            isSelecting = false;
          }
        });

        // Clear selection when clicking outside
        container.addEventListener("click", (e) => {
          if (
            (e.target === container || e.target === document.getElementById("room")) &&
            !isDragging
          ) {
            selectedElements.forEach((el) => el.classList.remove("selected"));
            selectedElements.clear();

            if (selectionBox) {
              selectionBox.remove();
              selectionBox = null;
            }
          }
        });

         // Define room configurations
          const rooms = {
            room1: {
              width: 24,
              height: 8,
              stages: [{ width: 4, height: 8, left: 20, top: 0 }],
              doors: [{ width: 0.2, height: 3, left: 0, top: 2 }]
            },
            room2: {
              width: 28,
              height: 8,
              stages: [{ width: 4, height: 8, left: 24, top: 0 }],
              doors: [
                { width: 0.2, height: 2.8, left: 0, top: 4.2 }
                // { width: 2, height: 3, left: 26, top: 4 },
              ],
              recordingRoom: {
                width: 2,
                height: 4,
                position: { top: 1, left: 2 }
              }
            },
            room3: {
              width: 32,
              height: 16,
              stages: [{ width: 4, height: 8, left: 28, top: 4 }],
              doors: [
                { width: 0.2, height: 3, left: 0, top: 0 },
                { width: 0.2, height: 3, left: 31.7, top: 0 },
                { width: 0.2, height: 3, left: 0, top: 12.9 }
              ]
            },
            room4: {
              type: "polygon",
              width: 28,
              height: 8,
              points: [
                { x: 0, y: 0 },
                { x: 28, y: 0 },
                { x: 28, y: 8 },
                { x: 20, y: 8 },
                { x: 20, y: 6 },
                { x: 15, y: 6 },
                { x: 15, y: 8 },
                { x: 5, y: 8 },
                { x: 5, y: 7 },
                { x: 0, y: 8 }
              ],
              stages: [{ width: 4, height: 8, left: 24, top: 0 }],
              doors: [{ width: 0.2, height: 2, left: 0, top: 2 }]
            },
            roomCircle: {
              type: "circle", // รูปแบบวงกลม
              width: 15, // เส้นผ่านศูนย์กลาง
              height: 15, // ใช้ความสูงเป็นเส้นผ่านศูนย์กลาง
              stages: [{ width: 3, height: 6, left: 11, top: 4 }],
              doors: [{ width: 0.2, height: 2, left: 0, top: 6 }]
            }
          };




        //จัดการห้อง Room
        $(document).ready(function () {
          const a4WidthPx = 1123; // A4 width in pixels
          const a4HeightPx = 794; // A4 height in pixels
          const margin = 10; // Margin of 10px

          // Function to set up the room
          function setupRoom(roomConfig) {
            // Calculate scaling factor to fit the A4 container with margins
            const roomScale = Math.min(
              (a4WidthPx - 120) / roomConfig.width,
              (a4HeightPx - 230) / roomConfig.height // Adjust for top and bottom margins
            );

            const roomWidthPx = roomConfig.width * roomScale;
            const roomHeightPx = roomConfig.height * roomScale;

            // Set room size and style
            $("#room")
              .css({
                width: `${roomWidthPx}px`,
                height: `${roomHeightPx}px`,
                margin: "auto",
                position: "relative",
                marginRight: "20px",

                border: "1px solid grey",
                clipPath: "none",
                WebkitClipPath: "none"
              })
              .empty();

            if (roomConfig.type === "polygon" && roomConfig.points) {
              $("#room").css({
                border: "none"
              });

              const scaledPoints = roomConfig.points.map((point) => {
                return `${point.x * roomScale},${point.y * roomScale}`;
              });

              const svgNamespace = "http://www.w3.org/2000/svg";

              // สร้าง SVG Element
              const svg = document.createElementNS(svgNamespace, "svg");
              svg.setAttribute("width", `${roomWidthPx}`);
              svg.setAttribute("height", `${roomHeightPx}`);
              svg.style.overflow = "visible";

              // สร้าง Polygon Element
              const polygon = document.createElementNS(svgNamespace, "polygon");
              polygon.setAttribute("points", scaledPoints.join(" "));
              polygon.setAttribute("style", "fill:white; stroke:grey; stroke-width:1");
              svg.appendChild(polygon);

              // เพิ่มข้อความความยาวและองศาของแต่ละด้าน
              roomConfig.points.forEach((point, index) => {
                const nextIndex = (index + 1) % roomConfig.points.length;
                const startX = point.x * roomScale;
                const startY = point.y * roomScale;
                const endX = roomConfig.points[nextIndex].x * roomScale;
                const endY = roomConfig.points[nextIndex].y * roomScale;

                // คำนวณความยาวระหว่างจุด
                const length = Math.sqrt(
                  Math.pow(roomConfig.points[nextIndex].x - point.x, 2) +
                    Math.pow(roomConfig.points[nextIndex].y - point.y, 2)
                ).toFixed(2); // เก็บทศนิยม 2 ตำแหน่ง

                // คำนวณตำแหน่งของข้อความ (ตรงกลางของเส้น)
                const textX = (startX + endX) / 2;
                const textY = (startY + endY) / 2;

                // คำนวณองศา (Angle)
                const deltaX = roomConfig.points[nextIndex].x - point.x;
                const deltaY = roomConfig.points[nextIndex].y - point.y;
                const angle = (Math.atan2(deltaY, deltaX) * 180) / Math.PI; // แปลง radians เป็น degrees
                const formattedAngle = angle.toFixed(2); // เก็บทศนิยม 2 ตำแหน่ง

                updateLengths(svg, roomConfig, roomScale);
              });

              // สร้างจุด `<circle>` ที่ลากได้สำหรับแต่ละจุด
              roomConfig.points.forEach((point, index) => {
                const circle = document.createElementNS(svgNamespace, "circle");
                const cx = point.x * roomScale;
                const cy = point.y * roomScale;
                const radius = 5;
                circle.setAttribute("cx", cx);
                circle.setAttribute("cy", cy);
                circle.setAttribute("r", radius);
                circle.setAttribute("fill", "red");
                circle.setAttribute("class", "draggable-point");
                circle.setAttribute("data-index", index); // เก็บ Index ของจุดไว้

                // เพิ่ม Event Listener สำหรับการลากจุด
                makePointDraggable(circle, polygon, roomConfig, roomScale, svg);
                svg.appendChild(circle);
              });

              $("#room").append(svg);
            }

            // ฟังก์ชันทำให้จุดลากได้
            function makePointDraggable(circle, polygon, roomConfig, roomScale, svg) {
              let isDragging = false;

              circle.addEventListener("mousedown", (e) => {
                isDragging = true;
              });

              document.addEventListener("mousemove", (e) => {
                if (!isDragging) return;

                const rect = $("#room")[0].getBoundingClientRect();
                let x = e.clientX - rect.left;
                let y = e.clientY - rect.top;

                // ปัดตำแหน่ง x และ y ให้เป็น step 0.1
                x = (Math.round((x / roomScale) * 10) / 10) * roomScale;
                y = (Math.round((y / roomScale) * 10) / 10) * roomScale;

                const index = parseInt(circle.getAttribute("data-index"), 10);
                circle.setAttribute("cx", x);
                circle.setAttribute("cy", y);

                // อัปเดตตำแหน่งของจุดใน roomConfig
                roomConfig.points[index].x = Math.round((x / roomScale) * 10) / 10;
                roomConfig.points[index].y = Math.round((y / roomScale) * 10) / 10;

                // อัปเดตตำแหน่ง Polygon
                const newPoints = roomConfig.points
                  .map((point) => `${point.x * roomScale},${point.y * roomScale}`)
                  .join(" ");
                polygon.setAttribute("points", newPoints);

                // อัปเดตข้อความความยาว
                updateLengths(svg, roomConfig, roomScale);
              });

              document.addEventListener("mouseup", () => {
                isDragging = false;
              });
            }

            // ฟังก์ชันอัปเดตข้อความความยาว, องศา, และจุดพิกัด
            function updateLengths(svg, roomConfig, roomScale) {
              // ลบข้อความเก่าทั้งหมด
              svg.querySelectorAll("text").forEach((text) => text.remove());
              roomConfig.points.forEach((point, index) => {
                const nextIndex = (index + 1) % roomConfig.points.length;
                const startX = point.x * roomScale;
                const startY = point.y * roomScale;
                const endX = roomConfig.points[nextIndex].x * roomScale;
                const endY = roomConfig.points[nextIndex].y * roomScale;

                // คำนวณความยาวระหว่างจุด
                const length = Number(
                  Math.sqrt(
                    Math.pow(roomConfig.points[nextIndex].x - point.x, 2) +
                      Math.pow(roomConfig.points[nextIndex].y - point.y, 2)
                  ).toFixed(2) // เก็บทศนิยม 2 ตำแหน่ง
                );

                // คำนวณองศา (Angle)
                const deltaX = roomConfig.points[nextIndex].x - point.x;
                const deltaY = roomConfig.points[nextIndex].y - point.y;
                const angle = (Math.atan2(deltaY, deltaX) * 180) / Math.PI; // แปลง radians เป็น degrees
                const formattedAngle = angle.toFixed(2); // เก็บทศนิยม 2 ตำแหน่ง

                // คำนวณตำแหน่งข้อความความยาว (กลางเส้น)
                const textX = (startX + endX) / 2;
                const textY = (startY + endY) / 2;

                // สร้าง Text Element สำหรับความยาว
                const lengthText = document.createElementNS(
                  "http://www.w3.org/2000/svg",
                  "text"
                );
                lengthText.setAttribute("x", textX);
                lengthText.setAttribute("y", textY - 5); // ตำแหน่งเหนือข้อความมุม
                lengthText.setAttribute("fill", "green");
                lengthText.setAttribute("font-size", "12px");
                lengthText.setAttribute("text-anchor", "middle");
                lengthText.setAttribute("class", "no-select");
                lengthText.textContent = `${Number(length)}m`;
                svg.appendChild(lengthText);

                // สร้าง Text Element สำหรับองศา
                // const angleText = document.createElementNS("http://www.w3.org/2000/svg", "text");
                // angleText.setAttribute("x", textX);
                // angleText.setAttribute("y", textY + 10); // ตำแหน่งใต้ข้อความความยาว
                // angleText.setAttribute("fill", "green");
                // angleText.setAttribute("font-size", "12px");
                // angleText.setAttribute("text-anchor", "middle");
                // angleText.setAttribute("class", "no-select");
                // angleText.textContent = `${formattedAngle}°`;
                // svg.appendChild(angleText);

                // สร้างข้อความระบุจุด (Point X: (x, y))
                const pointX = point.x.toFixed(1); // ตัดให้เหลือทศนิยม 1 ตำแหน่ง
                const pointY = point.y.toFixed(1); // ตัดให้เหลือทศนิยม 1 ตำแหน่ง
                const formattedX = Number(point.x.toFixed(1));
                const formattedY = Number(point.y.toFixed(1));
                const positionText = document.createElementNS(
                  "http://www.w3.org/2000/svg",
                  "text"
                );
                positionText.setAttribute("x", startX - 45); // วางข้อความด้านขวาของจุด
                positionText.setAttribute("y", startY - 5); // วางข้อความเหนือจุด
                positionText.setAttribute("fill", "rgb(49, 47, 47)");
                positionText.setAttribute("font-size", "12px");
                positionText.setAttribute("text-anchor", "start");
                positionText.setAttribute("class", "no-select");
                positionText.textContent = `Point ${
                  index + 1
                }: (${formattedX}, ${formattedY})`;
                svg.appendChild(positionText);
              });
            }

            if (roomConfig.type === "circle") {
              $("#room").css({
                margin: "auto",
                border: "none",
                position: "relative"
              });

              $("#line-grid-A4").css({
                position: "absolute",
                left: "50%"
              });
              // คำนวณค่ากึ่งกลางและรัศมี
              const ellipseCx = roomWidthPx / 2; // จุดกึ่งกลางแกน X
              const ellipseCy = roomHeightPx / 2; // จุดกึ่งกลางแกน Y
              const ellipseRx = roomWidthPx / 2; // รัศมีตามแกน X
              const ellipseRy = roomHeightPx / 2; // รัศมีตามแกน Y

              // วาดวงรีหรือวงกลม
              const ellipseSVG = `
                                <svg width="${roomWidthPx}" height="${roomHeightPx}" style="overflow: hidden;">
                                  <ellipse cx="${ellipseCx}" cy="${ellipseCy}" rx="${ellipseRx}" ry="${ellipseRy}"
                                    style="fill:white; stroke:black; stroke-width:1;overflow: hidden;" />
                                </svg>
                              `;
              $("#room").append(ellipseSVG);
            }

            // เรียกใช้ addStage
            (roomConfig.stages || []).forEach((stage) => {
              addStage(roomWidthPx, roomHeightPx, stage, roomScale);
            });

            // เรียกใช้ addDoor
            (roomConfig.doors || []).forEach((door) => {
              addDoor(roomWidthPx, roomHeightPx, door, roomScale);
            });

            // เพิ่มห้องควบคุมเสียง (ถ้ามี)
            if (roomConfig.recordingRoom) {
              addRecordingRoom(roomConfig.recordingRoom, roomScale);
            }

            // แสดงกล่องจุดและความยาวของ customroom
            function toggleVisibility(isVisible) {
              const visibility = isVisible ? "visible" : "hidden";
              $("svg .draggable-point").css(
                "display",
                visibility === "visible" ? "block" : "none"
              ); // ซ่อนหรือแสดงจุด
              $("svg text").css("display", visibility === "visible" ? "block" : "none"); // ซ่อนหรือแสดงข้อความความยาว
            }
            $("#tg-lengeAndPoint").on("change", function () {
              const isChecked = $(this).is(":checked");
              toggleVisibility(isChecked);
            });

            $(document).ready(function () {
              const isChecked = $("#tg-lengeAndPoint").is(":checked");
              toggleVisibility(isChecked); // ซิงค์สถานะเริ่มต้นของ checkbox
            });

            // แสดงกล่องที่จะเลือก customroom
            const roomType = roomConfig.type;
            if (roomType === "polygon") {
              $("#tg-lengeAndPoint").parent().show();
            } else {
              $("#tg-lengeAndPoint").parent().hide();
            }
          }
          // สร้างเวที
          function addStage(roomWidthPx, roomHeightPx, stage, roomScale) {
            const stageWidthPx = stage.width * roomScale;
            const stageHeightPx = stage.height * roomScale;

            const stageLeft = stage.left * roomScale;
            const stageTop = stage.top * roomScale;

            const stageElement = document.createElement("div");
            stageElement.className = "stage";
            stageElement.style.width = `${stageWidthPx}px`;
            stageElement.style.height = `${stageHeightPx}px`;
            stageElement.style.position = "absolute";
            stageElement.style.left = `${stageLeft}px`; // ตำแหน่งที่ปรับแล้ว
            stageElement.style.top = `${stageTop}px`; // ตำแหน่งที่ปรับแล้ว
            stageElement.style.backgroundColor = "rgba(141, 87, 25, 0.946)";
            stageElement.style.color = "white";
            stageElement.style.textAlign = "center";
            stageElement.style.cursor = "move";
            stageElement.style.lineHeight = `${stageHeightPx}px`;
            stageElement.innerHTML = "<span class='no-select'>Stage</span>";
            document.getElementById("room").appendChild(stageElement);
            // makeDraggable(stageElement);
          }

          // ประตู
          function addDoor(roomWidthPx, roomHeightPx, door, roomScale) {
            const doorWidthPx = door.width * roomScale;
            const doorHeightPx = door.height * roomScale;

            const doorLeftPx = door.left * roomScale;
            const doorTopPx = door.top * roomScale;

            const doorElement = document.createElement("div");
            doorElement.className = "door";
            doorElement.style.width = `${doorWidthPx}px`;
            doorElement.style.height = `${doorHeightPx}px`;
            doorElement.style.position = "absolute";
            doorElement.style.left = `${doorLeftPx}px`;
            doorElement.style.top = `${doorTopPx}px`;
            doorElement.style.zIndex = `1`;
            doorElement.style.backgroundColor = "brown";
            doorElement.style.cursor = "move";
            document.getElementById("room").appendChild(doorElement);
          }

          // ห้องอัดเสียง
          function addRecordingRoom(recordingRoom, roomScale) {
            const recWidthPx = recordingRoom.width * roomScale;
            const recHeightPx = recordingRoom.height * roomScale;
            const recLeft = recordingRoom.left * roomScale;
            const recTop = recordingRoom.top * roomScale;

            // สร้าง div สำหรับห้องควบคุมเสียง
            const recordingRoomElement = document.createElement("div");
            recordingRoomElement.className = "recording-room no-select";
            recordingRoomElement.style.position = "absolute";
            recordingRoomElement.style.width = `${recWidthPx}px`;
            recordingRoomElement.style.height = `${recHeightPx}px`;
            recordingRoomElement.style.left = `${recLeft}px`;
            recordingRoomElement.style.top = `${recTop}px`;
            recordingRoomElement.style.backgroundColor = "#d7d1dc";
            recordingRoomElement.style.textAlign = "center";
            recordingRoomElement.style.lineHeight = `${recHeightPx}px`;

            const textElement = document.createElement("p");
            textElement.className = "recording-room-text no-select";
            textElement.textContent = "Control Room";

            recordingRoomElement.appendChild(textElement);
            document.getElementById("room").appendChild(recordingRoomElement);
          }

          $("#clear-all").on("click", function () {
            clearCurrentRoom();
          });

          // ล้างห้องทั้งหมด
          function clearCurrentRoom() {
            $("#a4-container")
              .find(".table-set, .text-element, .image-element, .yellow-box")
              .remove();
            updateCounts();
          }

          $("#select-all").on("click", function () {
            const elements = $("#a4-container").find(
              ".table-set, .text-element, .image-element, .yellow-box"
            );

            // เพิ่มทุกองค์ประกอบใน selectedElements และเพิ่มคลาส selected
            elements.each(function () {
              $(this).addClass("selected");
              selectedElements.add(this);
            });
          });

          // เมื่อเปลี่ยนห้อง
          $("#roomSelector").on("change", function () {
            const selectedRoom = $(this).val();
            currentRoomKey = selectedRoom;
            clearCurrentRoom();
            setupRoom(rooms[selectedRoom]);
            // loadPolygonPointsForRoom(rooms[selectedRoom]);
            console.log("Room setup completed for:", selectedRoom);
          });

          let isEditMode = true; // ใช้ตัวแปรนี้เพื่อตรวจสอบว่าเป็น Add หรือ Edit
          // เรียกห้องเริ่มต้นเมื่อโหลดหน้า
          setupRoom(rooms.room1);

          // โหลดข้อมูลห้อง
          function loadRoomDetails(roomKey) {
            // Reset modal fields
            $("#roomName").val("");
            $("#roomType").val("");
            $("#roomWidth").val("");
            $("#roomHeight").val("");
            $("#doorList").empty();
            $("#stageList").empty();
            $("#pointsList").empty();
            $("#polygonPointsContainer").hide(); // Hide polygon container by default

            // Load the selected room configuration
            const roomConfig = rooms[roomKey];
            if (!roomConfig) return;

            $("#roomName").val(roomKey);
            $("#roomType").val(roomConfig.type || "rectangle");
            $("#roomWidth").val(roomConfig.width); // Convert from px to meters
            $("#roomHeight").val(roomConfig.height);

            // Load doors
            const $doorList = $("#doorList").empty();
            (roomConfig.doors || []).forEach((door, index) => {
              $doorList.append(`
                            <div class="door-item mb-2 d-grid-5column" data-index="${index}">
                              <div class="center">
                                <button type="button" class="btn btn-danger rounded-circle btn-sm remove-door-button">
                                  <i style="font-size:14px" class="fa">&#xf068;</i>
                                </button>
                              </div>
                              <div>
                                <label>Door Width</label>
                                <input type="number" class="form-control mb-1 door-width" placeholder="Width (m)" value="${door.width}">
                              </div>
                              <div>
                                <label>Door Height</label>
                                <input type="number" class="form-control mb-1 door-height" placeholder="Height (m)" value="${door.height}">
                              </div>
                              <div>
                                <label>Door Left</label>
                                <input type="number" class="form-control mb-1 door-left" placeholder="Left Position (m)" value="${door.left}">
                              </div>
                              <div>
                                <label>Door Top</label>
                                <input type="number" class="form-control door-top" placeholder="Top Position (m)" value="${door.top}">
                              </div>
                            </div>
                          `);
            });

            // Load stages
            const $stageList = $("#stageList").empty();
            (roomConfig.stages || []).forEach((stage, index) => {
              $stageList.append(`
                            <div class="stage-item mb-2 d-grid-5column" data-index="${index}">
                              <div class="center">
                                <button type="button" class="btn btn-danger rounded-circle btn-sm remove-stage-button">
                                  <i style="font-size:14px" class="fa">&#xf068;</i>
                                </button>
                              </div>
                              <div>
                                <label>Stage Width</label>
                                <input type="number" class="form-control mb-1 stage-width" placeholder="Width (m)" value="${stage.width}">
                              </div>
                              <div>
                                <label>Stage Height</label>
                                <input type="number" class="form-control mb-1 stage-height" placeholder="Height (m)" value="${stage.height}">
                              </div>
                              <div>
                                <label>Stage Left</label>
                                <input type="number" class="form-control mb-1 stage-left" placeholder="Left Position (m)" value="${stage.left}">
                              </div>
                              <div>
                                <label>Stage Top</label>
                                <input type="number" class="form-control stage-top" placeholder="Top Position (m)" value="${stage.top}">
                              </div>
                            </div>
                          `);
            });

            // Load polygon points if the room is a polygon
            if (roomConfig.type === "polygon") {
              const $pointsList = $("#pointsList").empty();
              (roomConfig.points || []).forEach((point, index) => {
                $pointsList.append(`
                              <div class="point-item mb-2">
                                <label>Point ${index + 1}</label>
                                <div class="sss" style="background-color:grey;">
                                  <input style="border-radius:0px;" type="number" class="form-control point-x" placeholder="X (m)" value="${
                                    point.x
                                  }">
                                  <input style="border-radius:0px;" type="number" class="form-control point-y" placeholder="Y (m)" value="${
                                    point.y
                                  }">
                                  <button class="btn btn-sm remove-point center"><i class="fa fa-trash-o text-white" style="font-size:18px mt-2"></i></button>
                                  </div>
                              </div>
                            `);
              });
              // Show the polygon points container
              $("#polygonPointsContainer").show();
            }
          }

          // AddStageButton
          $("#addStageButton").on("click", function () {
            const $stageList = $("#stageList");
            $stageList.append(`
                          <div class="stage-item mb-2 d-grid-5column">
                            <div class="center">
                              <button type="button" class="btn btn-danger rounded-circle btn-sm remove-stage-button">
                                <i style="font-size:14px" class="fa">&#xf068;</i>
                              </button>
                            </div>
                            <div>
                              <label>Stage Width</label>
                              <input type="number" class="form-control mb-1 stage-width" placeholder="Width (m)">
                            </div>
                            <div>
                              <label>Stage Height</label>
                              <input type="number" class="form-control mb-1 stage-height" placeholder="Height (m)">
                            </div>
                            <div>
                              <label>Stage Left</label>
                              <input type="number" class="form-control mb-1 stage-left" placeholder="Left Position (m)">
                            </div>
                            <div>
                              <label>Stage Top</label>
                              <input type="number" class="form-control stage-top" placeholder="Top Position (m)">
                            </div>
                          </div>
                        `);
          });

          $(document).on("click", ".remove-stage-button", function () {
            $(this).closest(".stage-item").remove();
          });

          // AddDoorButton
          $("#addDoorButton").on("click", function () {
            const $doorList = $("#doorList");
            $doorList.append(`
                            <div class="door-item mb-2 d-grid-5column">

                              <div class="center">
                                <button type="button" class="btn btn-danger rounded-circle btn-sm remove-door-button" ><i style="font-size:14px" class="fa">&#xf068;</i></button>
                              </div>

                              <div>
                                <label>Door Width</label>
                              <input type="number" class="form-control mb-1 door-width" placeholder="Width (m)">
                              </div>

                              <div>
                                <label>Door Height</label>
                              <input type="number" class="form-control mb-1 door-height" placeholder="Height (m)">
                              </div>

                              <div>
                                <label>Door Left</label>
                              <input type="number" class="form-control mb-1 door-left" placeholder="Left Position (m)">
                              </div>

                              <div>
                                <label>Door Top</label>
                              <input type="number" class="form-control door-top" placeholder="Top Position (m)">
                              </div>

                            </div>
                          `);
          });

          // ลบประตู
          $(document).on("click", ".remove-door-button", function () {
            $(this).closest(".door-item").remove();
          });

          let currentRoomKey = $("#roomSelector").val();

          // เปิด Modal สำหรับเพิ่มห้อง
          $("#addRoomButton").on("click", function () {
            isEditMode = false; // กำหนดเป็นโหมด Add
            $("#editRoomModalTitle").text("Add New Room");
            // รีเซ็ตค่าในฟอร์ม
            $("#roomType").val("rectangle");
            $("#roomName").val("");
            $("#roomWidth").val("");
            $("#roomHeight").val("");
            $("#stageList").empty();
            $("#doorList").empty();
            $("#pointsList").empty();
            $("#editRoomModal").modal("show");
            $("#editRoomModal").modal("show");
          });

          // เปิด Modal สำหรับแก้ไขห้อง
          $("#editRoomButton").on("click", function () {
            isEditMode = true; // กำหนดเป็นโหมด Edit
            $("#editRoomModalTitle").text("Edit Room Details");
            // เติมข้อมูลในฟอร์มสำหรับห้องปัจจุบัน
            loadRoomDetails(currentRoomKey);
            $("#editRoomModal").modal("show");
          });

          // บันทึกห้อง (ทั้ง Add และ Edit)
          $("#saveRoomChanges").on("click", function () {
            const roomName = $("#roomName").val().trim();
            const roomType = $("#roomType").val(); // Get selected type
            const roomWidth = parseFloat($("#roomWidth").val());
            const roomHeight = parseFloat($("#roomHeight").val());

            if (!roomName || isNaN(roomWidth) || isNaN(roomHeight)) {
              alert("Please fill in all required fields.");
              return;
            }

            // Prepare room config
            const roomConfig = {
              type: roomType,
              width: roomWidth,
              height: roomHeight,
              stages: [],
              doors: []
            };

            // Collect stage data
            const stages = [];
            $("#stageList .stage-item").each(function () {
              const stageWidth = parseFloat($(this).find(".stage-width").val());
              const stageHeight = parseFloat($(this).find(".stage-height").val());
              const stageLeft = parseFloat($(this).find(".stage-left").val());
              const stageTop = parseFloat($(this).find(".stage-top").val());

              if (
                !isNaN(stageWidth) &&
                !isNaN(stageHeight) &&
                !isNaN(stageLeft) &&
                !isNaN(stageTop)
              ) {
                stages.push({
                  width: stageWidth,
                  height: stageHeight,
                  left: stageLeft,
                  top: stageTop
                });
              }
            });
            roomConfig.stages = stages; // Add stages to room config

            // Add doors
            $("#doorList .door-item").each(function () {
              const doorWidth = parseFloat($(this).find(".door-width").val());
              const doorHeight = parseFloat($(this).find(".door-height").val());
              const doorLeft = parseFloat($(this).find(".door-left").val());
              const doorTop = parseFloat($(this).find(".door-top").val());

              if (
                !isNaN(doorWidth) &&
                !isNaN(doorHeight) &&
                !isNaN(doorLeft) &&
                !isNaN(doorTop)
              ) {
                roomConfig.doors.push({
                  width: doorWidth,
                  height: doorHeight,
                  left: doorLeft,
                  top: doorTop
                });
              }
            });

            // Add points for polygons
            if (roomType === "polygon") {
              roomConfig.points = [];
              roomConfig.points.push({ x: 0, y: 0 }); // Add the first point as (0, 0)
              $("#pointsList .point-item").each(function (index) {
                if (index === 0) return; // Skip the first point
                const pointX = parseFloat($(this).find(".point-x").val());
                const pointY = parseFloat($(this).find(".point-y").val());
                if (!isNaN(pointX) && !isNaN(pointY)) {
                  roomConfig.points.push({ x: pointX, y: pointY });
                }
              });
            }

            // Add or update room
            if (!isEditMode) {
              // Add new room
              rooms[roomName] = roomConfig;
              $("#roomSelector").append(
                `<option value="${roomName}">${roomName}</option>`
              );
            } else {
              // Edit existing room
              const originalRoomName = currentRoomKey; // เก็บชื่อห้องเดิมไว้
              if (roomName !== originalRoomName) {
                // ถ้าชื่อห้องถูกเปลี่ยน
                delete rooms[originalRoomName]; // ลบชื่อห้องเก่า
                rooms[roomName] = roomConfig; // เพิ่มชื่อห้องใหม่
                $(`#roomSelector option[value="${originalRoomName}"]`)
                  .val(roomName)
                  .text(roomName); // อัปเดต Dropdown
              } else {
                rooms[roomName] = roomConfig; // อัปเดตข้อมูลห้อง
              }
            }

            // อัปเดตค่าที่เลือกใน Dropdown
            $("#roomSelector").val(roomName);

            // Close modal and refresh room
            currentRoomKey = roomName; // ตั้งค่าห้องปัจจุบันเป็นห้องที่เพิ่งบันทึก
            setupRoom(rooms[currentRoomKey]);
            $("#editRoomModal").modal("hide");
          });

          // Show/hide points input based on type
          $("#roomType").on("change", function () {
            if ($(this).val() === "polygon") {
              $("#polygonPointsContainer").show();
            } else {
              $("#polygonPointsContainer").hide();
            }
          });

          // Add new point for polygons
          $("#addPointButton").on("click", function () {
            const index = $("#pointsList .point-item").length + 1;
            $("#pointsList").append(`
                          <div class="point-item mb-2" data-index="${index}">
                            <label>Point ${index}</label>
                            <div class="sss d-flex" style="background-color:grey;">
                              <input style="border-radius:0px;" type="number" class="form-control point-x" placeholder="X (m)">
                              <input style="border-radius:0px;" type="number" class="form-control point-y" placeholder="Y (m)">
                              <button class="btn btn-sm remove-point center"><i class="fa fa-trash-o text-white" style="font-size:18px mt-2"></i></button>
                            </div>
                          </div>
                        `);
          });

          // Remove point
          $("#pointsList").on("click", ".remove-point", function () {
            $(this).closest(".point-item").remove();

            // อัปเดตหมายเลขจุดใหม่หลังจากลบ
            $("#pointsList .point-item").each(function (index) {
              $(this).attr("data-index", index + 1); // อัปเดต data-index
              $(this)
                .find("label")
                .text(`Point ${index + 1}`); // อัปเดต label
            });
          });

          // ลบห้อง
          $("#deleteRoomButton").on("click", function () {
            if (!currentRoomKey) {
              alert("กรุณาเลือกห้องที่ต้องการลบ");
              return;
            }

            const confirmation = confirm(
              `คุณต้องการลบห้อง "${currentRoomKey}" หรือไม่?`
            );
            if (confirmation) {
              // ลบห้องออกจากออบเจกต์ rooms
              delete rooms[currentRoomKey];

              // ลบตัวเลือกใน Dropdown
              $(`#roomSelector option[value="${currentRoomKey}"]`).remove();

              // ตั้งค่าให้ห้องถัดไปเป็นห้องที่เลือกอยู่
              const nextRoomKey = Object.keys(rooms)[0]; // ห้องแรกในออบเจกต์
              if (nextRoomKey) {
                currentRoomKey = nextRoomKey;
                setupRoom(rooms[nextRoomKey]);
                $("#roomSelector").val(nextRoomKey);
              } else {
                // ถ้าไม่มีห้องเหลือ
                $("#room").empty(); // เคลียร์หน้าจอ
                currentRoomKey = null;
              }
            }
          });

          // ฟังก์ชันเปิด Modal เพื่อเพิ่มออบเจกต์
          function openAddItemModal(className, top) {
            currentClassName = className;
            startTop = top;
            const defaultGap = className === "table-long" ? 0 : 0.8;
            if (className === "table-long") {
              $("#itemCount").val(1); // จำนวนโต๊ะดีฟอลต์สำหรับโต๊ะยาว
              $("#chairCount").val(3); // จำนวนเก้าอี้ต่อโต๊ะดีฟอลต์สำหรับโต๊ะยาว
            } else if (className === "table-round") {
              $("#itemCount").val(1); // จำนวนโต๊ะดีฟอลต์สำหรับโต๊ะกลม
              $("#chairCount").val(8); // จำนวนเก้าอี้ต่อโต๊ะดีฟอลต์สำหรับโต๊ะกลม
            }
            $("#gapValue").val(defaultGap);
            $("#addItemModal").modal("show");
          }

          // เมื่อกดปุ่มยืนยันใน Modal
          $("#addItemConfirm").on("click", function () {
            const tableCount = parseInt($("#itemCount").val()) || 0;
            const chairCount = parseInt($("#chairCount").val()) || 0;
            const tableColor = $("#tableColor").val();
            const chairColor = $("#chairColor").val();
            const gap =
              parseInt($("#gapValue").val()) ||
              (currentClassName === "table-long" ? 0 : 0.8);

            const addType = $("input[name='addType']:checked").val();

            let addChairsOnly = false;
            let addTableOnly = false;

            if (addType === "chair-only") addChairsOnly = true;
            if (addType === "table-only") addTableOnly = true;

            if (addChairsOnly && chairCount === 0) {
              alert("กรุณาใส่จำนวนเก้าอี้!");
              return;
            }

            if (tableCount > 0) {
              addElement(
                currentClassName,
                tableCount,
                startTop,
                chairCount,
                tableColor,
                chairColor,
                gap,
                addChairsOnly,
                addTableOnly
              );
              $("#addItemModal").modal("hide");
            } else {
              alert("กรุณาใส่จำนวนที่ถูกต้อง!");
            }
          });

          // ฟังก์ชันเพิ่มออบเจ็กต์
          function addElement(
            className,
            tableCount,
            startTop,
            chairCount,
            tableColor,
            chairColor,
            gap,
            addChairsOnly = false,
            addTableOnly = false
          ) {
            const roomWidthPx = $("#room").width();
            const roomHeightPx = $("#room").height();
            const roomConfig = rooms[$("#roomSelector").val()];
            const roomScale = roomWidthPx / roomConfig.width;

            const roundTableDiameterPx = 1.5 * roomScale;
            const longTableWidthPx = 1.8 * roomScale;
            const longTableHeightPx = 0.45 * roomScale;
            const chairSizePx = 0.7 * roomScale;
             const minWidth8Px = 0.8 * roomScale;

           let gapPx; // กำหนดตัวแปร gapPx ภายนอก
            if (addTableOnly || addChairsOnly) {
              gap = Math.max(gap, 0.8);
              gapPx = gap * roomScale; // คำนวณ gap สำหรับกรณี addTableOnly หรือ addChairsOnly
            } else {
              gap = Math.max(gap, 0.8);
              const gapPxL = gap * roomScale; // คำนวณ gap สำหรับโต๊ะยาว
              const gapPxR = (gap * roomScale) + chairSizePx; // คำนวณ gap สำหรับโต๊ะกลม
              gapPx = className === "table-long" ? gapPxL : gapPxR; // เลือก gap ตามประเภทโต๊ะ
            }

            let currentLeft = 50; // เริ่มจากตำแหน่งซ้าย
            let currentTop = startTop; // เริ่มจากตำแหน่งบน

            for (let i = 0; i < tableCount; i++) {
              const tableWidthPx =
                className === "table-long" ? longTableWidthPx : roundTableDiameterPx;
              const tableHeightPx =
                className === "table-long" ? longTableHeightPx : roundTableDiameterPx;

              if (currentLeft + tableWidthPx > roomWidthPx) {
                currentLeft = 50; // รีเซ็ตตำแหน่งซ้าย
                currentTop += tableHeightPx  + gapPx; // ย้ายลงมาด้านล่าง
              }

              if (currentTop + tableHeightPx > roomHeightPx) {
                alert("Not enough space to add more tables!");
                break;
              }

              if (addChairsOnly) {
                // Add chairs only, each in a separate container
                for (let j = 0; j < chairCount; j++) {
                  const roomWidthPx = $("#room").width();
                  const roomHeightPx = $("#room").height();
                  const roomConfig = rooms[$("#roomSelector").val()];
                  const roomScale = roomWidthPx / roomConfig.width;


                  const chairWidth = 0.4 * roomScale;
                  const chairHeight = 0.25 * roomScale;

                  const chairContainer = document.createElement("div");
                  chairContainer.className = "table-set";
                  chairContainer.style.position = "absolute";
                  chairContainer.style.left = `${currentLeft}px`;
                  chairContainer.style.top = `${currentTop}px`;
                  // chairContainer.style.border = "1px solid red";
                  chairContainer.style.minWidth = `${chairWidth + 2}px`;
                  chairContainer.style.minHeight = `${chairHeight + 2}px`;

                  const chairElement = document.createElement("div");
                  chairElement.className = "chair";
                  chairElement.style.position = "absolute";
                  chairElement.style.width = `${chairWidth}px`;
                  chairElement.style.height = `${chairHeight}px`;
                  chairElement.style.backgroundColor = chairColor;
                  chairElement.style.borderRadius = "15px 15px 0 0"; // Rounded top
                  chairElement.style.transform = "rotate(0deg)"; // Default rotation
                  chairElement.style.transformOrigin = "50% 100%"; // Rotation pivot point

                  // Adjust chairContainer size based on table type
                  if (className === "table-long") {
                    chairContainer.style.width = `${chairWidth}px`;
                    chairContainer.style.height = `${chairHeight}px`;
                  } else if (className === "table-round") {
                    chairContainer.style.width = `${chairWidth}px`;
                    chairContainer.style.height = `${chairHeight}px`;
                  }

                  chairContainer.appendChild(chairElement); // Add chair to the container
                  $("#a4-container").append(chairContainer); // Add container to the room
                  makeDraggable(chairContainer); // Make draggable
                  updateCounts();

                  // Update position for the next chair
                  currentLeft += chairWidth + gapPx;
                  if (currentLeft + chairWidth > roomWidthPx) {
                    currentLeft = 50;
                    currentTop += chairHeight + gapPx;
                  }
                }
                return; // Skip the rest of the loop for `addChairsOnly`
              }

              // สร้าง div สำหรับ set (โต๊ะและเก้าอี้)
              const setContainer = document.createElement("div");
              setContainer.className = "table-set";
              setContainer.style.position = "absolute";
              setContainer.style.left = `${currentLeft}px`;
              setContainer.style.top = `${currentTop}px`;

              // setContainer.style.border= '1px solid green'

              // กำหนดขนาด setContainer ตามประเภทของโต๊ะและกรณีที่เลือก
              if (addTableOnly) {
                if (className === "table-long") {
                  setContainer.style.width = `${tableWidthPx+ minWidth8Px}px`;
                  setContainer.style.height = `${(chairSizePx   / 1.4)+ minWidth8Px}px`;
                } else if (className === "table-round") {
                  setContainer.style.width = `${tableWidthPx + minWidth8Px}px`;
                  setContainer.style.height = `${tableWidthPx + minWidth8Px}px`;
                }
              } else {
                if (className === "table-long") {
                  setContainer.style.width = `${tableWidthPx}px`; // รวมระยะเก้าอี้ซ้ายและขวา
                  setContainer.style.height = `${tableHeightPx +(chairSizePx/2) + minWidth8Px +(0.2*roomScale)}px`; // รวมระยะเก้าอี้ด้านบนและล่าง
                } else if (className === "table-round") {
                  setContainer.style.width = `${tableWidthPx + chairSizePx + minWidth8Px}px`; // รวมระยะเก้าอี้รอบด้าน
                  setContainer.style.height = `${tableHeightPx + chairSizePx + minWidth8Px}px`; // รวมระยะเก้าอี้รอบด้าน
                  setContainer.style.borderRadius = `50%`;
                }
              }


              // เพิ่มโต๊ะ
              if (!addChairsOnly) {
                const tableElement = document.createElement("div");
                tableElement.className = `${className}`;
                tableElement.style.position = "absolute";
                tableElement.style.width = `${tableWidthPx}px`;
                tableElement.style.height = `${tableHeightPx}px`;
                tableElement.style.borderRadius =
                  className === "table-round" ? "50%" : "0";
                tableElement.style.backgroundColor = tableColor;

                if (addTableOnly) {
                  if (className === "table-round") {
                    tableElement.style.top = `${0 * roomScale}px`;
                    tableElement.style.left = `${0 * roomScale}px`;
                  }
                } else {
                  if (className === "table-long") {
                    tableElement.style.bottom = `${0.4 * roomScale}px`;
                    tableElement.style.left = `${(0 * roomScale-1)}px`;
                  } else if (className === "table-round") {
                    tableElement.style.top = `${0.75 * roomScale}px`;
                    tableElement.style.left = `${0.75 * roomScale}px`;
                  }
                }
                setContainer.appendChild(tableElement);
              }

              // เพิ่มเก้าอี้
              if (!addTableOnly && chairCount > 0) {
                if (className === "table-round") {
                  const { chairPositions, rotationAngles } = calculateRoundTableChairs(
                    currentLeft,
                    currentTop,
                    roundTableDiameterPx,
                    chairCount
                  );
                  addChairs(chairPositions, chairColor, rotationAngles, setContainer);
                } else if (className === "table-long") {
                  const { chairPositions, rotationAngles } = calculateLongTableChairs(
                    currentLeft,
                    currentTop,
                    longTableWidthPx,
                    longTableHeightPx,
                    chairCount
                  );
                  addChairs(chairPositions, chairColor, rotationAngles, setContainer);
                }
              }

              // เพิ่ม container ลงใน room
              $("#a4-container").append(setContainer);
              makeDraggable(setContainer);
              currentLeft += tableWidthPx + gapPx; // เพิ่มตำแหน่งซ้ายพร้อม gap
            }
            updateCounts();
          }

          function calculateRoundTableChairs(left, top, diameter, chairCount) {
            const roomWidthPx = $("#room").width();
            const roomHeightPx = $("#room").height();
            const roomConfig = rooms[$("#roomSelector").val()];
            const roomScale = roomWidthPx / roomConfig.width;
            const roundTableDiameterPx = 1.5 * roomScale;
            const chairPositions = [];
            const rotationAngles = [];
            const radius = diameter / 2 + 0.12 * roomScale; // เพิ่มระยะห่างจากโต๊ะ;
            const centerX = diameter / 2 + 0.76 * roomScale;
            const centerY = diameter / 2 + 0.73 * roomScale;

            for (let i = 0; i < chairCount; i++) {
              const angle = (2 * Math.PI * i) / chairCount; // มุมในเรเดียน
              const x = centerX + radius * Math.cos(angle) - 15 * (0.015 * roomScale);
              const y = centerY + radius * Math.sin(angle) - 15 * (0.015 * roomScale);
              const rotationAngle = (angle * 180) / Math.PI + 90;
              chairPositions.push([x, y]);
              rotationAngles.push(rotationAngle);
            }
            return { chairPositions, rotationAngles };
          }

          function calculateLongTableChairs(
            left,
            top,
            width,
            height,
            chairCount,
            addChairsOnly = false
          ) {
            const roomWidthPx = $("#room").width();
            const roomHeightPx = $("#room").height();
            const roomConfig = rooms[$("#roomSelector").val()];
            const roomScale = roomWidthPx / roomConfig.width;

            const chairPositions = [];
            const rotationAngles = [];
            const chairPadding = 0.225 * roomScale; // ระยะห่างระหว่างเก้าอี้
            const chairWidth = 0.38 * roomScale;
            const chairHeight = 0.35 * roomScale;
            const minWidth8Px = 0.8 * roomScale;
            const gapPxC = 0.07 * roomScale;

            // กรณีเพิ่มเฉพาะเก้าอี้
            if (addChairsOnly) {
              const totalChairWidth =
                chairCount * chairWidth + (chairCount - 1) * chairPadding; // ความกว้างรวมของเก้าอี้
              const startX = 3; // เริ่มจากจุดกลางโต๊ะไปทางซ้าย
              const y = height - 0.7 * roomScale; // ตำแหน่ง Y ให้อยู่ด้านบน

              for (let i = 0; i < chairCount; i++) {
                const x = startX + i * (chairWidth + chairPadding); // กระจายตำแหน่ง X ของเก้าอี้
                chairPositions.push([x, y]);
                rotationAngles.push(180); // หมุนเก้าอี้ (หันไปข้างหน้า)
              }
              return { chairPositions, rotationAngles };
            }

            // คำนวณจุดเริ่มต้นให้เก้าอี้อยู่กึ่งกลางโต๊ะ
            const totalChairWidth =
              chairCount * chairWidth + (chairCount - 1) * chairPadding; // ความกว้างรวมของเก้าอี้
            const startX = (width - totalChairWidth -2) / 2; // เริ่มจากจุดที่กึ่งกลางโต๊ะ
            for (let i = 0; i < chairCount; i++) {
              const x = startX + i * (chairWidth + chairPadding); // ตำแหน่ง X ของเก้าอี้แต่ละตัว
              const y = (0.15 * roomScale)+(minWidth8Px/2)-gapPxC; // ตำแหน่ง Y ของเก้าอี้ (ด้านบนของโต๊ะ)
              chairPositions.push([x, y]);
              rotationAngles.push(0); // หมุนเก้าอี้ (หันไปข้างหน้า)
            }
            return { chairPositions, rotationAngles };
          }

          function addChairs(chairPositions, chairColor, rotationAngles, container) {
            const roomWidthPx = $("#room").width();
            const roomHeightPx = $("#room").height();
            const roomConfig = rooms[$("#roomSelector").val()];
            const roomScale = roomWidthPx / roomConfig.width;

            const chairWidth = 0.4 * roomScale;
            const chairHeight = 0.25 * roomScale;

            chairPositions.forEach(([x, y], index) => {
              const chairElement = document.createElement("div");
              chairElement.className = "chair";
              chairElement.style.position = "absolute";
              chairElement.style.left = `${x}px`;
              chairElement.style.top = `${y}px`;
              chairElement.style.width = `${chairWidth}px`;
              chairElement.style.height = `${chairHeight}px`;
              chairElement.style.backgroundColor = chairColor;
              chairElement.style.borderRadius = "15px 15px 0 0"; // ทำให้ครึ่งวงกลมด้านบน
              chairElement.style.transform = `rotate(${rotationAngles[index]}deg)`; // หมุนเก้าอี้
              chairElement.style.transformOrigin = "50% 100%"; // จุดหมุนอยู่ด้านล่างกลาง

              container.appendChild(chairElement);
              // updateCounts();
            });
          }

          // ผูกปุ่มกับฟังก์ชัน
          $("#add-long-tables").on("click", () => openAddItemModal("table-long", 50));
          $("#add-round-tables").on("click", () =>
            openAddItemModal("table-round", 100)
          );

          const gridElement = document.getElementById("line-grid-A4");
          const toggleGridButton = document.getElementById("toggle-line-grid-A4");

          // Toggle grid visibility
          toggleGridButton.addEventListener("click", () => {
            const isGridVisible = gridElement.style.display !== "none";
            gridElement.style.display = isGridVisible ? "none" : "block";
          });
          // Initially hide the grid
          gridElement.style.display = "none";
          function adjustGridSize() {
            const room = document.getElementById("room");
            const roomWidth = room.offsetWidth;
            const roomHeight = room.offsetHeight;

            const gridElement = document.getElementById("line-grid-A4");
            const selectedRoomKey = document.getElementById("roomSelector").value; // Get current room key
            const roomConfig = rooms[selectedRoomKey]; // Get current room configuration

            // Update grid dimensions to match the room
            gridElement.style.width = `${roomWidth}px`;
            gridElement.style.height = `${roomHeight}px`;

            // Calculate room scale (px per meter)
            const roomScale = roomWidth / roomConfig.width;

            // Calculate grid gap for 1 meter
            const gridGap = 0.5 * roomScale; // 1 meter in pixels
            gridElement.style.backgroundSize = `${gridGap}px ${gridGap}px`;

            // Calculate offset position of #room relative to #a4-container
            const roomRect = room.getBoundingClientRect();
            const containerRect = document
              .getElementById("a4-container")
              .getBoundingClientRect();
            const offsetX = roomRect.left - containerRect.left;
            const offsetY = roomRect.top - containerRect.top;

            // Adjust grid position
            gridElement.style.position = "absolute";

            if (roomConfig.type === "circle") {
              // Center grid for circle room
              gridElement.style.left = "50%";
              gridElement.style.top = "50%";
              gridElement.style.transform = "translate(-50%, -50%)";
            } else {
              // Default position for other room types
              gridElement.style.left = `${offsetX - 1}px`;
              gridElement.style.top = `${offsetY - 1}px`;
              gridElement.style.transform = "none";
            }
          }

          // Adjust grid size on window load and resize
          window.addEventListener("load", adjustGridSize);
          window.addEventListener("resize", adjustGridSize);

          // Update grid size whenever the room size changes dynamically
          const roomResizeObserver = new ResizeObserver(() => {
            adjustGridSize();
          });
          roomResizeObserver.observe(document.getElementById("room"));
        });
        // ตรวจสอบเมื่อผู้ใช้เปลี่ยนตัวเลือก
        $("input[name='addType']").on("change", function () {
          const selectedType = $("input[name='addType']:checked").val();

          if (selectedType === "table-only") {
            // ซ่อนฟิลด์จำนวนเก้าอี้
            $("label[for='chairCount']").hide();
            $("#chairCount").hide();

            // แสดงฟิลด์จำนวนโต๊ะ
            $("label[for='itemCount']").show();
            $("#itemCount").show();

            // คืนค่า label เดิมสำหรับจำนวนเก้าอี้
            $("label[for='chairCount']").text("Number of Chairs per Table");
          } else if (selectedType === "chair-only") {
            // ซ่อนฟิลด์จำนวนโต๊ะ
            $("label[for='itemCount']").hide();
            $("#itemCount").hide();

            // แสดงฟิลด์จำนวนเก้าอี้
            $("label[for='chairCount']").show();
            $("#chairCount").show();

            // เปลี่ยน label สำหรับจำนวนเก้าอี้
            $("label[for='chairCount']").text("Number of Chairs");
          } else if (selectedType === "table-and-chair") {
            // แสดงทั้งฟิลด์จำนวนโต๊ะและเก้าอี้
            $("label[for='itemCount']").show();
            $("#itemCount").show();
            $("label[for='chairCount']").show();
            $("#chairCount").show();

            // คืนค่า label เดิมสำหรับจำนวนเก้าอี้
            $("label[for='chairCount']").text("Number of Chairs per Table");
          }


        });

        function calculateRoomScale() {
          const roomWidthPx = $("#room").width();
          const roomConfig = rooms[$("#roomSelector").val()];
          return roomWidthPx / roomConfig.width; // คืนค่า roomScale
        }

        function convertToPx(value, roomScale) {
          return value * roomScale; // แปลงค่า (เช่น radius หรือ padding) เป็น pixel
        }



          function arrangeElementsInCircle(radius = 1) {
          if (selectedElements.size === 0) {
            alert("Please select some elements!");
            return;
          }
            const roomScale = calculateRoomScale();
            const radiusPx = convertToPx(radius, roomScale);

          console.log(radius);
          const centerX = 300; // Center X coordinate
          const centerY = 300; // Center Y coordinate
          const totalElements = selectedElements.size;
          const angleStep = (2 * Math.PI) / totalElements; // Angle step

          let currentAngle = 0;
          selectedElements.forEach((el) => {
            const x = centerX + radiusPx * Math.cos(currentAngle); // X position
            const y = centerY + radiusPx * Math.sin(currentAngle); // Y position

            el.style.left = `${x - el.offsetWidth / 2}px`; // Adjust X
            el.style.top = `${y - el.offsetHeight / 2}px`; // Adjust Y

            // Rotate the element to face the center
            const rotationAngle = (currentAngle * 180) / Math.PI + 90; // Convert to degrees and add 90
            el.style.transform = `rotate(${rotationAngle}deg)`; // Apply rotation
            el.style.transformOrigin = "50% 50%"; // Rotate from the center

            currentAngle += angleStep; // Increment angle
          });
        }

        function arrangeElementsInXAxis(padding = 1) {
          if (selectedElements.size === 0) {
            alert("Please select some elements!");
            return;
          }

           const roomScale = calculateRoomScale();
          const paddingPx = convertToPx(padding, roomScale);

          let minY = Infinity; // คำนวณตำแหน่ง Y ที่ต่ำที่สุด
          selectedElements.forEach((el) => {
            const elTop = parseInt(el.style.top, 10) || 0;
            minY = Math.min(minY, elTop);
          });

          let currentX = 50; // ตำแหน่งเริ่มต้นในแกน X
          selectedElements.forEach((el) => {
            el.style.left = `${currentX}px`; // ตั้งตำแหน่งในแกน X
            el.style.top = `${minY}px`; // ตำแหน่งในแกน Y (เท่าเดิม)
            currentX += el.offsetWidth + paddingPx; // เพิ่มตำแหน่งแกน X พร้อม padding
          });
        }

        function arrangeElementsInYAxis(padding = 1) {
          if (selectedElements.size === 0) {
            alert("Please select some elements!");
            return;
          }

        const roomScale = calculateRoomScale();
          const paddingPx = convertToPx(padding, roomScale);


          let minX = Infinity; // คำนวณตำแหน่ง X ที่ต่ำที่สุด
          selectedElements.forEach((el) => {
            const elLeft = parseInt(el.style.left, 10) || 0;
            minX = Math.min(minX, elLeft);
          });

          let currentY = 50; // ตำแหน่งเริ่มต้นในแกน Y
          selectedElements.forEach((el) => {
            el.style.top = `${currentY}px`; // ตั้งตำแหน่งในแกน Y
            el.style.left = `${minX}px`; // ตำแหน่งในแกน X (เท่าเดิม)
            currentY += el.offsetWidth + paddingPx; // เพิ่มตำแหน่งแกน Y พร้อม padding
          });
        }

          </script>
</html>
