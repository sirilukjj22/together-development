@extends('layouts.test')

@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai+Looped:wght@100;200;300;400;500;600;700;800;900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- เพิ่มลิงก์ CSS ของ Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <!-- ลิงก์ JavaScript ของ jQuery -->

    <!-- ลิงก์ JavaScript ของ Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <style>
        .container {
        margin-top: 40px;
        background-color: white;
        padding: 5% 5%;
        overflow-x: hidden;
        }
        input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }
        input[type=tel], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }
        input[type=tel1], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }

        input[type="date"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        font-size: 16px;
        background-color: #f8f8f8; /* เพิ่มสีพื้นหลัง */
    }
    input[type="number"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .button-guest{
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        border-width: 1px;
        border-radius: 8px;
        float: right;
        margin-Top: 10px;
        margin-Left: 100px;
        text-align: center;

    }
    .button-g{
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        margin-Left: 1px;
        text-align: center;

    }
    .button-guest-end{
        background-color:#ff0000;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        float: left;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        text-align: center;

    }
    .textarea{
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .add-phone {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: pointer;
    }

    .add-phone:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-phone:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }
    .add-input {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: pointer;
    }

    .add-input:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-input:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    /* สไตล์สำหรับปุ่ม "Add Fax" */
    .add-fax {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: pointer;
    }

    .add-fax:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-fax:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    /* สไตล์เพิ่มเติมตามที่ต้องการ */
    .input-group {
        margin-bottom: 8px;
    }
    .remove-input,
    .remove-fax,
    .remove-phone {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #dc3545; /* สีแดง */
        border-color: #dc3545; /* สีเหลือง */
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: not-allowed;
    }
    .custom-accordion {
    border: 1px solid #ccc;
    margin-bottom: 20px;
    border-radius: 5px; /* เพิ่มขอบมนเข้าไป */
    overflow: hidden; /* ทำให้มีการคอยรับเส้นขอบ */
  }
  .custom-accordion input[type="checkbox"] {
    display: none;
  }
  .custom-accordion label {
    font-size: 18px;
    background-color: #f0f0f0;
    display: block;
    cursor: pointer;
    padding: 15px 20px;
    border-bottom: 1px solid #ccc;
  }
  .custom-accordion label::before {
    content: "\2610"; /* Unicode for empty checkbox */
    margin-right: 10px;
    font-size: 24px;
  }
  .custom-accordion input[type="checkbox"]:checked + label::before {
    content: "\2611"; /* Unicode for checked checkbox */
  }
  .custom-accordion-content {
    font-size: 16px;
    padding: 20px;
    display: none;
    border-top: 1px solid #ccc; /* เพิ่มขอบด้านบน */
  }
  .custom-accordion input[type="checkbox"]:checked + label + .custom-accordion-content {
    display: block;
  }
   .button1 {
            background-color: white;
            border: 2px solid #ccc;
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            border-width: 1px;
        border-radius: 8px;
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button1:hover {
            background-color: #555;
            color: white;
        }

        .button1.clicked {
            background-color: green;
            color: white;
        }
        .buttonstyle button {
        width: 10%;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .buttonstyle button:hover {
        background-color: #ccc;
    }
    .titleh1 {
        font-size: 32px;
    }
    </style>
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            <div class="titleh1 col-9 my-3">
                <h1>Company (องค์กร)</h1>
            </div>
            <div class="col-3">

            </div>
        </div>

        <div class="row buttonstyle">
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-cc">
                <button class="" onclick="window.location.href = '{{ url('/Company/edit/'.$Company->id) }}'">Company</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-c">
                <button class="" onclick="window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'">Contact</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-d">
                <button class="" onclick="window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}'">Detail</button>
            </div>
        </div>
        <br>
        <div class="mt-4">
            <div class="col-12 row" >
                <div class="col-1"style="  text-align: center; margin-Top: 20px;">Search</div>
                <div class="col-3">
                    <input type="text" class="form-control" name="search" placeholder="search"/>
                </div>

                <div class="col-4"></div>

            </div>
            <div class="col-12 row mt-3">
                <ul class="nav nav-tabs">
                    <li   li class="nav-item">
                        <a class="nav-link" onclick="openHistory(event, 'Summary_Visit_info')" id="defaultOpen">Summary Visit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="openHistory(event, 'Lastest_Visit_info')" >Lastest Visit </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " onclick="openHistory(event, 'Billing Folio info')" >Billing Folio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  onclick="openHistory(event, 'Latest Freelancer By')">Latest Freelancer By</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " onclick="openHistory(event, 'Lastest Freelancer Commission')" >Lastest Freelancer Commission</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " onclick="openHistory(event, 'Contract Rate Document')" >Contract Rate Document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " onclick="openHistory(event, 'User logs')" >User logs</a>
                    </li>
                </ul>
                <div id="Summary_Visit_info" class="tabcontent">
                    <div class="row">
                        <table class="table" >
                            <thead>
                                <tr>
                                    <th scope="col"class="text-center">#</th>
                                   <th scope="col"class="text-center">NO. Quotation</th>
                                    <th scope="col"class="text-center">Document date</th>
                                    <th scope="col"class="text-center">Room Rev.</th>
                                    <th scope="col"class="text-center">F&B Rev.</th>
                                    <th scope="col"class="text-center">Sqa </th>
                                    <th scope="col"class="text-center">Banquest</th>
                                    <th scope="col"class="text-center">Other Rev.</th>
                                    <th scope="col"class="text-center">Total Rev.</th>
                                    <th scope="col"class="text-center">Pax</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="col"class="text-center">1</td>
                                    <td scope="col"class="text-center">Q6702005</td>
                                    <td scope="col"class="text-center">25/2/2024</td>
                                    <td scope="col"class="text-center">7,225.00</td>
                                    <td scope="col"class="text-center">0.00</td>
                                    <td scope="col"class="text-center">0.00</td>
                                    <td scope="col"class="text-center">0.00</td>
                                    <td scope="col"class="text-center">0.00</td>
                                    <td scope="col"class="text-center">7,225.00</td>
                                    <td scope="col"class="text-center">7</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="Lastest_Visit_info" class="tabcontent mt-3">
                    <div class="row" >
                        <table class="table">
                                <thead class="table-active">
                                    <tr>
                                        <th scope="col"class="text-center">ชื่อสถาบัน</th>
                                        <th scope="col"class="text-center">คณะ</th>
                                        <th scope="col"class="text-center">สาขา</th>
                                        <th scope="col"class="text-center">เกรดเฉลี่ย</th>
                                        <th scope="col"class="text-center">จบการศึกษา</th>
                                        <th scope="col"class="text-center">วุฒิการศึกษา </th>
                                        <th scope="col"class="text-center">ประเภทสถาบัน</th>
                                        <th scope="col"class="text-center">ตัวเลือก</th>
                                    </tr>
                                </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            document.getElementById("defaultOpen").click(); // เปิดแท็บ Summary Visit info เมื่อหน้าโหลด
            document.getElementById("lastestOpen").click();}
        function openHistory(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("nav-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
            }
            $(document).ready(function(){
                $('.select2').select2();
        });
    </script>
@endsection
