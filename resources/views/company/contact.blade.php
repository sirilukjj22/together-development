@extends('layouts.test')

@section('content')

    <style>
        .container {
        margin-top: 40px;
        background-color: white;
        padding: 5% 5%;
        overflow-x: hidden;
    }
    .form-select{
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
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
        .btn_left {
            display: flex;
            justify-content: flex-start; /* จัดตำแหน่งให้อยู่ทางซ้าย */
        }
        .buttonstyle button {
        width: 10%;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
        background-color: #fff;
    }

    .buttonstyle button:hover {
        background-color: #ccc;
    }
    .titleh1 {
        font-size: 32px;
    }
    .select2-container{
            width: 100% !important;
        }
        .button-return {
    align-items: center;
    appearance: none;
    background-color: #6b6b6b;
    color: #fff;
    border-radius: 8px;
    border-style: none;
    box-shadow: rgba(0, 0, 0, 0.2) 0 3px 5px -1px,
      rgba(0, 0, 0, 0.14) 0 6px 10px 0, rgba(0, 0, 0, 0.12) 0 1px 18px 0;
    box-sizing: border-box;
    cursor: pointer;
    display: inline-flex;
    fill: currentcolor;
    font-size: 14px;
    font-weight: 500;
    height: 40px;
    justify-content: center;
    letter-spacing: 0.25px;
    line-height: normal;
    max-width: 100%;
    overflow: visible;
    padding: 2px 24px;
    position: relative;
    text-align: center;
    text-transform: none;
    transition: box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1),
      opacity 15ms linear 30ms, transform 270ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    touch-action: manipulation;
    width: auto;
    will-change: transform, opacity;
    margin-left: 5px;
  }

  .button-return:hover {
    background-color: #ffffff !important;
    color: #000000;
    transform: scale(1.1);
  }
    </style>
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            <div class="titleh1 col-9 my-3">
                <h1>Company (องค์กร)</h1>
            </div>

        </div>

        <div class="row buttonstyle">
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-cc">
                <button style="" onclick="window.location.href = '{{ url('/Company/edit/'.$Company->id) }}'">Company</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-c">
                <button class="" onclick="window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'">Contact</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-d">
                <button class=""onclick="window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}'">Detail</button>
            </div>
        </div>



        <div class="add " >
            <button type="button" class="button-17 button-18" data-toggle="modal"
                data-target="#createContart">เพิ่มตัวแทนบริษัท</button>

        </div><br><br>
        <button class="statusbtn mt-4" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            สถานะการใช้งาน &#11206;
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" onclick="window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'">ทั้งหมด</a>
            <a class="dropdown-item" style="color: green;" onclick="window.location.href = '{{ url('/Company/contact/acCon/' . $Company->id . '?value=1') }}'">เปิดใช้งาน</a>
            <a class="dropdown-item" style="color: #f44336;" onclick="window.location.href = '{{ url('/Company/contact/noCon/' . $Company->id . '?value=0') }}'">ปิดใช้งาน</a>
        </div>
        <div class="modal fade" id="createContart" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter2Title" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มตัวแทนบริษัท</h5>
                </div>
                <div class="modal-body-split">
                    <form  id="myForm" action="{{url('/Company/edit/contact/create/'.$Company->id)}}"  method="POST">
                    {!! csrf_field() !!}
                        <div class="col-12 row">
                            <div class="col-lg-1 col-md-1 col-sm-12"></div>
                            <div class="col-lg-2 col-md-6 col-sm-12" ><label for="prefix">Title</label><br>
                                <select name="prefix" id="PrefaceSelect" class="form-select" required>
                                    <option value=""></option>
                                        @foreach($Mprefix as $item)
                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                        @endforeach
                                </select></div>
                            <div class="col-lg-4 col-md-6 col-sm-12" ><label for="first_name">First Name</label><br>
                            <input type="text" id="first_nameAgent" name="first_nameAgent"maxlength="70" required></div>
                            <div class="col-lg-4 col-md-6 col-sm-12" ><label for="last_name" >Last Name</label><br>
                            <input type="text" id="last_nameAgent" name="last_nameAgent"maxlength="70" required></div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-lg-1 col-md-1 col-sm-12"></div>
                            <div class="col-lg-3 col-md-6 col-sm-12 mt-2">
                                <label for="Country">Country</label><br>
                                <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()">
                                    <option value="Thailand">ประเทศไทย</option>
                                    <option value="Other_countries">ประเทศอื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 mt-2" id="cityInputA" style="display:none;">
                                <label for="City">City</label><br>
                                <input type="text" id="cityA" name="cityA">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 mt-2" id="citythaiA" style="display:block;">
                                <label for="City">City</label><br>
                                <select name="provinceAgent" id="provinceAgent" class="select2" onchange="provinceA()" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                <label for="Amphures">Amphures</label><br>
                                <select name="amphuresA" id="amphuresA" class="select2" onchange="amphuresAgent()" >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-lg-1 col-md-6 col-sm-12"></div>
                            <div class="col-lg-3 col-md-6 col-sm-12 mt-2">
                                <label for="Tambon">Tambon</label><br>
                                <select name="TambonA" id ="TambonA" class="select2" onchange="TambonAgent()" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 mt-2">
                                <label for="zip_code">zip_code</label><br>
                                <select name="zip_codeA" id ="zip_codeA" class="select2"  style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Email">Email</label><br>
                                <input type="text" id="EmailAgent" name="EmailAgent"maxlength="70" required>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-lg-1 col-md-6 col-sm-12"></div>
                            <div class="col-lg-10 col-md-10 col-sm-12" >
                                <label for="Address">Address</label><br>
                                <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="textarea" aria-label="With textarea" required></textarea>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-lg-1 col-md-6 col-sm-12"></div>
                            <div class="col-lg-4 col-md-6 col-sm-12" >
                                <label for="Phone_number">Phone Number</label><br>
                                <div id="phone-container" class="flex-container">
                                    <!-- ตำแหน่งนี้จะใส่ input เดียวในตอนเริ่มต้น -->
                                    <div class="phone-group" >
                                        <input type="text" name="phone[]" id="phone-main"class="form-control" maxlength="10" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                                        <div id="add-phone-orther"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-lg-1 col-md-6 col-sm-12"></div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <button type="button" class="add-phone"id="add-phone" data-target="phone-container">เพิ่ม</button>
                                <button type="button" class="remove-phone " >ลบ</button>
                            </div>
                        </div>
                        <div class="modal-footer mt-5">
                            <button type="button" class="button-return"data-dismiss="modal">Close</button>
                            <button type="submit" onclick="confirmSubmit(event)" class="button-10" style="background-color: #109699;">Save changes</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
        <form enctype="multipart/form-data" id="form-id2">
            @csrf
            <table id="example" class="display">
                <thead>
                    <tr>
                        <th>
                            <label class="custom-checkbox">
                                <input type="checkbox" onClick="toggle(this)"/>
                                <span class="checkmark"></span>
                            </label>ทั้งหมด
                        </th>
                        <th style="text-align: center;">ลำดับ</th>
                        <th style="text-align: center;">รหัสโปรไฟล์</th>
                        <th>ชื่อองค์กร</th>
                        <th>สาขา</th>
                        <th>ชื่อผู้ใช้งาน</th>
                        <th>นามสกุลผู้ใช้งาน</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($representative))
                        @foreach ($representative as $key => $item)
                            <tr>
                                <td data-label="เลือก">
                                    <label class="custom-checkbox">
                                    <input name="dummy" type="checkbox" data-record-id="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="รหัสลูกค้า">{{ $item->Profile_ID}}</td>
                                <td data-label="ตัวย่อ">{{ $item->Company_Name }}</td>
                                <td data-label="ตัวย่อ">{{ $item->Branch }}</td>
                                <td data-label="ชื่อผู้ใช้งาน">{{ $item->First_name }}</td>
                                <td data-label="นามสกุลผู้ใช้งาน">{{ $item->Last_name }}</td>
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status === 1)
                                        <button type="button" class="button-1 status-toggle" data-id="{{ $item->id }}"data-status="{{ $item->status }} "data-company="{{ $Company->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="button-3 status-toggle " data-id="{{ $item->id }}" data-status="{{ $item->status }} "data-company="{{ $Company->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="button-18 button-17" type="button" data-toggle="dropdown">ทำรายการ
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li class="licolor"><a href="{{ url('/Company/edit/contact/editcontact/'.$Company->id.'/'.$item->id) }}">แก้ไขข้อมูล</a></li>
                                            <li class="licolor"><a href="#" class="delete" title="Delete" data-toggle="tooltip" onclick="confirmDelete(this)" data-id="{{ $item->id }}" data-company="{{ $Company->id }}">ลบข้อมูล</a></li>
                                        </ul>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </form>

    </div>
    <script>
        $(document).ready(function() {
            new DataTable('#example', {
                //ajax: 'arrays.txt'
                // scrollX: true,
            });

            $('.select2').select2({
                dropdownParent: $('#createContart')
            });
        });


        function showcityAInput() {
            var countrySelectA = document.getElementById("countrySelectA");
            var cityInputA = document.getElementById("cityInputA");
            var citythaiA = document.getElementById("citythaiA");
            var amphuresSelect = document.getElementById("amphuresA");
            var tambonSelect = document.getElementById("TambonA");
            var zipCodeSelect = document.getElementById("zip_codeA");
            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
            if (countrySelectA.value === "Other_countries") {
                // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                cityInputA.style.display = "block";
                citythaiA.style.display = "none";
                // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
            } else if (countrySelectA.value === "Thailand"){
                                 // เช็คค่าที่ถูกเลือกใน dropdown list เมือง

                        // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                        cityInputA.style.display = "none";
                        citythaiA.style.display = "block";
                        // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                        amphuresSelect.disabled = false;
                        tambonSelect.disabled = false;
                        zipCodeSelect.disabled = false;

                // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                amphuresAgent();
            }

        }


//---------------------------------------------------2------------------------------
            function provinceA(){
            var provinceAgent = $('#provinceAgent').val();
            jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Company/amphuresA/"+provinceAgent+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                    jQuery('#amphuresA').children().remove().end();
                    console.log(result);
                    $('#amphuresA').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var amphuresA = new Option(value.name_th,value.id);
                        //console.log(amphuresA);
                        $('#amphuresA').append(amphuresA);
                    });
                },
            })

        }
        function amphuresAgent(){
            var amphuresAgent  = $('#amphuresA').val();
            console.log(amphuresAgent);
            $.ajax({
                type:   "GET",
                url:    "{!! url('/Company/TambonA/"+amphuresAgent+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                   // console.log(result);
                    jQuery('#TambonA').children().remove().end();
                    $('#TambonA').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var TambonA  = new Option(value.name_th,value.id);
                        $('#TambonA').append(TambonA );
                       // console.log(TambonA);
                    });
                },
            })
        }
        function TambonAgent(){
            var TambonAgent  = $('#TambonA').val();
            console.log(TambonAgent);
            $.ajax({
                type:   "GET",
                url:    "{!! url('/Company/districtsA/"+TambonAgent+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                    console.log(result);
                    jQuery('#zip_codeA').children().remove().end();
                    //console.log(result);
                    $('#zip_codeA').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var zip_codeA  = new Option(value.zip_code,value.zip_code);
                        $('#zip_codeA').append(zip_codeA);
                        //console.log(zip_codeA);
                    });
                },
            })
        }
        //Company_Phone

