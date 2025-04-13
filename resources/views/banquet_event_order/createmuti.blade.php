@extends('layouts.masterLayout')
<style>
    @media screen and (max-width: 500px) {
    .mobileHidden {
        display: none;
    }

    .mobileLabelShow {
        display: inline;
    }

    #mobileshow {
        margin-top: 60px;
    }
    }

    .dt-container {
    min-height: max-content;
    }
    @media (max-width: 768px) {
        .image-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .image-container img.logo {
            margin-bottom: 20px;
            width: 50%;
        }
        .Profile{
            width: 100%;
        }
    }
</style>
<style>
    .image-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: left;
    }
    .image-container img.logo {
        width: 15%; /* กำหนดขนาดคงที่ */
        height: auto;
        margin-right: 20px;
    }

    .image-container .info {
        margin-top: 0;
    }

    .image-container .info p {
        margin: 5px 0;
    }
    .dataTables_empty {
    display: none; /* ซ่อนข้อความ */
    /* หรือสามารถปรับแต่งสไตล์อื่น ๆ ได้ที่นี่ */
    }
    .image-container .titleh1 {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .table-container {
    margin-bottom: 20px;
    }

    .table-grey-banquet {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    border: 1px solid rgb(200, 197, 197);
    color:rgb(94, 93, 93);
    }

    .table-grey-banquet th,
    .table-grey-banquet td {
    padding: 8px;
    text-align: left;
    font-size: 14px;
    background-color: white;
    text-align: center;
    border-bottom: 1px solid rgb(188, 188, 188) !important;
    border-left: 1px solid rgb(188, 188, 188) !important;
    }

    .table-grey-banquet th {
    border-top: 1px solid rgb(188, 188, 188) !important;
    background-color: #68c2bc5b !important;
    font-weight: bold;
    white-space: nowrap;
    color:rgb(54, 54, 54);
    }

    .section {
    margin-bottom: 10px;
    border: 1px solid grey;
    }

    .section>div:nth-child(2) {
    padding: 10px;
    }

    .section p {
    margin-bottom: 5px;
    }

    .section-title {
    background-color: #2c7f7a;
    color: white;
    font-weight: bold;
    padding: 8px;
    font-size: 16px;
    text-align: center;
    }

    .last-bdbt-none tr:nth-last-child(1) {
    border: none;
    }

    .d-grid-2column-banquet {
    display: grid;
    grid-template-columns: 1fr 1fr;
    }

    @media (max-width:1000px) {
    .d-grid-2column-banquet {
        display: flex;
        flex-wrap: wrap;
    }
    }

    .d-grid-2column-banquet>div:nth-child(n+1) {
    flex-grow: 1;
    }

    .date-setup {
    color: #1a5854;
    font-weight: 550;
    }

    .bt-ed-dl {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
    }

    .bt-ed-dl>button {
    border: none;
    padding: 5px;
    width: 35px;
    height: 35px;
    border-radius: 5px;
    }

    .bt-ed-dl>button.ed {
    border: 1px solid #FFC107;
    background-color: #ffc10730;
    }

    .bt-ed-dl>button.ed:hover,
    .bt-ed-dl>button.ed:focus {
    background-color: #FFC107;
    }

    .bt-ed-dl>button.dl {
    border: 1px solid #DC3545;
    background-color: #da294f2f;
    }

    .bt-ed-dl>button.dl:hover,
    .bt-ed-dl>button.dl:focus {
    background-color: #DC3545;
    }
    .modal-dialog.modal-fullscreen {
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
        max-width: none;
    }

    .modal-fullscreen .modal-content {
        height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .readonly-input {
        background-color: #ffffff !important;/* สีพื้นหลังขาว */
    }

    .readonly-input:focus {
        background-color: #ffffff !important;/* ให้สีพื้นหลังขาวเมื่ออยู่ในสถานะโฟกัส */
        box-shadow: none; /* ลบเงาเมื่อโฟกัส */
        border-color: #ced4da; /* ให้เส้นขอบมีสีเทาอ่อนเพื่อให้เหมือนกับการไม่ได้โฟกัส */
    }
</style>
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class="span3">Create Banquet Event Order</div>
            </div>
            <div class="col-auto">

            </div>
        </div> <!-- .row end -->
    </div>
</div>
<form id="myForm" action="{{url('/Banquet/Event/Order/save/detail/'.$Proposal->id)}}" method="POST">
    @csrf
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div>
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-3 mb-3">
                        <div for="Header">
                            <section>
                                <div class="flex-between">
                                    <div class="d-flex">
                                        <div class="col-lg-7 col-md-12 col-sm-12 image-container">
                                            <img src="{{ asset('assets/images/' . $settingCompany->image) }}" alt="Together Resort Logo" class="logo"/>
                                            <div class="info">
                                                <li class="font-w-600">{{$settingCompany->name}}</li>
                                                <li class="left-4px font-w-600"> *** Head Office / Headquarters </li>
                                                <li>{{$settingCompany->address}}</li>
                                                <li> Tel : {{$settingCompany->tel}}  @if ($settingCompany->fax) | Fax : {{$settingCompany->fax}} @endif </li>
                                                <li>HOTEL TAX ID {{$settingCompany->Hotal_ID}}</li>
                                                <li class="w-spaceWrap-less860px"> <span> website: {{$settingCompany->web}} |</span><span> Email: {{$settingCompany->email}} </span> </li>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <li>
                                        <strong>{{$BEOID}}</strong>
                                        <input type="hidden" name="BEOID" id="BEOID" value="{{$BEOID}}">
                                        </li>
                                        <li>Reference : {{$Proposal->Quotation_ID}}</li>
                                        <li>Issue Date : {{$Proposal->issue_date}}</li>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <h5 class="text-center border-top py-2 text-capitalize mt-2"> Banquet event order </h5>
                        <div for="Account Information">
                            <div class="flex-end mb-2">
                                <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#addAccountEventDetailsModal" id="addEvent" style="display: block;"> Add </button>
                                <button type="button" class="btn btn-warning lift btn_modal" data-bs-toggle="modal" data-bs-target="#addAccountEventDetailsModal" id="editEvent" style="display: none;"><img src="{{ asset('image/meetingRoom/edit.png') }}" width="20" /></button>
                            </div>
                            <div class="d-grid-2column-banquet">
                                <div class="section">
                                    <div class="section-title">Account Information</div>
                                    <div>
                                        <p>
                                            <strong>Group Name :</strong>
                                            <span>{{$fullName}}</span>
                                        </p>
                                        <p>
                                            <strong>Address :</strong>
                                            <span>{{$address}}</span>
                                        </p>
                                        <hr style="border: 1px solid #000000;" />
                                        <p>
                                            <strong>Contact :</strong>
                                            <span>{{$contact ?? '-'}}</span>


                                        </p>
                                        <p>
                                            <strong>Phone :</strong>
                                            <span>{{$phone->Phone_number}}</span>
                                        </p>
                                        <p>
                                            <strong>Email :</strong>
                                            <span>{{$Email}}</span>
                                        </p>
                                        <p>
                                            <strong>Onsite Contact :</strong>
                                            <span>-</span>
                                        </p>
                                    </div>
                                </div>
                                <!-- Event Details Section -->
                                <div class="section">
                                    <div class="section-title">Event Details</div>
                                    <div>
                                        <p style="color: #1a5854">
                                            <strong>Event Date :</strong>
                                            <span id="Event_Date">{{$banquet->event_date}}</span>
                                        </p>
                                        <hr style="border: 1px solid #000000;" />
                                        <p>
                                            <strong>Sales By :</strong>
                                            <span>{{$user->name}}</span>
                                            <input type="hidden" id="sales" name="sales" value="{{$user->name}}">
                                        </p>
                                        <p>
                                            <strong>Catering By :</strong>
                                            <span id="Catering">{{$banquet->catering}}</span>
                                        </p>
                                        <hr style="border: 1px solid #000000;" />
                                        <div>
                                            จำนวนทีมงาน : <span id="Number_of_teams">{{$banquet->number}}</span> ท่าน
                                        </div>
                                        <div>
                                            รายละเอียดยานพาหนะ : <span id="Details_of_the_vehicle">{{$banquet->vehicle}}</span>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="addAccountEventDetailsModal" tabindex="-1" aria-labelledby="addAccountEventDetailsModalLabel"aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addAccountEventDetailsModalLabel">
                                                    Event Details
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="AccountEventDetails">
                                                        <div class="row">
                                                            <!-- Event Details -->
                                                            <div class="d-grid-2column">
                                                                <div class="mb-3">
                                                                    <label for="eventDate" class="form-label">Event Date</label>
                                                                    <div class="input-group">
                                                                        @php
                                                                            $formattedDate = date('d/m/Y', strtotime($banquet->event_date));
                                                                        @endphp
                                                                        <input type="text" name="eventDate" id="eventDate" placeholder="DD/MM/YYYY" class="form-control readonly-input" value="{{$formattedDate}}" readonly>
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                                <!-- ไอคอนปฏิทิน -->
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="cateringBy" class="form-label" >Catering By</label>
                                                                    <input type="text" class="form-control" id="cateringBy" name="catering" placeholder="Enter catering contact" value="{{$banquet->catering}}"/>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="teamCount" class="form-label">จำนวนทีมงาน</label>
                                                                    <input type="text" class="form-control" id="team" name="team" placeholder="Enter number of team members"  value="{{$banquet->number}}"/>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="vehicleDetails" class="form-label" >รายละเอียดยานพาหนะ</label>
                                                                    <textarea class="form-control" name="vehicle" id="vehicle" rows="2" placeholder="Enter vehicle details" >{{$banquet->vehicle}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button"  class="btn btn-primary" data-bs-dismiss="modal" onclick=" EventDetail()">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div for="Event Schedule">
                            <div class="mt-3">
                                <div class="flex-between">
                                    <b class="" style="font-size: 1.2em; color: #1a5854">Event Schedule</b>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventScheduleModal"> Add Event Schedule </button>
                                </div>
                                <!-- <div> -->
                                <table id="eventScheduleTable" class="table-together-false table-grey-banquet mt-3">
                                    <thead>
                                        <tr>
                                        <th style="width: 5%;">#</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                        <th>Function</th>
                                        <th>Setup</th>
                                        <th>AGR</th>
                                        <th>GTD</th>
                                        <th>SET</th>
                                        <th style="width:12%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="eventScheduleBody">
                                        @if (!empty($schedule))
                                            @foreach ( $schedule as $key => $item)
                                                @php
                                                    $var = $key + 1;
                                                @endphp
                                                <tr>
                                                    <td style="text-align:center;">{{$key + 1 }}</td>
                                                    <td style="text-align:center;" id="td_DateSchedule_{{$var}}">{{$item->date}},{{$item->first_time}} - {{$item->last_time}} น.</td>
                                                    <td style="text-align:center;" id="td_RoomSchedule_{{$var}}">{{$item->room}}</td>
                                                    <td style="text-align:center;" id="td_functionSchedule_{{$var}}">{{$item->function}}</td>
                                                    <td style="text-align:center;" id="td_setupSchedule_{{$var}}">{{$item->setup}}</td>
                                                    <td style="text-align:center;" id="td_agrSchedule_{{$var}}">{{$item->agr_schedule}}</td>
                                                    <td style="text-align:center;" id="td_gtdSchedule_{{$var}}">{{$item->gtd_schedule}}</td>
                                                    <td style="text-align:center;" id="td_setSchedule_{{$var}}">{{$item->set_schedule}}</td>
                                                    <td style="text-align:center;" class="bt-ed-dl">
                                                        <button type="button" class="ed" onclick="editRow(' + rowNumbemain + ')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addEventScheduleModal">
                                                            <img src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />
                                                            <input type="hidden" id="edit_id_{{$var}}" value="{{$var}}">
                                                        </button>
                                                        <button type="button" class="dl" onclick="removeRow({{$var}})">
                                                            <img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <!-- </div> -->
                            </div>
                            <div class="modal fade" id="addEventScheduleModal" tabindex="-1" aria-labelledby="addEventScheduleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEventScheduleModalLabel"> Add Event Schedule </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <div class="d-grid-2column">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Date</label>
                                                    <div class="input-group">
                                                        <input type="text"  id="DateSchedule" placeholder="DD/MM/YYYY" class="form-control readonly-input" readonly>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <!-- ไอคอนปฏิทิน -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="roomSelect" class="form-label">Room</label>
                                                    <select class="form-select"  id="RoomSchedule" name="RoomSchedule">
                                                        <option value="Meeting Room 1">Meeting Room 1</option>
                                                        <option value="Meeting Room 2">Meeting Room 2</option>
                                                        <option value="Meeting Room 3">Meeting Room 3</option>
                                                        <option value="Conference Hall">Conference Hall </option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Start Time</label>
                                                    <input type="time" class="form-control" id="StartSchedule" name="StartSchedule" step="60" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="form-label">End Time</label>
                                                    <input type="time" class="form-control"id="EndSchedule" step="60" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="function" class="form-label">Function</label>
                                                    <input type="text" class="form-control" id="functionSchedule"  placeholder="Enter function" list="functionOptions" />
                                                    <datalist id="functionOptions">
                                                        <option value="Meeting"></option>
                                                        <option value="Conference"></option>
                                                        <option value="Workshop"></option>
                                                        <option value="Seminar"></option>
                                                        <option value="Training"></option>
                                                    </datalist>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="setupSelect" class="form-label">Setup</label>
                                                    <select class="form-select"id="setupSchedule">
                                                        <option value="Theatre">Theatre</option>
                                                        <option value="Conference">Classroom</option>
                                                        <option value="U-Shape">U-Shape</option>
                                                        <option value="Conference">Conference</option>
                                                        <option value="Banquet">Banquet</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-grid-3column">
                                                <div>
                                                    <label for="agr" class="form-label">AGR
                                                        <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="AGR หมายถึง จำนวนแขกและเงื่อนไขงาน ที่ตกลงกัน"></i>
                                                    </label>
                                                    <input type="number" class="form-control" id="agrSchedule"placeholder="Enter AGR" />
                                                </div>
                                                <div>
                                                    <label for="gtd" class="form-label">GTD
                                                        <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="GTD หมายถึง จำนวนแขกขั้นต่ำที่ลูกค้ายืนยันเพื่อการเตรียมงาน"></i>
                                                    </label>
                                                    <input type="number" class="form-control" id="gtdSchedule" placeholder="Enter GTD" />
                                                </div>
                                                <div>
                                                    <label for="set" class="form-label">SET
                                                        <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="SET จำนวนการแขกที่จัดเตรียม"></i>
                                                    </label>
                                                    <input type="number" class="form-control" id="setSchedule"  placeholder="Enter SET" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                        <button type="button" class="btn btn-primary"  onclick=" EventSchedule()"> Save </button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div for="Assets and Equipment">
                            <div>
                                <div class="flex-between">
                                    <b class="mt-3" style="font-size: 1.2em; color: #1a5854">Assets and Equipment</b>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetsModal"> Add Assets and Equipment </button>
                                </div>
                                <!-- <div> -->
                                <table class="table-together-false table-grey-banquet mt-3" id="assetsTable">
                                    <thead>
                                        <tr>
                                        <th style="width: 5%;">#</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Remarks</th>
                                        <th>Price</th>
                                        <th style="width:12%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="AssetsEquipment">

                                    </tbody>
                                </table>
                                <!-- </div> -->
                            </div>
                            <div class="modal fade" id="addAssetsModal" tabindex="-1" aria-labelledby="addAssetsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addAssetsModalLabel"> Add Assets and Equipment </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-grid-2column">
                                                <div class="mb-3">
                                                    <label for="assetItem" class="form-label">Item</label>
                                                    <input type="text" class="form-control" id="assetItem" placeholder="Enter item name" />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label">Quantity</label>
                                                    <input type="number" class="form-control" id="quantity" placeholder="Enter quantity" />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="remarks" class="form-label">Remarks</label>
                                                    <input type="text" class="form-control" id="remarks" placeholder="Enter remarks" />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price</label>
                                                    <input type="number" class="form-control" id="price" placeholder="Enter price" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                            <button type="button" class="btn btn-primary"  onclick="AssetButton()"> Save </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div for="Food and Set Up" class="mt-5">
                            <div class="d-grid-2column-banquet " style="font-size: 0.9em;">
                                <!-- FOOD Section -->
                                <div class="section">
                                    <div class="section-title flex-between">
                                        <div class="flex-grow-1">Food</div>
                                        <button type="button"  class="center py-1 px-2" data-bs-toggle="modal" data-bs-target="#addFoodModal" style="background-color: transparent;border:1px solid rgb(244, 244, 244);border-radius: 5px;">
                                        <i class="fa fa-plus text-white" style="font-size:20px"></i>
                                        </button>
                                    </div>
                                    <table class="table table-bordered last-bdbt-none" id="foodTable">
                                        <tbody id="fooddetail">

                                        </tbody>
                                    </table>
                                </div>
                                <!-- SET UP Section -->
                                <div class="section">
                                    <div class="section-title flex-between">
                                        <div class="flex-grow-1">Set Up</div>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="center px-2 py-1 " id="addSetupButton" data-bs-toggle="modal" data-bs-target="#addSetupModal" style="background-color: transparent;border:1px solid rgb(244, 244, 244);border-radius: 5px;">
                                                <i class="fa fa-plus text-white" style="font-size:20px"></i>
                                            </button>
                                            <button type="button" class="center px-2 py-1" id="addSetupButton" data-bs-toggle="modal" data-bs-target="#selectSetupModal" style="background-color: transparent;border:1px solid rgb(244, 244, 244);border-radius: 5px;">
                                                <i class="fa fa-home text-white" style="font-size:20px"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <table id="setupTable" class="table-grey-banquet">
                                            <thead>
                                                <tr>
                                                <th>Event Time</th>
                                                <th>Room</th>
                                                <th>Details</th>
                                                <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="setupdetailtable">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal for Adding or Editing Set Up Details -->
                            <div class="modal fade" id="addSetupModal" tabindex="-1" aria-labelledby="addSetupModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addSetupModalLabel"> Add or Edit Set Up Details </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-grid-2column">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Event Date</label>
                                                    <div class="input-group">
                                                        <input type="text"  id="setupDate" placeholder="DD/MM/YYYY" class="form-control readonly-input" readonly>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <!-- ไอคอนปฏิทิน -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="setupRoom" class="form-label">Room</label>
                                                    <select class="form-control" id="setupRoom" required>
                                                        <option value="" disabled selected> Select a room </option>
                                                        <option value="Meeting Room 1">Meeting Room 1</option>
                                                        <option value="Meeting Room 2">Meeting Room 2</option>
                                                        <option value="Meeting Room 3">Meeting Room 3</option>
                                                        <option value="Dining Hall">Dining Hall</option>
                                                        <option value="Conference Hall">Conference Hall</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="startTime" class="form-label">Start Time</label>
                                                    <input type="time" class="form-control" id="startsetupTime" placeholder="Select start time" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="endTime" class="form-label">End Time</label>
                                                    <input type="time" class="form-control" id="endsetupTime" placeholder="Select end time" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="setupDetails" class="form-label">Details</label>
                                                    <textarea class="form-control" id="setupDetails" placeholder="Enter setup details" rows="3" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                        <button type="button" class="btn btn-primary" onclick="SetupButton()"> Save </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal for Adding Food Setup -->
                            <div class="modal fade" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title"> Add Food Details </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-grid-2column">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Event Date</label>
                                                <div class="input-group">
                                                    <input type="text"  id="FoodEvent" name="FoodEvent" placeholder="DD/MM/YYYY" class="form-control readonly-input" readonly>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <!-- ไอคอนปฏิทิน -->
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Room</label>
                                                <select class="form-control" id="RoomEvent" name="RoomEvent">
                                                <option value="" disabled selected> Select a room </option>
                                                <option value="Dining Hall">Dining Hall</option>
                                                <option value="Meeting Room 1">Meeting Room 1</option>
                                                <option value="Meeting Room 2">Meeting Room 2</option>
                                                <option value="Meeting Room 3">Meeting Room 3</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Start Time</label>
                                                <input type="time" id="StarttimeEvent" name="StarttimeEvent" class="form-control" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">End Time</label>
                                                <input type="time" id="EndtimeEvent" name="EndtimeEvent" class="form-control" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label text-danger">Special Request</label>
                                                <textarea class="form-control" id="SpecialEvent" name="SpecialEvent" rows="2" placeholder="e.g., Gluten allergy"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Guests</label>
                                                <input type="number" id="NumberEvent" class="form-control" name="NumberEvent" placeholder="Number of guests" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Food Type</label>
                                                <input type="text" class="form-control" id="FoodTypeEvent" name="FoodTypeEvent" placeholder="มื้ออาหาร Breakfast,lunch,..." />
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Food Details</label>
                                                <textarea class="form-control" rows="3" id="ListFoodEvent" name="ListFoodEvent" placeholder="List food details here"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Drink Details</label>
                                                <textarea class="form-control" rows="2" id="ListdrinkEvent" name="ListdrinkEvent" placeholder="List drink details here"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                    <button type="button" class="btn btn-primary" onclick="FoodButton()"> Save </button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="selectSetupModal" tabindex="-1" aria-labelledby="selectSetupModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="selectSetupModalLabel">Select Setup</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- Table to display setup options -->
                                    <table class="table table-striped" id="setupSelectionTable">
                                        <thead>
                                        <tr>
                                            <th>Event Time</th>
                                            <th>Room</th>
                                            <th>Details</th>
                                            <th>Select</th>
                                        </tr>
                                        </thead>
                                        <tbody id="selectsetup">

                                        </tbody>
                                    </table>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                    <button type="button" class="btn btn-primary" id="confirmSetup"> Confirm </button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-end mt-5">
                            <div class="">
                                <div>Customer Signature</div>
                                <p class="border-bottom" style="min-height: 40px"></p>
                                <p>Date:</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<input type="hidden" name="selectsteup" value="1" id="selectsteup">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
<script>
    $(document).ready(function() {
        var eventDate = $('#eventDate').val();
        if (!eventDate) {
            document.querySelector('#addEvent').style.display = "block";
        } else {
            document.querySelector('#addEvent').style.display = "none";
            document.querySelector('#editEvent').style.display = "block";
        }
    });
    $(function() {
        $('#eventDate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            autoApply: true,
            // minDate: moment().startOf('day'),
            locale: {
                format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
            }
        });

        // ดึงค่าจาก Date Picker แล้วแปลงเป็นฟอร์แมตใหม่
        $('#eventDate').on('apply.daterangepicker', function(ev, picker) {
            let formattedDate = picker.startDate.format('dddd, DD MMM YYYY'); // Wednesday, 22 Oct 2024
            $(this).val(formattedDate);

        });
        $('#DateSchedule').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            autoApply: true,
            minDate: moment().startOf('day'),
            locale: {
                format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
            }
        });

        // ดึงค่าจาก Date Picker แล้วแปลงเป็นฟอร์แมตใหม่
        $('#DateSchedule').on('apply.daterangepicker', function(ev, picker) {
            let formattedDate = picker.startDate.format('DD MMM YYYY'); // Wednesday, 22 Oct 2024
            $(this).val(formattedDate);

        });
        $('#FoodEvent').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            autoApply: true,
            minDate: moment().startOf('day'),
            locale: {
                format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
            }
        });

        // ดึงค่าจาก Date Picker แล้วแปลงเป็นฟอร์แมตใหม่
        $('#FoodEvent').on('apply.daterangepicker', function(ev, picker) {
            let formattedDate = picker.startDate.format('DD MMM YYYY'); // Wednesday, 22 Oct 2024
            $(this).val(formattedDate);

        });

        $('#setupDate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            autoApply: true,
            minDate: moment().startOf('day'),
            locale: {
                format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
            }
        });

        // ดึงค่าจาก Date Picker แล้วแปลงเป็นฟอร์แมตใหม่
        $('#setupDate').on('apply.daterangepicker', function(ev, picker) {
            let formattedDate = picker.startDate.format('DD MMM YYYY'); // Wednesday, 22 Oct 2024
            $(this).val(formattedDate);
        });
    });
    function EventDetail() {
        var eventDate = $('#eventDate').val();
        var cateringBy = $('#cateringBy').val();
        var teamCount = $('#team').val();
        var vehicleDetails = $('#vehicle').val();
        if (!eventDate) {
            document.querySelector('#addEvent').style.display = "block";
        } else {
            $('#Event_Date').text(eventDate);
            $('#Catering').text(cateringBy);
            $('#Number_of_teams').text(teamCount);
            $('#Details_of_the_vehicle').text(vehicleDetails);
            document.querySelector('#addEvent').style.display = "none";
            document.querySelector('#editEvent').style.display = "block";

            let eventData = {
                BEOID: $('#BEOID').val(),
                event_date: $('#AccountEventDetails #eventDate').val(),
                catering: $('#AccountEventDetails #cateringBy').val(),
                number: $('#AccountEventDetails #team').val(),
                vehicle: $('#AccountEventDetails #vehicle').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // สำหรับ Laravel CSRF
            };
            $.ajax({
                url: '/Banquet/Event/Order/save/event/details', // กำหนด route ที่จะบันทึกข้อมูล
                type: 'POST',
                data: eventData,
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "เกิดข้อผิดพลาดในการบันทึก",
                    });
                }
            });
        }
    }

    function formatTimeTo12Hour(time) {
        if (!time) return ''; // ถ้าไม่มีค่า ให้คืนค่าเป็นค่าว่าง
        var [hour, minute] = time.split(':'); // แยกชั่วโมงและนาที
        var period = hour >= 12 ? 'PM' : 'AM'; // กำหนด AM/PM
        hour = hour % 12 || 12; // แปลง 24 ชั่วโมงเป็น 12 ชั่วโมง
        return hour + ':' + minute + ' ' + period;
    }
    function EventSchedule() {
        var DateSchedule = $('#DateSchedule').val();
        var RoomSchedule = $('#RoomSchedule').val();
        var StartSchedule = $('#StartSchedule').val();
        var EndSchedule = $('#EndSchedule').val();
        var functionSchedule = $('#functionSchedule').val();
        var setupSchedule = $('#setupSchedule').val();
        var agrSchedule = $('#agrSchedule').val();
        var gtdSchedule = $('#gtdSchedule').val();
        var setSchedule = $('#setSchedule').val();
        var rowNumbemain = $('#eventScheduleBody tr').length + 1;
        var formattedStart = formatTimeTo12Hour(StartSchedule);
        var formattedEnd = formatTimeTo12Hour(EndSchedule);
        var edit_id = $('#edit_id_'+ rowNumbemain).val() ?? 0;
        var table = $('#table_value').val();


        if (table > 0) {
            rowNumbemain = table;
            console.log(setSchedule);
            $('#tr-Schedule' + rowNumbemain).html(
                '<input type="hidden" id="table_value" value="">'+
                '<td style="text-align:center;">' + rowNumbemain + '</td>' +
                '<td style="text-align:center;" id="td_DateSchedule_' + rowNumbemain + '"><input type="hidden" id="DateSchedule-' + rowNumbemain + '" name="DateSchedule_' + rowNumbemain + '" value="' + DateSchedule + '">' + DateSchedule + ', ' + formattedStart + ' - ' + formattedEnd + '</td>' +
                '<td style="text-align:center;" id="td_RoomSchedule_' + rowNumbemain + '"><input type="hidden" id="RoomSchedule-' + rowNumbemain + '" name="RoomSchedule_' + rowNumbemain + '" value="' + RoomSchedule + '">' + RoomSchedule + '</td>' +
                '<td style="text-align:center;" id="td_functionSchedule_' + rowNumbemain + '"><input type="hidden" id="functionSchedule-' + rowNumbemain + '" name="functionSchedule_' + rowNumbemain + '" value="' + functionSchedule + '">' + functionSchedule + '</td>' +
                '<td style="text-align:center;" id="td_setupSchedule_' + rowNumbemain + '"><input type="hidden" id="setupSchedule-' + rowNumbemain + '" name="setupSchedule_' + rowNumbemain + '" value="' + setupSchedule + '">' + setupSchedule + '</td>' +
                '<td style="text-align:center;" id="td_agrSchedule_' + rowNumbemain + '"><input type="hidden" id="agrSchedule-' + rowNumbemain + '" name="agrSchedule_' + rowNumbemain + '" value="' + agrSchedule + '">' + agrSchedule + '</td>' +
                '<td style="text-align:center;" id="td_gtdSchedule_' + rowNumbemain + '"><input type="hidden" id="gtdSchedule-' + rowNumbemain + '" name="gtdSchedule_' + rowNumbemain + '" value="' + gtdSchedule + '">' + gtdSchedule + '</td>' +
                '<td style="text-align:center;" id="td_setSchedule_' + rowNumbemain + '"><input type="hidden" id="setSchedule-' + rowNumbemain + '" name="setSchedule_' + rowNumbemain + '" value="' + setSchedule + '">' + setSchedule + '</td>' +
                '<input type="hidden" id="StartSchedule-' + rowNumbemain + '" name="StartSchedule_' + rowNumbemain + '" value="' + StartSchedule + '">' +
                '<input type="hidden" id="EndSchedule-' + rowNumbemain + '" name="EndSchedule_' + rowNumbemain + '" value="' + EndSchedule + '">' +
                '<td style="text-align:center;" class="bt-ed-dl">' +
                    '<button type="button" class="ed" onclick="editRow(' + rowNumbemain + ')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addEventScheduleModal">' +
                        '<img src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                        '<input type="hidden" id="edit_id_'+ rowNumbemain +'" value="'+ rowNumbemain +'">' +
                    '</button>' +
                    '<button type="button" class="dl" onclick="removeRow(' + rowNumbemain + ')">' +
                        '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                    '</button>' +
                '</td>'
            );
        }else{
            $('#eventScheduleBody').append(
                '<tr id="tr-Schedule' + rowNumbemain + '">' +
                    '<input type="hidden" id="table_value" value="">'+
                    '<td style="text-align:center;">' + rowNumbemain + '</td>' +
                    '<td style="text-align:center;" id="td_DateSchedule_' + rowNumbemain + '"><input type="hidden" id="DateSchedule-' + rowNumbemain + '" name="DateSchedule_' + rowNumbemain + '" value="' + DateSchedule + '">' + DateSchedule +','+ StartSchedule +' - '+ EndSchedule +'</td>' +
                    '<td style="text-align:center;"id="td_RoomSchedule_' + rowNumbemain + '"><input type="hidden" id="RoomSchedule-' + rowNumbemain + '" name="RoomSchedule_' + rowNumbemain + '" value="' + RoomSchedule + '">' + RoomSchedule +'</td>' +
                    '<td style="text-align:center;"id="td_functionSchedule_' + rowNumbemain + '"><input type="hidden" id="functionSchedule-' + rowNumbemain + '" name="functionSchedule_' + rowNumbemain + '" value="' + functionSchedule + '">' + functionSchedule +'</td>' +
                    '<td style="text-align:center;"id="td_setupSchedule_' + rowNumbemain + '"><input type="hidden" id="setupSchedule-' + rowNumbemain + '" name="setupSchedule_' + rowNumbemain + '" value="' + setupSchedule + '">' + setupSchedule +'</td>' +
                    '<td style="text-align:center;"id="td_agrSchedule_' + rowNumbemain + '"><input type="hidden" id="agrSchedule-' + rowNumbemain + '" name="agrSchedule_' + rowNumbemain + '" value="' + agrSchedule + '">' + agrSchedule +'</td>' +
                    '<td style="text-align:center;"id="td_gtdSchedule_' + rowNumbemain + '"><input type="hidden" id="gtdSchedule-' + rowNumbemain + '" name="gtdSchedule_' + rowNumbemain + '" value="' + gtdSchedule + '">' + gtdSchedule +'</td>' +
                    '<td style="text-align:center;"id="td_setSchedule_' + rowNumbemain + '"><input type="hidden" id="setSchedule-' + rowNumbemain + '" name="setSchedule_' + rowNumbemain + '" value="' + setSchedule + '">' + setSchedule +'</td>' +
                    '<input type="hidden" id="StartSchedule-' + rowNumbemain + '" name="StartSchedule_' + rowNumbemain + '" value="' + StartSchedule + '">' +
                    '<input type="hidden" id="EndSchedule-' + rowNumbemain + '" name="EndSchedule_' + rowNumbemain + '" value="' + EndSchedule + '">' +
                    '<td style="text-align:center;" class="bt-ed-dl">' +
                        '<button type="button" class="ed" onclick="editRow(' + rowNumbemain + ')" style="margin-right: 5px;"data-bs-toggle="modal" data-bs-target="#addEventScheduleModal">' +
                            '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" /><input type="hidden" id="edit_id_'+ rowNumbemain +'" value="'+rowNumbemain+'">' +
                        '</button>' +
                        '<button type="button" class="dl" onclick="removeRow(' + rowNumbemain + ')">' +
                            '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            );

        }

        $('#DateSchedule, #RoomSchedule, #StartSchedule, #EndSchedule, #functionSchedule, #setupSchedule, #agrSchedule, #gtdSchedule, #setSchedule,#table_value').val('');

        // ปิด modal (ต้องใช้ id หรือ class ของ modal ที่ต้องการปิด)
        $('#addEventScheduleModal').modal('hide'); // ใช้กับ Bootstrap Modal
    }
    function saveScheduleData() {
        let dataToSend = [];

        $('#eventScheduleBody tr').each(function () {
            let rowId = $(this).attr('id').replace('tr-Schedule', '');

            let rowData = {
                date: $('#DateSchedule-' + rowId).val(),
                room: $('#RoomSchedule-' + rowId).val(),
                function: $('#functionSchedule-' + rowId).val(),
                setup: $('#setupSchedule-' + rowId).val(),
                agr: $('#agrSchedule-' + rowId).val(),
                gtd: $('#gtdSchedule-' + rowId).val(),
                set: $('#setSchedule-' + rowId).val(),
                start: $('#StartSchedule-' + rowId).val(),
                end: $('#EndSchedule-' + rowId).val()
            };

            dataToSend.push(rowData);
        });

        // ส่งข้อมูลผ่าน AJAX
        $.ajax({
            url: "{{ url('/Mvat/check-edit-name') }}/" + id,
            type: 'GET',
            data: {
                _token: '{{ csrf_token() }}',
                schedules: dataToSend
            },
            success: function(response) {
                alert('บันทึกข้อมูลสำเร็จ');
                console.log(response);
            },
            error: function(xhr) {
                alert('เกิดข้อผิดพลาดในการบันทึก');
                console.error(xhr.responseText);
            }
        });
    }

    function editRow(rowNumber) {
        // ดึงค่าจากแถวที่เลือก
        var date = $('#DateSchedule-' + rowNumber).val();
        var room = $('#RoomSchedule-' + rowNumber).val();
        var start = $('#StartSchedule-' + rowNumber).val();
        var end = $('#EndSchedule-' + rowNumber).val();
        var functionSch = $('#functionSchedule-' + rowNumber).val();
        var setup = $('#setupSchedule-' + rowNumber).val();
        var agr = $('#agrSchedule-' + rowNumber).val();
        var gtd = $('#gtdSchedule-' + rowNumber).val();
        var set = $('#setSchedule-' + rowNumber).val();
        var table = $('#table_value').val();
        console.log(table);

        // แสดงค่าที่ดึงมาใน modal
        // ใส่ค่ากลับเข้าไปในฟอร์ม
        $('#table_value').val(rowNumber);
        $('#DateSchedule').val(date);
        $('#RoomSchedule').val(room);
        $('#StartSchedule').val(start);
        $('#EndSchedule').val(end);
        $('#functionSchedule').val(functionSch);
        $('#setupSchedule').val(setup);
        $('#agrSchedule').val(agr);
        $('#gtdSchedule').val(gtd);
        $('#setSchedule').val(set);

        // เปิด Modal เพื่อแก้ไขข้อมูล (ถ้ามี)
        console.log("Editing Row: " + rowNumber);
    }
    function removeRow(rowNumber) {
        $('#tr-Schedule' + rowNumber).remove();
    }
    function AssetButton(){
        var assetItem = $('#assetItem').val();
        var quantity = $('#quantity').val();
        var remarks = $('#remarks').val();
        var price = parseFloat($('#price').val()) || 0; // ตรวจสอบค่าก่อน
        var priceamount = price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        var table = $('#table_Asset').val();
        var rowNumbemain = $('#AssetsEquipment tr').length + 1;
        if (table > 0) {
            rowNumbemain = table;
            $('#tr-Assets' + rowNumbemain).html(
                '<input type="hidden" id="table_Asset" value="">'+
                '<td style="text-align:center;">' + rowNumbemain + '</td>' +
                '<td style="text-align:center;" id="td_assetItem_' + rowNumbemain + '"><input type="hidden" id="assetItem-' + rowNumbemain + '" name="assetItem_' + rowNumbemain + '" value="' + assetItem + '">' + assetItem +'</td>' +
                '<td style="text-align:center;"id="td_quantity_' + rowNumbemain + '"><input type="hidden" id="quantity-' + rowNumbemain + '" name="quantity_' + rowNumbemain + '" value="' + quantity + '">' + quantity +'</td>' +
                '<td style="text-align:center;"id="td_remarks_' + rowNumbemain + '"><input type="hidden" id="remarks-' + rowNumbemain + '" name="remarks_' + rowNumbemain + '" value="' + remarks + '">' + remarks +'</td>' +
                '<td style="text-align:center;"id="td_price_' + rowNumbemain + '"><input type="hidden" id="price-' + rowNumbemain + '" name="price_' + rowNumbemain + '" value="' + price + '">' + priceamount +'</td>' +
                '<td style="text-align:center;" class="bt-ed-dl">' +
                    '<button type="button" class="ed" onclick="editAssetRow(' + rowNumbemain + ')" style="margin-right: 5px;"data-bs-toggle="modal" data-bs-target="#addAssetsModal">' +
                        '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                    '</button>' +
                    '<button type="button" class="dl" onclick="removeAssetRow(' + rowNumbemain + ')">' +
                        '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                    '</button>' +
                '</td>'
            );
        }else{
            $('#AssetsEquipment').append(
                '<tr id="tr-Assets' + rowNumbemain + '">' +
                    '<input type="hidden" id="table_Asset" value="">'+
                    '<td style="text-align:center;">' + rowNumbemain + '</td>' +
                    '<td style="text-align:center;" id="td_assetItem_' + rowNumbemain + '"><input type="hidden" id="assetItem-' + rowNumbemain + '" name="assetItem_' + rowNumbemain + '" value="' + assetItem + '">' + assetItem +'</td>' +
                    '<td style="text-align:center;"id="td_quantity_' + rowNumbemain + '"><input type="hidden" id="quantity-' + rowNumbemain + '" name="quantity_' + rowNumbemain + '" value="' + quantity + '">' + quantity +'</td>' +
                    '<td style="text-align:center;"id="td_remarks_' + rowNumbemain + '"><input type="hidden" id="remarks-' + rowNumbemain + '" name="remarks_' + rowNumbemain + '" value="' + remarks + '">' + remarks +'</td>' +
                    '<td style="text-align:center;"id="td_price_' + rowNumbemain + '"><input type="hidden" id="price-' + rowNumbemain + '" name="price_' + rowNumbemain + '" value="' + price + '">' + priceamount +'</td>' +
                    '<td style="text-align:center;" class="bt-ed-dl">' +
                        '<button type="button" class="ed" onclick="editAssetRow(' + rowNumbemain + ')" style="margin-right: 5px;"data-bs-toggle="modal" data-bs-target="#addAssetsModal">' +
                            '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                        '</button>' +
                        '<button type="button" class="dl" onclick="removeAssetRow(' + rowNumbemain + ')">' +
                            '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            );
        }
        $('#assetItem, #quantity, #remarks, #price').val('');

        // ปิด modal (ต้องใช้ id หรือ class ของ modal ที่ต้องการปิด)
        $('#addAssetsModal').modal('hide'); // ใช้กับ Bootstrap Modal
    }
    function editAssetRow(rowNumber) {
        // ดึงค่าจากแถวที่เลือก
        var assetItem = $('#assetItem-' + rowNumber).val();
        var quantity = $('#quantity-' + rowNumber).val();
        var remarks = $('#remarks-' + rowNumber).val();
        var price = $('#price-' + rowNumber).val();
        var table = $('#table_Asset').val();
        $('#table_Asset').val(rowNumber);
        $('#assetItem').val(assetItem);
        $('#quantity').val(quantity);
        $('#remarks').val(remarks);
        $('#price').val(price);
    }
    function removeAssetRow(rowNumber) {
        $('#tr-Assets' + rowNumber).remove();
    }
    function FoodButton() {
        var FoodEvent = $('#FoodEvent').val().trim();
        var RoomEvent = $('#RoomEvent').val();
        var StarttimeEvent = $('#StarttimeEvent').val();
        var EndtimeEvent = $('#EndtimeEvent').val();
        var NumberEvent = $('#NumberEvent').val();
        var ListdrinkEvent = $('#ListdrinkEvent').val();
        var ListFoodEvent = $('#ListFoodEvent').val();
        var SpecialEvent = $('#SpecialEvent').val();
        var FoodTypeEvent = $('#FoodTypeEvent').val();
        // เช็คค่าก่อนเรียกฟังก์ชัน
        var formattedStart = StarttimeEvent ? formatTimeTo12Hour(StarttimeEvent) : '';
        var formattedEnd = EndtimeEvent ? formatTimeTo12Hour(EndtimeEvent) : '';

        var table = $('#table_food').val();
        var rowNumbemain = ($('#fooddetail tr').length) + 1; // แบ่ง 2 เพื่อให้นับเป็นคู่
        var datenumber = rowNumbemain; // ให้ทั้งสองค่าตรงกัน
        rowNumber = 0;
        if (table) {
            rowNumber = table.replace('_', '.');
        }

        if (rowNumber > 0) {
            datenumber =table;
            var editIcon = @json(asset('image/meetingRoom/edit.png'));
            var deleteIcon = @json(asset('image/meetingRoom/delete.png'));
            $('#tr-food' + datenumber).html(
                '<td>' +
                    '<div>' +
                        '<div class="flex-between">' +
                            '<p>' +
                                '<strong>Room : </strong> <span id="food-Room-' + datenumber + '">' + RoomEvent + '</span>' +
                                '<input type="hidden" id="foodinputRoom-' + datenumber + '" name="foodinputRoom_' + datenumber + '" value="' + RoomEvent + '">' +
                                '<input type="hidden" id="date-food-' + datenumber + '" name="date-food_' + datenumber + '" value="' + FoodEvent + '">' +
                                '<input type="hidden" id="startfoodTime-' + datenumber + '" name="startfoodTime_' + datenumber + '" value="' + StarttimeEvent + '">' +
                                '<input type="hidden" id="endfoodTime-' + datenumber + '" name="endfoodTime_' + datenumber + '" value="' + EndtimeEvent + '">' +
                            '</p>' +
                            '<div class="bt-ed-dl">' +
                                '<button type="button" class="ed" onclick="editfoodRow(\'' + datenumber + '\')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addFoodModal">' +
                                    '<img src="' + editIcon + '" alt="Edit" width="20" />' +
                                '</button>' +
                                '<button type="button" class="dl" onclick="removefoodRow(\'' + datenumber + '\')">' +
                                    '<img src="' + deleteIcon + '" alt="Delete" width="20" />' +
                                '</button>' +
                            '</div>' +
                        '</div>' +
                        '<p>' +
                            '<strong style="color: #c05624">Special Request :</strong><span id="food-Special-' + datenumber + '">' + SpecialEvent + '</span>' +
                            '<input type="hidden" id="foodinputSpecial-' + datenumber + '" name="foodinputSpecial_' + datenumber + '" value="' + SpecialEvent + '">' +
                        '</p>' +
                        '<p>' +
                            '<strong>Breakfast (Guests: <span id="food-Guest-' + datenumber + '">' + NumberEvent + '</span>):</strong>' +
                            '<input type="hidden" id="foodinputGuest-' + datenumber + '" name="foodinputGuest_' + datenumber + '" value="' + NumberEvent + '">' +
                        '</p>' +
                        '<ul>' +
                            '<li>' +
                                '<b>Food:</b>' +
                                '<span id="food-Food-' + datenumber + '">' + ListFoodEvent + '</span>' +
                                '<input type="hidden" id="foodinputFood-' + datenumber + '" name="foodinputFood_' + datenumber + '" value="' + ListFoodEvent + '">' +
                            '</li>' +
                            '<li>' +
                                '<b>Drink:</b>' +
                                '<span id="food-Drink-' + datenumber + '">' + ListdrinkEvent + '</span>' +
                                '<input type="hidden" id="foodinputDrink-' + datenumber + '" name="foodinputDrink_' + datenumber + '" value="' + ListdrinkEvent + '">' +
                            '</li>' +
                        '</ul>' +
                    '</div>' +
                '</td>'
            );
        }else{
            if (FoodEvent) {
                var dateFoodElements = $("[id^='date-food-']");
                var isDuplicate = false; // ตัวแปรเช็คค่าซ้ำ
                var number = ($('#fooddetail tr').length);
                if (dateFoodElements.length > 0) {
                    $("[id^='date-food-']").each(function () {
                        var value = $(this).val().trim(); // ดึงค่าจาก input และตัดช่องว่างออก
                        if (value == FoodEvent.trim()) {
                            isDuplicate = true; // ถ้าค่าตรงกันให้ตั้งค่าเป็นซ้ำ
                            var duplicateId = $(this).attr('id'); // ดึงค่า ID เช่น "date-setup-1"
                            duplicateNumber = duplicateId.match(/\d+$/); // ดึงตัวเลขท้ายสุด
                            if (duplicateNumber) {
                                duplicateNumber = duplicateNumber[0]; // แปลงเป็นตัวเลข
                            }
                            return false; // ออกจากการวนลูปเมื่อเจอค่าซ้ำ
                        }
                    });
                }
                if (!isDuplicate) {
                    $('#fooddetail').append(
                        '<tr class="date-header" id="tr-fooddetail' + datenumber + '">' +
                            '<td colspan="2" style="font-weight: bold; color: #1a5854">' +
                                '<input type="hidden" id="date-food-' + datenumber + '" name="date-food_' + datenumber + '" value="' + FoodEvent + '">' +
                                '<input type="hidden" id="startfoodTime-' + datenumber + '" name="startfoodTime_' + datenumber + '" value="' + StarttimeEvent + '">' +
                                '<input type="hidden" id="endfoodTime-' + datenumber + '" name="endfoodTime_' + datenumber + '" value="' + EndtimeEvent + '">' +
                                    FoodEvent +
                            '</td>' +
                        '</tr>' +
                        '<tr id="tr-food' + datenumber + '">' +
                            '<input type="hidden" id="table_food" value="">' +
                            '<td>' +
                                '<div>' +
                                    '<div class="flex-between">' + // ✅ ต้องใส่ + ครอบ string ให้ถูกต้อง
                                        '<p>' +
                                            '<strong>Room : </strong> <span id="food-Room-' + datenumber + '">'+RoomEvent+'</span>' +
                                            '<input type="hidden" id="foodinputRoom-' + datenumber + '" name="foodinputRoom_' + datenumber + '" value="' + RoomEvent + '">' +
                                        '</p>' +
                                        '<div class="bt-ed-dl">' +
                                            '<button type="button" class="ed" onclick="editfoodRow(' + datenumber + ')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addFoodModal">' +
                                                '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                                            '</button>' +
                                            '<button type="button" class="dl" onclick="removefoodRow(' + datenumber + ')">' +
                                                '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                                            '</button>' +
                                        '</div>' +
                                    '</div>' +
                                    '<p>'+
                                        '<strong style="color: #c05624">Special Request :</strong><span id="food-Special-' + datenumber + '">'+SpecialEvent+'</span>'+
                                        '<input type="hidden" id="foodinputSpecial-' + datenumber + '" name="foodinputSpecial_' + datenumber + '" value="' + SpecialEvent + '">' +
                                    '</p>'+
                                    '<p>'+
                                        '<strong>Breakfast (Guests: <span id="food-Guest-' + datenumber + '">'+NumberEvent+'</span>):</strong>'+
                                        '<input type="hidden" id="foodinputGuest-' + datenumber + '" name="foodinputGuest_' + datenumber + '"value="' + NumberEvent + '" >' +
                                    '</p>'+
                                    '<ul>'+
                                        '<li>'+
                                            '<b>Food:</b>'+
                                            '<span id="food-Food-' + datenumber + '">'+ListFoodEvent+'</span>'+
                                            '<input type="hidden" id="foodinputFood-' + datenumber + '" name="foodinputFood_' + datenumber + '" value="' + ListFoodEvent + '">' +
                                            '<input type="hidden" id="foodinputtype-' + datenumber + '" name="foodinputtype_' + datenumber + '" value="' + FoodTypeEvent + '">' +
                                        '</li>'+
                                        '<li>'+
                                            '<b>Drink:</b>'+
                                            '<span id="food-Drink-' + datenumber + '">'+ListdrinkEvent+'</span>'+
                                            '<input type="hidden" id="foodinputDrink-' + datenumber + '" name="foodinputDrink_' + datenumber + '" value="' + ListdrinkEvent + '">' +
                                        '</li>'+
                                    '</ul>'+
                                '</div>' +
                            '</td>' +
                        '</tr>'
                    );
                    number++;
                } else {
                    var editIcon = @json(asset('image/meetingRoom/edit.png'));
                    var deleteIcon = @json(asset('image/meetingRoom/delete.png'));
                    var datenumber = duplicateNumber + '_' + number;
                    $('#fooddetail').append(
                        '<tr id="tr-food' + datenumber + '">' +
                            '<input type="hidden" id="table_food" value="">' +
                            '<td>' +
                                '<div>' +
                                    '<div class="flex-between">' +
                                        '<p>' +
                                            '<strong>Room : </strong> <span id="food-Room-' + datenumber + '">' + RoomEvent + '</span>' +
                                            '<input type="hidden" id="foodinputRoom-' + datenumber + '" name="foodinputRoom_' + datenumber + '" value="' + RoomEvent + '">' +
                                            '<input type="hidden" id="date-food-' + datenumber + '" name="date-food_' + datenumber + '" value="' + FoodEvent + '">' +
                                            '<input type="hidden" id="startfoodTime-' + datenumber + '" name="startfoodTime_' + datenumber + '" value="' + StarttimeEvent + '">' +
                                            '<input type="hidden" id="endfoodTime-' + datenumber + '" name="endfoodTime_' + datenumber + '" value="' + EndtimeEvent + '">' +
                                        '</p>' +
                                        '<div class="bt-ed-dl">' +
                                            '<button type="button" class="ed" onclick="editfoodRow(\'' + datenumber + '\')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addFoodModal">' +
                                                '<img src="' + editIcon + '" alt="Edit" width="20" />' +
                                            '</button>' +
                                            '<button type="button" class="dl" onclick="removefoodRow(\'' + datenumber + '\')">' +
                                                '<img src="' + deleteIcon + '" alt="Delete" width="20" />' +
                                            '</button>' +
                                        '</div>' +
                                    '</div>' +
                                    '<p>' +
                                        '<strong style="color: #c05624">Special Request :</strong><span id="food-Special-' + datenumber + '">' + SpecialEvent + '</span>' +
                                        '<input type="hidden" id="foodinputSpecial-' + datenumber + '" name="foodinputSpecial_' + datenumber + '" value="' + SpecialEvent + '">' +
                                    '</p>' +
                                    '<p>' +
                                        '<strong>Breakfast (Guests: <span id="food-Guest-' + datenumber + '">' + NumberEvent + '</span>):</strong>' +
                                        '<input type="hidden" id="foodinputGuest-' + datenumber + '" name="foodinputGuest_' + datenumber + '" value="' + NumberEvent + '">' +
                                    '</p>' +
                                    '<ul>' +
                                        '<li>' +
                                            '<b>Food:</b>' +
                                            '<span id="food-Food-' + datenumber + '">' + ListFoodEvent + '</span>' +
                                            '<input type="hidden" id="foodinputFood-' + datenumber + '" name="foodinputFood_' + datenumber + '" value="' + ListFoodEvent + '">' +
                                        '</li>' +
                                        '<li>' +
                                            '<b>Drink:</b>' +
                                            '<span id="food-Drink-' + datenumber + '">' + ListdrinkEvent + '</span>' +
                                            '<input type="hidden" id="foodinputDrink-' + datenumber + '" name="foodinputDrink_' + datenumber + '" value="' + ListdrinkEvent + '">' +
                                        '</li>' +
                                    '</ul>' +
                                '</div>' +
                            '</td>' +
                        '</tr>'
                    );
                }
            }else{
                console.log("ไม่มีวันที่ให้ตรวจสอบ");
            }
        }
        $('#FoodEvent, #RoomEvent, #StarttimeEvent, #EndtimeEvent, #NumberEvent,#ListdrinkEvent, #ListFoodEvent, #SpecialEvent,#FoodTypeEvent,#table_food').val('');
        $('#addFoodModal').modal('hide');
    }
    function editfoodRow(datenumber){
        let rowNumber = Number(datenumber); // แปลงเป็นตัวเลขก่อน
        if (Number.isInteger(rowNumber)) { // ตรวจสอบว่าเป็นจำนวนเต็มหรือไม่
            var date = $('#date-food-' + rowNumber).val();
        } else {  // แทนที่ _ เป็น - โดยไม่ประกาศตัวแปรใหม่
            if (datenumber.includes('_')) { // ตรวจสอบว่าเป็นรูปแบบที่มี _
                rowNumber = datenumber.split('_')[0];  // แยกค่าและเลือกค่าแรกสุด
            }
            var date = $('#date-food-' + rowNumber).val();
        }
        var start = $('[id="startfoodTime-' + datenumber + '"]').val();
        var end = $('[id="endfoodTime-' + datenumber + '"]').val();
        var room = $('[id="foodinputRoom-' + datenumber + '"]').val();
        var special = $('[id="foodinputSpecial-' + datenumber + '"]').val();
        var guest = $('[id="foodinputGuest-' + datenumber + '"]').val();
        var food = $('[id="foodinputFood-' + datenumber + '"]').val();
        var drink = $('[id="foodinputDrink-' + datenumber + '"]').val();
        var type = $('[id="foodinputtype-' + datenumber + '"]').val();
        console.log(date);


        $('#FoodEvent').val(date).prop('disabled', true);
        $('#table_food').val(datenumber);
        $('#RoomEvent').val(room);
        $('#StarttimeEvent').val(start);
        $('#EndtimeEvent').val(end);
        $('#NumberEvent').val(guest);
        $('#ListdrinkEvent').val(drink);
        $('#ListFoodEvent').val(food);
        $('#SpecialEvent').val(special);
        $('#FoodTypeEvent').val(type);

    }
    function removefoodRow(datenumber) {
        datenumber = String(datenumber);
        console.log(rowNumber);

        if (datenumber.includes('_')) {
            $('#tr-food' + datenumber).remove();
        }else{
            let rowNumber = parseInt(datenumber);
            if ($('[id^="tr-food' + rowNumber + '_"]').length > 0) {
                $('#tr-food' + rowNumber).remove();
            }else {
                $('#tr-food' + rowNumber).remove();
                $('#tr-fooddetail' + rowNumber).remove();
            }
        }
    }
    function deleteFoodButton(){
        // เคลียร์ค่าของ input ทั้งหมด
        $('#FoodEvent').val('');
        $('#RoomEvent').val('');
        $('#StarttimeEvent').val('');
        $('#EndtimeEvent').val('');
        $('#NumberEvent').val('');
        $('#ListdrinkEvent').val('');
        $('#ListFoodEvent').val('');
        $('#SpecialEvent').val('');
        $('#FoodTypeEvent').val('');

        $('#food-date').text('');
        $('#food-Room').text('');
        $('#food-Food').text('');
        $('#food-Special').text('');
        $('#food_guest').text('');
        $('#food-Drink').text('');

        // ซ่อนปุ่มแก้ไข ถ้ามี
        $('#EditFood').hide();

        $('#deleteFood').hide();
    }
    function SetupButton() {
        var setupEventDate = $('#setupDate').val();
        var setupRoom = $('#setupRoom').val();
        var startsetupTime = $('#startsetupTime').val();
        var endsetupTime = $('#endsetupTime').val();
        var setupDetails = $('#setupDetails').val();
        var formattedStart = formatTimeTo12Hour(startsetupTime);
        var formattedEnd = formatTimeTo12Hour(endsetupTime);
        var table = $('#table_setuup').val();
        var rowNumbemain = ($('#setupdetailtable tr').length) + 1; // แบ่ง 2 เพื่อให้นับเป็นคู่
        var datenumber = rowNumbemain; // ให้ทั้งสองค่าตรงกัน
        rowNumber = 0;
        if (table) {
            rowNumber = table.replace('_', '.');
        }
        if (rowNumber > 0) {
            datenumber =table;
            $('#tr-setup' + datenumber).html(
                '<input type="hidden" id="setup-id-' + datenumber + '" name="setup-id_' + datenumber + '" value="' + datenumber + '">' +
                '<input type="hidden" id="date-setup-' + datenumber + '" name="date-setup_' + datenumber + '" value="' + setupEventDate + '">' +
                '<input type="hidden" id="table_setuup" value="">' +
                '<td style="text-align:center;"id="StartEnd-' + datenumber + '">' + formattedStart + ' - ' + formattedEnd + '</td>' +
                '<td style="text-align:center;"><input type="hidden" id="setupRoom-' + datenumber + '" name="setupRoom_' + datenumber + '" value="' + setupRoom + '">' + setupRoom + '</td>' +
                '<td style="text-align:center;"><input type="hidden" id="setupDetails-' + datenumber + '" name="setupDetails_' + datenumber + '" value="' + setupDetails + '">' + setupDetails + '</td>' +
                '<input type="hidden" id="startsetupTime-' + datenumber + '" name="startsetupTime_' + datenumber + '" value="' + startsetupTime + '">' +
                '<input type="hidden" id="endsetupTime-' + datenumber + '" name="endsetupTime_' + datenumber + '" value="' + endsetupTime + '">' +
                '<td style="text-align:center;" class="bt-ed-dl">' +
                    '<button type="button" class="ed" onclick="editsetupRow(\'' + datenumber + '\')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addSetupModal">' +
                        '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                    '</button>' +
                    '<button type="button" class="dl" onclick="removesetupRow(\'' + datenumber + '\')">' +
                        '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                    '</button>' +
                '</td>'
            );
            $('#tr-select' + datenumber).html(
                '<input type="hidden" id="setup-id-' + datenumber + '" name="setup-id_' + datenumber + '" value="' + datenumber + '">' +
                '<input type="hidden" id="date-setup-' + datenumber + '" name="date-setup_' + datenumber + '" value="' + setupEventDate + '">' +
                '<input type="hidden" id="table_setuup" value="">' +
                '<td style="text-align:left;" id="StartEnd-' + datenumber + '">'+ setupEventDate +' , '+formattedStart + ' - ' + formattedEnd + '</td>' +
                '<td style="text-align:left;"><input type="hidden" id="setupRoom-' + datenumber + '" name="setupRoom_' + datenumber + '" value="' + setupRoom + '">' + setupRoom + '</td>' +
                '<td style="text-align:left;"><input type="hidden" id="setupDetails-' + datenumber + '" name="setupDetails_' + datenumber + '" value="' + setupDetails + '">' + setupDetails + '</td>' +
                '<input type="hidden" id="startsetupTime-' + datenumber + '" name="startsetupTime_' + datenumber + '" value="' + startsetupTime + '">' +
                '<input type="hidden" id="endsetupTime-' + datenumber + '" name="endsetupTime_' + datenumber + '" value="' + endsetupTime + '">' +
                '<td style="text-align:center;">' +
                    '<button type="button" class="btn btn-primary" onclick="selectrow(' + datenumber + ')">' +
                        'Select'+
                    '</button>' +
                '</td>'
            );
        }
        else{
            if (setupEventDate) {
                var dateSetupElements = $("[id^='date-setup-']");
                var isDuplicate = false; // ตัวแปรเช็คค่าซ้ำ
                var number = ($('#setupdetailtable tr').length);
                if (dateSetupElements.length > 0) {
                    $("[id^='date-setup-']").each(function () {
                        var value = $(this).val().trim(); // ดึงค่าจาก input และตัดช่องว่างออก
                        if (value == setupEventDate.trim()) {
                            isDuplicate = true; // ถ้าค่าตรงกันให้ตั้งค่าเป็นซ้ำ
                            var duplicateId = $(this).attr('id'); // ดึงค่า ID เช่น "date-setup-1"
                            duplicateNumber = duplicateId.match(/\d+$/); // ดึงตัวเลขท้ายสุด
                            if (duplicateNumber) {
                                duplicateNumber = duplicateNumber[0]; // แปลงเป็นตัวเลข
                            }
                            return false; // ออกจากการวนลูปเมื่อเจอค่าซ้ำ
                        }
                    });
                }

                if (!isDuplicate) {

                    $('#setupdetailtable').append(
                        '<tr id="tr-setupdetail' + datenumber + '">' +
                            '<td colspan="4" class="date-setup text-start">' +
                                '<input type="hidden" id="date-setup-' + datenumber + '" name="date-setup_' + datenumber + '" value="' + setupEventDate + '">' +
                                setupEventDate +
                            '</td>' +
                        '</tr>' +
                        '<tr id="tr-setup' + datenumber + '">' +
                            '<input type="hidden" id="table_setuup" value="">' +
                            '<input type="hidden" id="setup-id-' + datenumber + '" name="setup-id_' + datenumber + '" value="' + datenumber + '">' +
                            '<td style="text-align:center;"id="StartEnd-' + datenumber + '">' + formattedStart + ' - ' + formattedEnd + '</td>' +
                            '<td style="text-align:center;"><input type="hidden" id="setupRoom-' + datenumber + '" name="setupRoom_' + datenumber + '" value="' + setupRoom + '">' + setupRoom + '</td>' +
                            '<td style="text-align:center;"><input type="hidden" id="setupDetails-' + datenumber + '" name="setupDetails_' + datenumber + '" value="' + setupDetails + '">' + setupDetails + '</td>' +
                            '<input type="hidden" id="startsetupTime-' + datenumber + '" name="startsetupTime_' + datenumber + '" value="' + startsetupTime + '">' +
                            '<input type="hidden" id="endsetupTime-' + datenumber + '" name="endsetupTime_' + datenumber + '" value="' + endsetupTime + '">' +
                            '<td style="text-align:center;" class="bt-ed-dl">' +
                                '<button type="button" class="ed" onclick="editsetupRow(' + datenumber + ')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addSetupModal">' +
                                    '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                                '</button>' +
                                '<button type="button" class="dl" onclick="removesetupRow(' + datenumber + ')">' +
                                    '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                                '</button>' +
                            '</td>' +
                        '</tr>'
                    );
                    number++;
                } else {

                    var datenumber = duplicateNumber + '_' + number;
                    $('#setupdetailtable').append(
                        '<tr id="tr-setup' + datenumber + '">' +
                            '<input type="hidden" id="setup-id-' + datenumber + '" name="setup-id_' + datenumber + '" value="' + datenumber + '">' +
                            '<input type="hidden" id="date-setup-' + datenumber + '" name="date-setup_' + datenumber + '" value="' + setupEventDate + '">' +
                            '<input type="hidden" id="table_setuup" value="">' +
                            '<td style="text-align:center;" id="StartEnd-' + datenumber + '">' + formattedStart + ' - ' + formattedEnd + '</td>' +
                            '<td style="text-align:center;"><input type="hidden" id="setupRoom-' + datenumber + '" name="setupRoom_' + datenumber + '" value="' + setupRoom + '">' + setupRoom + '</td>' +
                            '<td style="text-align:center;"><input type="hidden" id="setupDetails-' + datenumber + '" name="setupDetails_' + datenumber + '" value="' + setupDetails + '">' + setupDetails + '</td>' +
                            '<input type="hidden" id="startsetupTime-' + datenumber + '" name="startsetupTime_' + datenumber + '" value="' + startsetupTime + '">' +
                            '<input type="hidden" id="endsetupTime-' + datenumber + '" name="endsetupTime_' + datenumber + '" value="' + endsetupTime + '">' +
                            '<td style="text-align:center;" class="bt-ed-dl">' +
                                '<button type="button" class="ed" onclick="editsetupRow(\'' + datenumber + '\')" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addSetupModal">' +
                                    '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />' +
                                '</button>'+
                                '<button type="button" class="dl" onclick="removesetupRow(\'' + datenumber + '\')">' +
                                    '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                                '</button>' +
                            '</td>' +
                        '</tr>'
                    );
                }
                $('#selectsetup').append(
                    '<tr id="tr-select' + datenumber + '">' +
                        '<input type="hidden" id="table_setuup" value="">' +
                        '<input type="hidden" id="setup-id-' + datenumber + '" name="setup-id_' + datenumber + '" value="' + datenumber + '">' +
                        '<input type="hidden" id="date-setup-' + datenumber + '" name="date-setup_' + datenumber + '" value="' + setupEventDate + '">' +
                        '<td style="text-align:left;" id="StartEnd-' + datenumber + '">'+ setupEventDate +' , '+formattedStart + ' - ' + formattedEnd + '</td>' +
                        '<td style="text-align:left;"><input type="hidden" id="setupRoom-' + datenumber + '" name="setupRoom_' + datenumber + '" value="' + setupRoom + '">' + setupRoom + '</td>' +
                        '<td style="text-align:left;"><input type="hidden" id="setupDetails-' + datenumber + '" name="setupDetails_' + datenumber + '" value="' + setupDetails + '">' + setupDetails + '</td>' +
                        '<input type="hidden" id="startsetupTime-' + datenumber + '" name="startsetupTime_' + datenumber + '" value="' + startsetupTime + '">' +
                        '<input type="hidden" id="endsetupTime-' + datenumber + '" name="endsetupTime_' + datenumber + '" value="' + endsetupTime + '">' +
                        '<td style="text-align:center;">' +
                            '<button type="button" class="btn btn-primary" onclick="selectrow(\'' + datenumber + '\')">' +
                                'Select'+
                            '</button>' +
                        '</td>' +
                    '</tr>'
                );
            } else {
                console.log("ไม่มีวันที่ให้ตรวจสอบ");
            }
        }
        $('#setupDate, #setupRoom, #startsetupTime, #endsetupTime, #setupDetails,#table_setuup').val('');
        $('#addSetupModal').modal('hide');
    }

    function editsetupRow(datenumber) {
        // แปลงเป็นตัวเลขก่อน
        console.log(datenumber);
        let rowNumber = Number(datenumber);
        if (Number.isInteger(rowNumber)) { // ตรวจสอบว่าเป็นจำนวนเต็มหรือไม่
            var date = $('#date-setup-' + rowNumber).val();
        } else {  // แทนที่ _ เป็น - โดยไม่ประกาศตัวแปรใหม่
            if (datenumber.includes('_')) { // ตรวจสอบว่าเป็นรูปแบบที่มี _
                rowNumber = datenumber.split('_')[0];  // แยกค่าและเลือกค่าแรกสุด
            }
            var date = $('#date-setup-' + rowNumber).val();
        }

        var room = $('[id="setupRoom-' + datenumber + '"]').val();
        var start = $('[id="startsetupTime-' + datenumber + '"]').val();
        var end = $('[id="endsetupTime-' + datenumber + '"]').val();
        var Detail = $('[id="setupDetails-' + datenumber + '"]').val();
        var table = $('#table_setuup').val();

        $('#setupDate').prop('disabled', true);
        $('#setupDate').val(date);
        $('#table_setuup').val(datenumber);
        $('#setupDetails').val(Detail);
        $('#setupRoom').val(room);
        $('#startsetupTime').val(start);
        $('#endsetupTime').val(end);
    }
    function removesetupRow(datenumber) {
        datenumber = String(datenumber);
        if (datenumber.includes('_')) {
            $('#tr-setup' + datenumber).remove();
        }else{
            let rowNumber = parseInt(datenumber);
            if ($('[id^="tr-setup' + rowNumber + '_"]').length > 0) {
                console.log(1);

                $('#tr-setup' + datenumber).remove();
            }else {
                $('#tr-setup' + rowNumber).remove();
                $('#tr-setupdetail' + rowNumber).remove();
            }
        }
    }
    function selectrow(datenumber) {
        let rowNumber = Math.floor(Number(datenumber)); // แปลงเป็นตัวเลขและปัดเศษลง
        var date = $(`#date-setup-${rowNumber}`).val() || '';
        var start = $(`#startsetupTime-${rowNumber}`).val() || '';
        var end = $(`#endsetupTime-${rowNumber}`).val() || '';
        var room = $(`#setupRoom-${rowNumber}`).val() || '';
        var Detail = $(`#setupDetails-${rowNumber}`).val() || '';
        var setup = $(`#setup-id-${rowNumber}`).val() || '';

        let selectdetail = {
            date: date,
            startdate: start,
            enddate: end,
            room: room,
            Detail: Detail,
            setup:setup
        };
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "selectsteup";
        input.value = JSON.stringify(selectdetail); // แปลง object เป็น string ก่อนใส่ใน value

        // เพิ่ม input ลงในฟอร์ม
        var form = document.getElementById("myForm");
        form.appendChild(input);
        form.removeAttribute('target');
        form.submit();
        return selectdetail;

    }

</script>
@endsection
