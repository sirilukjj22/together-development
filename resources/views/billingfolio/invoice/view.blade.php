@extends('layouts.masterLayout')
<style>
    .wrap-bill {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding:3em 1em;
    background-color: white;
    }
    .bill  {
    background-color: rgb(237, 237, 237);
    display: grid;
    grid-template-columns: 1fr ;
    grid-gap: 1rem;
    border: 1px solid #ccc;
    padding: 1rem;
    border-radius: 5px;
    width:900px;
    min-height: 700px;
    }
    .bill >p {
    text-align: center;
    font-family: 1.5em;
    font-weight: 500;
    }
    #table-revenueEditBill th {
    border-top: 1px solid #fff !important;
    border-bottom: 1px solid #fff !important;
    text-transform: uppercase;
    }
    #table-revenueEditBill td {
    border-top: 1px solid #fff !important;
    text-transform: capitalize;
    }
</style>
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class="span3">View Billing Folio</div>
            </div>
            <div class="col-auto">
            </div>
        </div> <!-- .row end -->
    </div>
</div>
<div id="content-index" class="body d-flex py-lg-4 py-3">
    <div class="container-xl">
        <div class="row clearfix">
            <div class="col-sm-12 col-12 pi">
                <div class="card-body">
                    {{-- <div class="flex-end my-2">
                        <button class="bt-tg-normal bt-grey md"  onclick="window.location.href='SplitePayment.html'">Back</button>
                    </div> --}}
                    <div class="wrap-bill">
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
                                                <h4 class="font-upper"> Tax invoice {{$data['invoice']}}</h4>
                                                <li>
                                                    <span>Guest name</span>
                                                    <span id="displayGuestNameEditBill">
                                                        {{$data['fullname']}}
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>Reservation No</span>
                                                    <span id="displayReservationNoEditBill">{{$data['reservationNo']}}</span>
                                                </li>
                                                <li>
                                                    <span>Company</span>
                                                    <span id="displayCompanyEditBill">
                                                        {{$data['fullnameCom']}}
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>Tax ID/Gst Pass</span>
                                                    <span id="displayTaxIDEditBill">{{$data['Identification']}}</span>
                                                </li>
                                                <li>
                                                    <span>Address</span>
                                                    <span id="displayAddressEditBill" >{{$data['Address']}}  {{$data['tambon']}}  {{$data['amphures']}} {{$data['province']}} {{$data['zip_code']}}</span>
                                                </li>
                                                </ul>
                                                <ul>
                                                <li>
                                                    <span>Page #</span>
                                                    <span>1/1 </span>
                                                </li>
                                                <li>
                                                    <span>Room No.</span>
                                                    <span id="displayRoomNoEditBill">{{$data['room']}}</span>
                                                </li>
                                                <li>
                                                    <span>Arrival</span>
                                                    <span id="displayArrivalEditBill">{{$data['arrival']}}</span>
                                                </li>
                                                <li>
                                                    <span>Departure</span>
                                                    <span id="displayDepartureEditBill">{{$data['departure']}}</span>
                                                </li>
                                                <li>
                                                    <span>No of Guest</span>
                                                    <span id="displayNumberOfGuestsEditBill">{{$data['numberOfGuests']}}</span>
                                                </li>
                                                <li>
                                                    <span>Printed Date</span>
                                                    <span  id="date">{{$data['dateFormatted']}} </span>
                                                </li>
                                                <li>
                                                    <span>Printed time</span>
                                                    <span  id="dateM">{{ $data['dateTime'] }}</span>
                                                </li>
                                                <li>
                                                    <span>Tax invoice Date</span>
                                                    <span  id="Invoicedate">{{$data['created_at']}}</span>
                                                </li>
                                                </ul>
                                            </div>
                                            </section>
                                        </header>
                                        <section class="receipt-cutomer-detail-body">
                                            <div>
                                            <div style="overflow: auto;">
                                                <table id="table-revenueEditBill" >
                                                    <thead>
                                                        <tr>
                                                        <th>Date</th>
                                                        <th >Description </th>
                                                        <th>Reference</th>
                                                        <th>amount</th>
                                                        </tr>
                                                    </thead>
                                                    @foreach($productItems as $key => $item)
                                                    <tr>
                                                        <td >{{$Date}}</td>
                                                        <td >
                                                            {{ $item['detail'] }}
                                                        </td>
                                                        <td ></td>
                                                        <td style="text-align: right">{{ number_format($item['amount'], 2) }}</td>
                                                    </tr>
                                                    @endforeach


                                                </table>
                                            </div>
                                            </div>
                                        </section>
                                        <section class="receipt-subtotal">

                                        <div class="d-flex  gap-2 flex-wrap justify-content-between w-100" >
                                            <div class="flex-grow-1" style="padding-left: 11%">** {{$data['note']}} **<span id="displayNoteEditBill"></span></div>

                                            <div class="right">
                                                <ul class="font-w-500">
                                                    <li>
                                                    <span>Total Balance(Baht) </span>
                                                    <span class="border-total-top" id="Balance">{{ number_format($data['Amount'], 2) }}</span>
                                                    </li>
                                                    <li>
                                                    <span>Vatable</span>
                                                    <span id="Vatable">{{ number_format($data['Amount']/1.07, 2) }}</span>
                                                    </li>
                                                    <li>
                                                    <span>VAT 7 %</span>
                                                    <span id="VAT">{{ number_format($data['Amount']-$data['Amount']/1.07, 2) }}</span>
                                                    </li>
                                                    <li>
                                                    <span>Non - Vatable</span>
                                                    <span>0</span>
                                                    </li>
                                                    <li>
                                                    <span>Total Amount (Baht)</span>
                                                    <span id="AmountBaht">  {{ number_format($data['Amount'], 2) }}</span>
                                                    </li>
                                                    <li class="font-w-600">
                                                    <span>Net Total</span>
                                                    <span class="border-total" id="Nettotal">  {{ number_format($data['Amount'], 2) }}</span>
                                                    </li>
                                                </ul>
                                            </div>
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
                    </div>
                    <div class="col-lg-12 row mt-5">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4 "  style="display:flex; justify-content:center; align-items:center;">
                            <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                                Back
                            </button>
                        </div>
                        <div class="col-lg-4"></div>
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
    function BACKtoEdit(){
            event.preventDefault();
            Swal.fire({
                title: "Do you want to go back?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(1);
                    // If user confirms, redirect to the correct route
                    window.location.href = "{!! route('BillingFolio.CheckPI', $ids) !!}";
                }
            });
        }
</script>
@endsection