//phene_contract
document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-phone');
        const removeButton = document.querySelector('.remove-phone');
        const phoneContainer = document.getElementById('phone-container');

        let phoneCount = 1;

        addButton.addEventListener('click', function() {
            const phoneGroup = document.createElement('div');
            phoneGroup.classList.add('phone-group');
            phoneGroup.innerHTML = `
                <input type="text" name="phone[]"  class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
            `;
            phoneContainer.appendChild(phoneGroup);
            phoneCount++;
        });

        removeButton.addEventListener('click', function() {
            if (phoneCount > 1) {
                const phoneGroups = phoneContainer.querySelectorAll('.phone-group');
                const lastPhoneGroup = phoneGroups[phoneGroups.length - 1];
                phoneContainer.removeChild(lastPhoneGroup);
                phoneCount--;
            }
        });
    });

    </script>
 <script>
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting

        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();

            var message = `หากบันทึกข้อมูลบริษัท ${Company_Name} สาขา ${Branch} หรือไม่`;
            Swal.fire({
                title: "คุณต้องการบันทึกใช่หรือไม่?",
                text: message,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#28a745",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, submit the form
                    document.getElementById("myForm").submit();
                }
            });
        }


    var alertMessage = "{{ session('alert_') }}";
    var alerterror = "{{ session('error_') }}";
    if(alertMessage) {
        // แสดง SweetAlert ทันทีเมื่อโหลดหน้าเว็บ
        Swal.fire({
            icon: 'success',
            title: alertMessage,
            showConfirmButton: false,
            timer: 1500
        });
    }if(alerterror) {
        Swal.fire({
            icon: 'error',
            title: alerterror,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function confirmDelete(element) {
    var itemId = $(element).data('id');
    var companyId = $(element).data('company');

    Swal.fire({
        title: "คุณต้องการลบใช่หรือไม่?",
        text: "หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้!",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "ลบข้อมูล",
        cancelButtonText: "ยกเลิก",
        confirmButtonColor: "#B22222",
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {
            // ถ้าผู้ใช้คลิก "ตกลง"
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: "GET",
                url: "{{ url('/Company/edit/contact/delete') }}" + '/' + companyId + '/' + itemId,
                dataType: "JSON",
                success: function(response) {

                    if (response.success) {
                        Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success').then(() => {
                            location.reload(); // รีเฟรชหน้าเว็บเพื่อแสดงการเปลี่ยนแปลง
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาดในการลบข้อมูล', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('เกิดข้อผิดพลาดในการลบข้อมูล', xhr.responseText, 'error');
                }
            });
        } else {
            // ถ้าผู้ใช้คลิก "ยกเลิก"
            Swal.fire('การเปลี่ยนแปลงไม่ถูกบันทึก', '', 'info');
        }
    });
    return false; // เพื่อป้องกันการนำลิงก์ไปยัง URL หลังจากแสดง SweetAlert2
}

    $(document).ready(function() {
    $('.status-toggle').click(function() {
        var button = $(this);
        var id = button.data('id');
        var status = button.data('status');
        var companyId = button.data('company');
        var newStatus = status === 1 ? 0 : 1; // Toggle status
        var token = "{{ csrf_token() }}"; // รับ CSRF token จาก Laravel

        // ทำ AJAX request
        $.ajax({
            type: 'POST',
            url: "{{ url('/Company/contact/change-status/') }}" + '/' + companyId,
            data: {
                _token: token, // เพิ่ม CSRF token ในข้อมูลของ request
                ids: id,
                status: newStatus
            },
            success: function(response) {
                // ปรับเปลี่ยนสถานะบนหน้าเว็บ
                console.log(response.Company);
                button.data('status', newStatus);
                if (newStatus == 1) {
                    // เปลี่ยนสถานะจากปิดเป็นเปิด
                    button.removeClass('button-3').addClass('button-1').html('ใช้งาน');
                } else {
                    // เปลี่ยนสถานะจากเปิดเป็นปิด
                    button.removeClass('button-1').addClass('button-3').html('ปิดใช้งาน');
                }
                Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                location.reload();
            }
        });
    });
});
</script>
@endsection
