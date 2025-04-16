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
                                        <strong id="BEO_ID">{{$BEOID}}</strong>
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
                                            <span id="Event_Date">{{$banquet->event_date ?? '-'}}</span>
                                        </p>
                                        <hr style="border: 1px solid #000000;" />
                                        <p>
                                            <strong>Sales By :</strong>
                                            <span>{{$user->name}}</span>
                                            <input type="hidden" id="sales" name="sales" value="{{$user->name}}">
                                        </p>
                                        <p>
                                            <strong>Catering By :</strong>
                                            <span id="Catering">{{$banquet->catering ?? '-'}}</span>
                                        </p>
                                        <hr style="border: 1px solid #000000;" />
                                        <div>
                                            จำนวนทีมงาน : <span id="Number_of_teams">{{$banquet->number ?? '-'}}</span> ท่าน
                                        </div>
                                        <div>
                                            รายละเอียดยานพาหนะ : <span id="Details_of_the_vehicle">{{$banquet->vehicle ?? '-'}}</span>
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
                                                                            $formattedDate = isset($banquet) && $banquet->event_date ? date('d/m/Y', strtotime($banquet->event_date)) : '';
                                                                        @endphp
                                                                        <input type="text" name="eventDate" id="eventDate" placeholder="DD/MM/YYYY"
                                                                        class="form-control readonly-input"
                                                                        value="{{ isset($banquet) && $banquet->event_date ? date('d/m/Y', strtotime($banquet->event_date)) : '' }}"
                                                                        readonly>
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
                                                                    <input type="text" class="form-control" id="cateringBy" name="catering" placeholder="Enter catering contact" value="{{$banquet->catering ?? null}}"/>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="teamCount" class="form-label">จำนวนทีมงาน</label>
                                                                    <input type="text" class="form-control" id="team" name="team" placeholder="Enter number of team members"  value="{{$banquet->number ?? null}}"/>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="vehicleDetails" class="form-label" >รายละเอียดยานพาหนะ</label>
                                                                    <textarea class="form-control" name="vehicle" id="vehicle" rows="2" placeholder="Enter vehicle details" >{{$banquet->vehicle ?? null}}</textarea>
                                                                </div>
                                                                <input type="hidden" class="form-control" id="proposal" name="proposal" value="{{$Quotation_ID}}"/>
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

                                                <tr id="tr-Schedule{{$item->row_id}}">
                                                    <td style="text-align:center;">{{$key + 1 }}</td>
                                                    <td style="text-align:center;" id="td_DateSchedule_{{$item->row_id}}">{{$item->date}},{{$item->first_time}} - {{$item->last_time}} น.</td>
                                                    <td style="text-align:center;" id="td_RoomSchedule_{{$item->row_id}}">{{$item->room}}</td>
                                                    <td style="text-align:center;" id="td_functionSchedule_{{$item->row_id}}">{{$item->function}}</td>
                                                    <td style="text-align:center;" id="td_setupSchedule_{{$item->row_id}}">{{$item->setup}}</td>
                                                    <td style="text-align:center;" id="td_agrSchedule_{{$item->row_id}}">{{$item->agr_schedule}}</td>
                                                    <td style="text-align:center;" id="td_gtdSchedule_{{$item->row_id}}">{{$item->gtd_schedule}}</td>
                                                    <td style="text-align:center;" id="td_setSchedule_{{$item->row_id}}">{{$item->set_schedule}}</td>
                                                    <td style="text-align:center;" class="bt-ed-dl">
                                                        <button type="button" class="ed" onclick="editScheduleRow({{$item->row_id}})" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addEventScheduleModal">
                                                            <img src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />
                                                            <input type="hidden" id="edit_id_{{$item->row_id}}" value="{{$item->row_id}}">
                                                        </button>
                                                        <button type="button" class="dl" onclick="removeScheduleRow({{$item->row_id}})">
                                                            <img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />
                                                        </button>
                                                    </td>
                                                    <input type="hidden" id="DateSchedule-{{$item->row_id}}" name="DateSchedule_{{$item->row_id}}" value="{{$item->date}}">
                                                    <input type="hidden" id="RoomSchedule-{{$item->row_id}}" name="RoomSchedule_{{$item->row_id}}" value="{{$item->room}}">
                                                    <input type="hidden" id="StartSchedule-{{$item->row_id}}" name="StartSchedule_{{$item->row_id}}" value="{{$item->first_time}}">
                                                    <input type="hidden" id="EndSchedule-{{$item->row_id}}" name="EndSchedule_{{$item->row_id}}" value="{{$item->last_time}} ">
                                                    <input type="hidden" id="functionSchedule-{{$item->row_id}}" name="functionSchedule_{{$item->row_id}}" value="{{$item->function}}">
                                                    <input type="hidden" id="setupSchedule-{{$item->row_id}}" name="setupSchedule_{{$item->row_id}}" value="{{$item->setup}}">
                                                    <input type="hidden" id="agrSchedule-{{$item->row_id}}" name="agrSchedule_{{$item->row_id}}" value="{{$item->agr_schedule}}">
                                                    <input type="hidden" id="gtdSchedule-{{$item->row_id}}" name="gtdSchedule_{{$item->row_id}}" value="{{$item->gtd_schedule}}">
                                                    <input type="hidden" id="setSchedule-{{$item->row_id}}" name="setSchedule_{{$item->row_id}}" value="{{$item->set_schedule}}">
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
                                                    <input type="time" class="form-control" id="StartScheduleTime"  step="60" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="form-label">End Time</label>
                                                    <input type="time" class="form-control" id="EndScheduleTime"  step="60" required />
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
                                                        <option value="Classroom">Classroom</option>
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
                                                    <input type="hidden" class="form-control" id="row"  value="0" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                        <button type="button" class="btn btn-primary" id="EventScheduleSave" style="display: block" onclick=" CreateSchedule()"> Save </button>
                                        <button type="button" class="btn btn-primary" id="EditeScheduleSave" style="display: none" onclick=" EditeSchedule()"> Save </button>
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
                                        @if (!empty($asset))
                                            @foreach ( $asset as $key => $item)
                                                <tr id="tr-Assets{{$item->row_id}}">
                                                    <td style="text-align:center;">{{$key + 1 }}</td>
                                                    <td style="text-align:center;" id="td_assetItem_{{$item->row_id}}">{{$item->item}}</td>
                                                    <td style="text-align:center;" id="td_quantity_{{$item->row_id}}">{{$item->quantity}}</td>
                                                    <td style="text-align:center;" id="td_remarks_{{$item->row_id}}">{{$item->remarks}}</td>
                                                    <td style="text-align:center;" id="td_price_{{$item->row_id}}"> {{ number_format($item->price, 2) }}</td>
                                                    <td style="text-align:center;" class="bt-ed-dl">
                                                        <button type="button" class="ed" onclick="editAssetRow({{$item->row_id}})" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#addAssetsModal">
                                                            <img src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" />
                                                        </button>
                                                        <button type="button" class="dl" onclick="removeAssetRow({{$item->row_id}})">
                                                            <img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />
                                                        </button>
                                                    </td>
                                                    <input type="hidden" id="assetItem-{{$item->row_id}}" name="assetItem_{{$item->row_id}}" value="{{$item->item}}">
                                                    <input type="hidden" id="quantity-{{$item->row_id}}" name="quantity_{{$item->row_id}}" value="{{$item->quantity}}">
                                                    <input type="hidden" id="remarks-{{$item->row_id}}" name="remarks_{{$item->row_id}}" value="{{$item->remarks}}">
                                                    <input type="hidden" id="price-{{$item->row_id}}" name="price_{{$item->row_id}}" value="{{$item->price}} ">
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
                                                    <input type="hidden" class="form-control" id="rowAsset"  value="0" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                                            <button type="button" class="btn btn-primary" id="CreateAssetSave" style="display: block" onclick=" CreateAsset()"> Save </button>
                                            <button type="button" class="btn btn-primary" id="EditeAssetSave" style="display: none" onclick=" EditeAsset()"> Save </button>
                                        </div>
                                    </div>
                                </div>
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
        var proposal = $('#proposal').val();
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
                proposal: $('#AccountEventDetails #proposal').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // สำหรับ Laravel CSRF
            };
            $.ajax({
                url: '/Banquet/Event/Order/save/event/details', // กำหนด route ที่จะบันทึกข้อมูล
                type: 'POST',
                data: eventData,
                success: function (response) {
                    var BEO_ID = response.BEOID;
                    $('#BEO_ID').text(BEO_ID);
                    $('#BEOID').val(BEO_ID);
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
                        $('#Event_Date').text('-');
                        $('#Catering').text('-');
                        $('#Number_of_teams').text('-');
                        $('#Details_of_the_vehicle').text('-');
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "เกิดข้อผิดพลาดในการบันทึก",
                    });
                    $('#Event_Date').text('-');
                    $('#Catering').text('-');
                    $('#Number_of_teams').text('-');
                    $('#Details_of_the_vehicle').text('-');
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

    //Schedule
    function CreateSchedule() {
        var DateSchedule = $('#DateSchedule').val();
        var RoomSchedule = $('#RoomSchedule').val();
        var StartSchedule = $('#StartScheduleTime').val();
        var EndSchedule = $('#EndScheduleTime').val();
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
        $('#eventScheduleBody').append(
            '<tr id="tr-Schedule' + rowNumbemain + '">' +
                '<input type="hidden" id="table_value" value=""><input type="hidden" id="row_id-' + rowNumbemain + '" value=""' + rowNumbemain + '"">'+
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
                    '<button type="button" class="ed" onclick="editScheduleRow(' + rowNumbemain + ')" style="margin-right: 5px;"data-bs-toggle="modal" data-bs-target="#addEventScheduleModal">' +
                        '<img class="" src="{{ asset('image/meetingRoom/edit.png') }}" alt="" width="20" /><input type="hidden" id="edit_id_'+ rowNumbemain +'" value="'+rowNumbemain+'">' +
                    '</button>' +
                    '<button type="button" class="dl" onclick="removeScheduleRow(' + rowNumbemain + ')">' +
                        '<img src="{{ asset('image/meetingRoom/delete.png') }}" alt="" width="20" />' +
                    '</button>' +
                '</td>' +
            '</tr>'
        );
        let dataToSend = {
            row: rowNumbemain,
            BEOID: $('#BEOID').val(),
            date: $('#DateSchedule-' + rowNumbemain).val(),
            room: $('#RoomSchedule-' + rowNumbemain).val(),
            function: $('#functionSchedule-' + rowNumbemain).val(),
            setup: $('#setupSchedule-' + rowNumbemain).val(),
            agr: $('#agrSchedule-' + rowNumbemain).val(),
            gtd: $('#gtdSchedule-' + rowNumbemain).val(),
            set: $('#setSchedule-' + rowNumbemain).val(),
            start: $('#StartSchedule-' + rowNumbemain).val(),
            end: $('#EndSchedule-' + rowNumbemain).val()
        };
        $.ajax({
            url: '/Banquet/Event/Order/save/schedule/details',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { data: dataToSend }, // ส่งแบบเป็น object
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
                    $('#tr-Schedule' + rowNumbemain).remove();
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "เกิดข้อผิดพลาดในการบันทึก",
                });
                $('#tr-Schedule' + rowNumbemain).remove();
            }
        });
        $('#DateSchedule, #RoomSchedule, #StartSchedule, #EndSchedule, #functionSchedule, #setupSchedule, #agrSchedule, #gtdSchedule, #setSchedule,#table_value').val('');

        // ปิด modal (ต้องใช้ id หรือ class ของ modal ที่ต้องการปิด)
        $('#addEventScheduleModal').modal('hide'); // ใช้กับ Bootstrap Modal
    }
    function editScheduleRow(rowNumber) {
        // ดึงค่าจากแถวที่เลือก
        var row = $('#row_id-' + rowNumber).val();
        var date = $('#DateSchedule-' + rowNumber).val();
        var room = $('#RoomSchedule-' + rowNumber).val();
        var start = $('#StartSchedule-' + rowNumber).val();
        var end = $('#EndSchedule-' + rowNumber).val();
        var functionSch = $('#functionSchedule-' + rowNumber).val();
        var setup = $('#setupSchedule-' + rowNumber).val();
        console.log(setup);

        var agr = $('#agrSchedule-' + rowNumber).val();
        var gtd = $('#gtdSchedule-' + rowNumber).val();
        var set = $('#setSchedule-' + rowNumber).val();
        var table = $('#table_value').val();
        if (end) {
            end = end.substring(0, 5); // เอาเฉพาะ HH:MM
            $('#EndScheduleTime').val(end);
        }
        $('#table_value').val(rowNumber);
        $('#DateSchedule').val(date);
        $('#RoomSchedule').val(room);
        $('#StartScheduleTime').val(start);
        $('#functionSchedule').val(functionSch);
        $('#setupSchedule').val(setup);
        $('#agrSchedule').val(agr);
        $('#gtdSchedule').val(gtd);
        $('#setSchedule').val(set);
        $('#row').val(rowNumber);
        $('#addEventScheduleModal').modal('show');
        document.querySelector('#EditeScheduleSave').style.display = "block";
        document.querySelector('#EventScheduleSave').style.display = "none";
    }
    function EditeSchedule () {
        console.log(1);

        var row = $('#row').val();
        var DateSchedule = $('#DateSchedule').val();
        var RoomSchedule = $('#RoomSchedule').val();
        var StartSchedule = $('#StartScheduleTime').val();
        var EndSchedule = $('#EndScheduleTime').val();
        var functionSchedule = $('#functionSchedule').val();
        var setupSchedule = $('#setupSchedule').val();
        var agrSchedule = $('#agrSchedule').val();
        var gtdSchedule = $('#gtdSchedule').val();
        var setSchedule = $('#setSchedule').val();

        let dataToSend = {
            row: row,
            BEOID: $('#BEOID').val(),
            date: DateSchedule,
            room: RoomSchedule,
            function: functionSchedule,
            setup: setupSchedule,
            agr: agrSchedule,
            gtd: gtdSchedule,
            set: setSchedule,
            start: StartSchedule,
            end: EndSchedule
        };
        console.log(dataToSend);

        $.ajax({
            url: '/Banquet/Event/Order/save/schedule/details',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { data: dataToSend }, // ส่งแบบเป็น object
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Good job!",
                        text: response.message,
                        icon: "success"
                    });
                    rowNumbemain = row;
                    $('#tr-Schedule' + rowNumbemain).html(
                        '<input type="hidden" id="table_value" value="">'+
                        '<td style="text-align:center;">' + rowNumbemain + '</td>' +
                        '<td style="text-align:center;" id="td_DateSchedule_' + rowNumbemain + '"><input type="hidden" id="DateSchedule-' + rowNumbemain + '" name="DateSchedule_' + rowNumbemain + '" value="' + DateSchedule + '">' + DateSchedule + ', ' + StartSchedule + ' - ' + EndSchedule + '</td>' +
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
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "เกิดข้อผิดพลาดในการบันทึก",
                });
            }
        });
        $('#DateSchedule, #RoomSchedule, #StartSchedule, #EndSchedule, #functionSchedule, #setupSchedule, #agrSchedule, #gtdSchedule, #setSchedule').val('');

        // ปิด modal (ต้องใช้ id หรือ class ของ modal ที่ต้องการปิด)
        $('#addEventScheduleModal').modal('hide');

    }
    function removeScheduleRow(rowNumber) {
        let dataToSend = {
            row: rowNumber,
            BEOID: $('#BEOID').val(),
        };
        $.ajax({
            url: '/Banquet/Event/Order/delete/schedule/details',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { data: dataToSend  }, // ส่งแบบเป็น object
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Good job!",
                        text: response.message,
                        icon: "success"
                    });
                    $('#tr-Schedule' + rowNumber).remove();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });

                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "เกิดข้อผิดพลาดในการบันทึก",
                });

            }
        });

    }
    //Asset
    function CreateAsset() {
        var assetItem = $('#assetItem').val();
        var quantity = $('#quantity').val();
        var remarks = $('#remarks').val();
        var price = parseFloat($('#price').val()) || 0; // ตรวจสอบค่าก่อน
        var priceamount = price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        var table = $('#table_Asset').val();
        var row = $('#rowAsset').val();
        var rowNumbemain = $('#AssetsEquipment tr').length + 1;
        $('#AssetsEquipment').append(
            '<tr id="tr-Assets' + rowNumbemain + '">' +
                '<input type="hidden" id="table_Asset" value=""><input type="hidden" id="row_id-' + rowNumbemain + '" value=""' + rowNumbemain + '"">'+
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
        let dataToSend = {
            row: rowNumbemain,
            BEOID: $('#BEOID').val(),
            assetItem: $('#assetItem-' + rowNumbemain).val(),
            quantity: $('#quantity-' + rowNumbemain).val(),
            remarks: $('#remarks-' + rowNumbemain).val(),
            price: $('#price-' + rowNumbemain).val()
        };
        $.ajax({
            url: '/Banquet/Event/Order/save/Asset/details',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { data: dataToSend }, // ส่งแบบเป็น object
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
                    $('#tr-Assets' + rowNumbemain).remove();
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "เกิดข้อผิดพลาดในการบันทึก",
                });
                $('#tr-Assets' + rowNumbemain).remove();
            }
        });
        $('#assetItem, #quantity, #remarks, #price').val('');
        // ปิด modal (ต้องใช้ id หรือ class ของ modal ที่ต้องการปิด)
        $('#addAssetsModal').modal('hide'); // ใช้กับ Bootstrap Modal
    }
    function editAssetRow(rowNumber) {
        var row = $('#row_id-' + rowNumber).val();
        var assetItem = $('#assetItem-' + rowNumber).val();
        var quantity = $('#quantity-' + rowNumber).val();
        var remarks = $('#remarks-' + rowNumber).val();
        var price = parseFloat($('#price-' + rowNumber).val()) || 0; // ตรวจสอบค่าก่อน
        var table = $('#table_Asset').val();
        $('#table_Asset').val(rowNumber);
        $('#assetItem').val(assetItem);
        $('#quantity').val(quantity);
        $('#remarks').val(remarks);
        $('#rowAsset').val(rowNumber);
        $('#price').val(price);

        document.querySelector('#EditeAssetSave').style.display = "block";
        document.querySelector('#CreateAssetSave').style.display = "none";
    }
    function EditeAsset(){
        var assetItem = $('#assetItem').val();
        var quantity = $('#quantity').val();
        var remarks = $('#remarks').val();
        var price = parseFloat($('#price').val()) || 0; // ตรวจสอบค่าก่อน
        var row = $('#rowAsset').val();
        var priceamount = price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        let dataToSend = {
            row: row,
            BEOID: $('#BEOID').val(),
            assetItem: assetItem,
            quantity: quantity,
            remarks: remarks,
            price: price
        };
        $.ajax({
            url: '/Banquet/Event/Order/save/Asset/details',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { data: dataToSend }, // ส่งแบบเป็น object
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Good job!",
                        text: response.message,
                        icon: "success"
                    });
                    rowNumbemain = row;
                    console.log(rowNumbemain);
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
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "เกิดข้อผิดพลาดในการบันทึก",
                });
            }
        });
        $('#assetItem, #quantity, #remarks, #price').val('');
        // ปิด modal (ต้องใช้ id หรือ class ของ modal ที่ต้องการปิด)
        $('#addAssetsModal').modal('hide'); // ใช้กับ Bootstrap Modal
    }
    function removeAssetRow(rowNumber){
        let dataToSend = {
            row: rowNumber,
            BEOID: $('#BEOID').val(),
        };
        $.ajax({
            url: '/Banquet/Event/Order/delete/Asset/details',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { data: dataToSend  }, // ส่งแบบเป็น object
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Good job!",
                        text: response.message,
                        icon: "success"
                    });
                    $('#tr-Assets' + rowNumber).remove();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });

                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "เกิดข้อผิดพลาดในการบันทึก",
                });

            }
        });
    }
</script>
@endsection
