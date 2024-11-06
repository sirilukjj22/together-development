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
.table-revenue-detail {
    display: none;
    margin: 1rem 0;
    padding: 1rem;
    background-color: aliceblue;
    border-radius: 7px;
    color: white;
    min-height: 20rem;
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Billing Folio.</small>
                    <div class=""><span class="span1">Billing Folio (ใบเรียกเก็บเงิน)</span></div>
                </div>
                <div class="col-auto">
                    <button class="bt-tg mr-2" style="position: relative" data-toggle="modal" data-target="#modalAddBill">
                        <span >Issue Bill</span>
                    </button>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-sm-12 col-12 pi">
                    <div class="">
                        <div class="card-body">
                            <section class="card-container bg-card-container">
                                <section class="card2 gradient-bg">
                                    <div class="card-content bg-card-content-white" class="card-content">
                                        <h5 class="card-title center">Client Details</h5>
                                        <ul class="card-list-withColon">
                                            <li>
                                            <span>Guest Name</span>
                                            @if ($type == 'Comapny')
                                                <span> - </span>
                                            @else
                                                <span>{{$name}}</span>
                                            @endif
                                            </li>
                                            <li>
                                            <span>Company</span>
                                            @if ($type == 'Comapny')
                                                <span>{{$name}}</span>
                                            @else
                                                <span> - </span>
                                            @endif
                                            </li>
                                            <li>
                                                <span>Tax ID/Gst Pass</span>
                                                <span>{{$Identification}}</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span>{{$address}}</span>
                                            </li>
                                            <li>
                                                <span>Check In Date</span>
                                                <span>{{$Additional->checkin ?? 'No Check In Date'}}</span>
                                            </li>
                                            <li>
                                                <span>Check Out Date</span>
                                                <span>{{$Additional->checkout ?? '-'}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                                <section class="card2 card-circle">
                                    <div class="tech-circle-container mx-4" style="background-color: #135d58;">
                                        <div class="outer-glow-circle"></div>
                                        <div class="circle-content">
                                            <p class="circle-text">
                                            <p class="f-w-bold fs-3">{{ number_format($total, 2, '.', ',') }}</p>
                                            <span class="subtext fs-6" >Total Amount</span>
                                            </p>
                                        </div>
                                        <div class="outer-ring">
                                            <div class="rotating-dot"></div>
                                        </div>
                                    </div>
                                </section>
                            <section class="card2 gradient-bg">
                            <div class="card-content3 bg-card-content-white">
                                <h5 class="card-title center" >Folio</h5>
                                <ul class="card-list-between">
                                    <li class="pb-1 px-2 justify-content-center gap-2 fs-5" >
                                        <span>AD N0. </span>
                                        <span class="hover-effect f-w-bold text-primary">({{$Additional->Additional_ID}}) </i>
                                        </span>
                                    </li>
                                    <li class="px-2">
                                        <span>Price Before Tax</span>
                                            <span class="hover-effect f-w-bold text-primary"> {{ number_format($total/1.07, 2, '.', ',') }} </i>
                                        </span>
                                    </li>
                                    <li class="px-2">
                                        <span>Value Added Tax</span>
                                        <span class="hover-effect f-w-bold text-primary"> {{ number_format($total - ($total/1.07), 2, '.', ',') }} </i></span>
                                    </li>
                                </ul>
                                <li class="outstanding-amount">
                                    <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                    <span class="text-success f-w-bold"> {{ number_format($total, 2, '.', ',') }}</span>
                                </li>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
                        <input type="hidden" class="form-control" id="AdditionalID" value="{{$Additional->Additional_ID}}" />
                        <!-- Modal ออกบิลปกติ-->
                        <div class="modal fade bd-example-modal-lg" id="modalAddBill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content rounded-lg">
                                <div class="modal-header modal-h" style="border-radius: 0;">
                                    <h3 class="modal-title text-white">Issue Bill</h3>
                                    <span type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </span>
                                </div>
                                <form id="myForm" action="{{route('BillingFolioOver.savere')}} " method="POST">
                                    @csrf
                                    <input type="hidden" id="Additional" name="Additional" class="form-control" value="{{$Additional->Additional_ID}}">
                                    <div class="modal-body">
                                        <div class="col-lg-12 flex-end" style="display: grid; gap:1px" >
                                            <b >Receipt ID : {{$REID}}</b>
                                            <b >Additional ID : {{$Additional->Additional_ID}}</b>
                                        </div>
                                        <h3>
                                            <span>Customer Details</span>
                                        </h3>
                                        <div >
                                            <label for="" class="star-red">Guest Name</label>
                                            <select name="Guest" id="Guest" class="select2" onchange="data()" required>
                                                <option value="{{$name_ID}}">{{$name}}</option>
                                                @foreach($datasub as $item)
                                                    @if ($type == 'Company')
                                                        <option value="{{ $item->ComTax_ID }}">{{ 'บริษัท '.$item->Companny_name.' จำกัด' ?? 'คุณ '.$item->first_name.' '.$item->last_name }}</option>
                                                    @else
                                                        <option value="{{ $item->GuestTax_ID }}">{{ $item->Company_name ?? $item->first_name.' '.$item->last_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div >
                                            <label class="star-red" for="reservationNo">Reservation No </label>
                                            <input type="text" class="form-control" name="reservationNo" id="reservationNo" required />
                                        </div>
                                        <div >
                                            <label for="company">Company</label>
                                            @if ($type == 'Company')
                                                <input type="text" class="form-control " id="company" name="company" value="{{$name}}" disabled  style="background-color: #59a89e81;"/>
                                            @else
                                                <input type="text" class="form-control " id="company" disabled  style="background-color: #59a89e81;"/>
                                            @endif
                                        </div>
                                        <div >
                                            <label for="taxID">Tax ID/Gst Pass</label>
                                            <input type="text" id="taxID" value="auto-select" class="form-control" disabled  style="background-color: #59a89e81;"/>
                                        </div>
                                        <div >
                                            <label for="address">Address</label>
                                            <input type="text" id="address" value="auto-select" class="form-control" disabled  style="background-color: #59a89e81;"/>
                                            <input type="text" id="address2" value="auto-select" class="form-control mt-3" disabled  style="background-color: #59a89e81;"/>
                                        </div>
                                        <h3>
                                            <span>Stay Details</span>
                                        </h3>
                                        <div >
                                            <label class="star-red" for="roomNo">Room No.</label>
                                            <input type="text" id="roomNo" name="roomNo" class="form-control" required />
                                        </div>
                                        <div >
                                            <label class="star-red" for="numberOfGuests">Number of Guests</label>
                                            <input type="text" id="numberOfGuests" name="numberOfGuests" class="form-control" required />
                                        </div>
                                        <div >
                                            <label for="arrival">Arrival</label>
                                            <div class="input-group">
                                                <input type="text" name="arrival" id="arrival" placeholder="DD/MM/YYYY" class="form-control" required>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                        <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div >
                                            <label for="departure">Departure</label>
                                            <div class="input-group">
                                                <input type="text" name="departure" id="departure" placeholder="DD/MM/YYYY" class="form-control" required>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                        <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <h3>
                                            <span>Payment Details</span>
                                        </h3>
                                        <div class="cashInput" >
                                            <label for="cashAmount" class="star-red">Cash Amount</label>
                                            <input type="text" id="Amount" value="{{ number_format($sumpayment, 2) }}" name="Amount" class="cashAmount form-control" placeholder="Enter cash amount" disabled style="background-color: #59a89e81;">
                                        </div>
                                        <div class="d-grid" style="height: max-content;">
                                            <label class="star-red" for="paymentDate">Date</label>
                                            <div class="input-group">
                                                <input type="text" name="paymentDate" id="paymentDate" placeholder="DD/MM/YYYY" class="form-control" required>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                        <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="note">Note</label>
                                                <textarea id="note" name="note" style="height: 1px" placeholder="Enter details" class="form-control"></textarea>
                                            </div>
                                            <div>
                                                <label for="reference">Reference</label>
                                                <input readonly type="text" id="reference" class="form-control" style="background-color: #59a89e81;"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="bt-tg bt-grey sm" data-dismiss="modal"> Close </button>
                                    <button type="button"  class="bt-tg sm" data-dismiss="modal" onclick="Preview()">Preview</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <section id="billDetailsEditBill" style="border: 1px solid rgba(23, 27, 36, 0.633); padding: 2rem; margin: 2rem 0;" class="wrap-bill">
                                <div class="wrap-all-company-detail">
                                    <header>
                                        <section class="wrap-company-detail">
                                        <div class="company-detail">
                                            <ul>
                                            <h1>{{$settingCompany->name_th}}</h1>
                                            <li class="font-w-600">{{$settingCompany->name}}</li>
                                            <li class="left-4px font-w-600"> *** Head Office / Headquarters </li>
                                            <li>{{$settingCompany->address}}</li>
                                            <li> Tel : {{$settingCompany->tel}} | @if ($settingCompany->fax) Fax : {{$settingCompany->fax}} @endif </li>
                                            <li>HOTEL TAX ID {{$settingCompany->Hotal_ID}}</li>
                                            <li class="w-spaceWrap-less860px"> <span> website: {{$settingCompany->web}} |</span><span> Email: {{$settingCompany->email}} </span> </li>
                                            </ul>
                                        </div>

                                        <div class="img">
                                            <img src="{{ asset('assets/images/' . $settingCompany->image) }}" alt="together-resort" width="200px" />
                                        </div>
                                        </section>
                                        <section>
                                        <h3 class="center font-upper">Receipt / tax invoice</h3>
                                        <div class="receipt-cutomer-detail">
                                            <ul>
                                            <h4 class="font-upper"> Tax invoice {{$REID}}</h4>
                                            <li>
                                                <span>Guest name</span>
                                                <span id="displayGuestNameEditBill">คุณพัชรี</span>
                                            </li>
                                            <li>
                                                <span>Reservation No</span>
                                                <span id="displayReservationNoEditBill">6576</span>
                                            </li>
                                            <li>
                                                <span>Company</span>
                                                <span id="displayCompanyEditBill">Together Resort </span>
                                            </li>
                                            <li>
                                                <span>Tax ID/Gst Pass</span>
                                                <span id="displayTaxIDEditBill">0764559000169</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span id="displayAddressEditBill" >168 Moo 2 Kaengkrachan phetchaburi 76170</span>
                                            </li>
                                            </ul>
                                            <ul>
                                            <li>
                                                <span>Page #</span>
                                                <span>1 /1 </span>
                                            </li>
                                            <li>
                                                <span>Room No.</span>
                                                <span id="displayRoomNoEditBill">9643</span>
                                            </li>
                                            <li>
                                                <span>Arrival</span>
                                                <span id="displayArrivalEditBill">01/06/2024</span>
                                            </li>
                                            <li>
                                                <span>Departure</span>
                                                <span id="displayDepartureEditBill">02/06/2024</span>
                                            </li>
                                            <li>
                                                <span>No of Guest</span>
                                                <span id="displayNumberOfGuestsEditBill">3</span>
                                            </li>
                                            <li>
                                                <span>Printed Date</span>
                                                <span  id="date">02/06/2024</span>
                                            </li>
                                            <li>
                                                <span>Printed time</span>
                                                <span  id="dateM">13:26:24 PM</span>
                                            </li>
                                            <li>
                                                <span>Tax invoice Date</span>
                                                <span  id="Invoicedate">02/06/2024</span>
                                            </li>
                                            </ul>
                                        </div>
                                        </section>
                                    </header>
                                    <section class="receipt-cutomer-detail-body">
                                        <div>
                                        <div style="overflow: auto;">
                                            <table id="table-revenueEditBill">
                                            <thead>
                                                <tr>
                                                <th>Date</th>
                                                <th >Description </th>
                                                <th>Reference</th>
                                                <th>amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                <td id="displayPaymentDateEditBill">10/04/2024</td>
                                                <td >
                                                    <span id="displayDescriptionEditBill" > SCB Bank Transfer - Together Resort Ltd - Reservation Deposit </span>
                                                    <p>*** <span id="displayNoteEditBill" >รายละเอียดโปรดระบุ</p>
                                        </td>
                                                <td id="displayReferenceEditBill"></td>
                                                <td id="displayAmountEditBill">{{ number_format($sumpayment, 2) }}</td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </section>
                                    <section class="receipt-subtotal">

                                        <div class="right">
                                        <ul class="font-w-500">
                                            <li>
                                            <span>Total Balance(Baht) </span>
                                            <span class="border-total-top">{{ number_format($sumpayment, 2) }}</span>
                                            </li>
                                            <li>
                                            <span>Vatable</span>
                                            <span>{{ number_format($sumpayment/1.07, 2) }}</span>
                                            </li>
                                            <li>
                                            <span>VAT 7 %</span>
                                            <span>{{ number_format($sumpayment-$sumpayment/1.07, 2) }}</span>
                                            </li>
                                            <li>
                                            <span>Non - Vatable</span>
                                            <span>0</span>
                                            </li>
                                            <li>
                                            <span>Total Amount (Baht)</span>
                                            <span>{{ number_format($sumpayment, 2) }}</span>
                                            </li>
                                            <li class="font-w-600">
                                            <span>Net Total</span>
                                            <span class="border-total">{{ number_format($sumpayment, 2) }}</span>
                                            </li>
                                        </ul>
                                        </div>
                                    </section>
                                    <section class="body-bottom">
                                        <div>
                                        <p> I agree that my liability for this invoice is not waived and agree to be held personally liable in the event that the indicated person, company, or association fails to pay for any part or the full amount of these charges. </p>
                                        </div>
                                    </section>
                                    <section class="signature">
                                        <div class="left">
                                        <p>Guest's Signature</p>
                                        </div>
                                        <div class="right">
                                        <p>Guest's Signature</p>
                                        </div>
                                    </section>
                                </div>
                            </section>
                            <div class="bottom">
                                <div class="flex-end pr-3">
                                    <button id="nextSteptoSave" class="bt-tg green md float-right" onclick="submit()"> Next </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />



    <script>
        $(document).ready(function () {
            // เลือกทุก input ที่มี class 'expiryDate'
            $('.expiryDate').on('input', function () {
                let input = $(this).val();

                // ลบเครื่องหมาย / ก่อนที่จะจัดรูปแบบใหม่
                input = input.replace(/\D/g, '');

                // ใส่ / หลังจากที่พิมพ์เดือน 2 ตัวแรก
                if (input.length > 2) {
                input = input.substring(0, 2) + '/' + input.substring(2, 4);
                }

                // จำกัดความยาวเป็น 5 ตัวอักษร (MM/YY)
                $(this).val(input.substring(0, 5));
            });
        });
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
            data();
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#Expiry').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Expiry').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#arrival').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#arrival').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#departure').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#departure').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#paymentDate').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#paymentDate').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        const table_name = ['roomTable','fbTable','banquetTable','entertainmentTable','ProposalTable','InvoiceTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        });
        function nav(id) {
            for (let index = 0; index < table_name.length; index++) {
                $('#'+table_name[index]).DataTable().destroy();
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        }
        function data() {
            var idcheck = $('#Guest').val();
            var nameID = document.getElementById('idfirst').value;
            if (idcheck) {
                id = idcheck;
            }else{
                id = nameID;
            }
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Data/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var fullname = response.fullname;
                    var Address = response.Address + ' '+ 'ตำบล'+ response.Tambon.name_th;
                    var Address2 = 'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    var TaxpayerIdentification = response.Identification;
                    $('#taxID').val(TaxpayerIdentification);
                    $('#address').val(Address);
                    $('#address2').val(Address2);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // ฟังก์ชัน Preview
            function Preview() {
                var idcheck = $('#Guest').val();
                var reservationNo = $('#reservationNo').val();
                var company = $('#company').val();
                var numberOfGuests = $('#numberOfGuests').val();
                var arrival = $('#arrival').val();
                var roomNo = $('#roomNo').val();
                var departure = $('#departure').val();
                var nameID = document.getElementById('idfirst').value;
                var note = $('#note').val();
                var datanamebank = ' Cash ' ;

                // เลือก id ที่จะใช้
                var id = idcheck ? idcheck : nameID;
                console.log(id);

                // AJAX เรียกข้อมูลจากเซิร์ฟเวอร์
                jQuery.ajax({
                    type: "GET",
                    url: `/Document/BillingFolio/Proposal/Additional/prewive/${id}`,  // ใช้ template literal สร้าง URL
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        var fullname = response.fullname;
                        var Address = response.Address + ' ตำบล' + response.Tambon.name_th + ' อำเภอ' + response.amphures.name_th + ' จังหวัด' + response.province.name_th + ' ' + response.Tambon.Zip_Code;
                        var TaxpayerIdentification = response.Identification;
                        var date = response.date;
                        var valid = response.valid;
                        var Time = response.Time;
                        var fullnameCom = response.fullnameCom;
                        // อัปเดตค่าต่างๆ ลงใน HTML
                        $('#displayGuestNameEditBill').text(fullname);
                        $('#displayTaxIDEditBill').text(TaxpayerIdentification);
                        $('#displayAddressEditBill').text(Address);
                        $('#displayReservationNoEditBill').text(reservationNo);
                        $('#displayCompanyEditBill').text(fullnameCom);
                        $('#displayRoomNoEditBill').text(roomNo); // ต้องตรวจสอบว่าคุณมีข้อมูล roomNo ที่ไหนหรือไม่
                        $('#displayArrivalEditBill').text(arrival);
                        $('#displayDepartureEditBill').text(departure);
                        $('#displayNumberOfGuestsEditBill').text(numberOfGuests);
                        $('#date').text(date);
                        $('#dateM').text(Time);
                        $('#Invoicedate').text(valid);
                        $('#displayPaymentDateEditBill').text(date);
                        $('#displayReferenceEditBill').text(reservationNo);
                        $('#displayNoteEditBill').text(note);
                        $('#displayDescriptionEditBill').text(datanamebank);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed: ", status, error);
                    }
                });
            }

            // เมื่อกดปุ่ม จะเรียกใช้ฟังก์ชัน Preview
            $('.bt-tg').on('click', function() {
                Preview();
            });
        });
        function submit() {
            document.getElementById("myForm").submit();
        }
    </script>
@endsection
