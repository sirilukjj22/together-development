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
</style>
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class="span3">View Spilte Bill</div>
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
                    @foreach ($groupedData  as $key => $paymentGroup)

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
                                                <h4 class="font-upper"> Tax invoice {{ $paymentGroup['bill'] }}</h4>
                                                <li>
                                                    <span>Guest name</span>
                                                    <span id="displayGuestNameEditBill">
                                                        @if ($paymentGroup['type']== 'Mprename')
                                                            {{ $paymentGroup['fullname'] }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>Reservation No</span>
                                                    <span id="displayReservationNoEditBill">{{ $datadetailbill['reservationNo'] }}</span>
                                                </li>
                                                <li>
                                                    <span>Company</span>
                                                    <span id="displayCompanyEditBill">
                                                        @if ($paymentGroup['type']== 'Mcompany_type')
                                                            {{ $paymentGroup['fullname'] }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>Tax ID/Gst Pass</span>
                                                    <span id="displayTaxIDEditBill">{{ $paymentGroup['Identification'] }}</span>
                                                </li>
                                                <li>
                                                    <span>Address</span>
                                                    <span id="displayAddressEditBill" >{{ $paymentGroup['Address'] }}</span>
                                                </li>
                                                </ul>
                                                <ul>
                                                <li>
                                                    <span>Page #</span>
                                                    <span>{{$key}}/{{$bill}} </span>
                                                </li>
                                                <li>
                                                    <span>Room No.</span>
                                                    <span id="displayRoomNoEditBill">{{ $datadetailbill['room'] }}</span>
                                                </li>
                                                <li>
                                                    <span>Arrival</span>
                                                    <span id="displayArrivalEditBill">{{ $datadetailbill['arrival'] }}</span>
                                                </li>
                                                <li>
                                                    <span>Departure</span>
                                                    <span id="displayDepartureEditBill">{{ $datadetailbill['departure'] }}</span>
                                                </li>
                                                <li>
                                                    <span>No of Guest</span>
                                                    <span id="displayNumberOfGuestsEditBill">{{ $datadetailbill['reservationNo'] }}</span>
                                                </li>
                                                <li>
                                                    <span>Printed Date</span>
                                                    <span  id="date">{{ $formattedDateprint }}</span>
                                                </li>
                                                <li>
                                                    <span>Printed time</span>
                                                    <span  id="dateM">{{ $formattedTime }}</span>
                                                </li>
                                                <li>
                                                    <span>Tax invoice Date</span>
                                                    <span  id="Invoicedate">{{ $datadetailbill['valid'] }}</span>
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
                                                    @php
                                                        $Balance = 0;
                                                        $Vatable = 0;
                                                        $addtax = 0;  // กำหนดค่าเริ่มต้น
                                                    @endphp

                                                        @if (isset($paymentGroup['payments']) && is_array($paymentGroup['payments']))
                                                            @foreach ($paymentGroup['payments'] as $payment)
                                                            <tbody>
                                                                @if ($Proposal)
                                                                <tr>
                                                                    <td >{{$Proposal->issue_date}}</td>
                                                                    <td >
                                                                        {{$Proposal->Quotation_ID}}
                                                                    </td>
                                                                    <td ></td>
                                                                    <td style="text-align: right">{{ number_format($Proposal->Nettotal/$bill, 2) }} </td>
                                                                </tr>
                                                                @endif
                                                                @if ($Additional)
                                                                <tr>
                                                                    <td >{{$Additional->issue_date}}</td>
                                                                    <td >
                                                                        {{$Additional->Additional_ID}}
                                                                    </td>
                                                                    <td ></td>
                                                                    <td style="text-align: right">{{ number_format($Additional->Nettotal/$bill, 2) }} </td>
                                                                </tr>
                                                                @endif
                                                                @foreach ($receive as  $item)
                                                                <tr>
                                                                    <td >{{$item->valid}}</td>
                                                                    <td >
                                                                        {{$item->Receipt_ID}} ({{$item->detail}})
                                                                    </td>
                                                                    <td ></td>
                                                                    <td style="text-align: right">- {{ number_format($item->document_amount/$bill, 2) }}</td>
                                                                </tr>
                                                                @endforeach
                                                                <tr style="border: none">
                                                                    <td id="displayPaymentDateEditBill">{{ $paymentdate }}</td>
                                                                    <td>
                                                                        <span id="displayDescriptionEditBill">{{ $payment['datanamebank'] }}</span>
                                                                    </td>
                                                                    <td id="displayReferenceEditBill"></td>
                                                                    <td id="displayAmountEditBill">{{ number_format($payment['amount'], 2) }}</td>
                                                                </tr>
                                                            </tbody>
                                                            @php
                                                                $Balance += $payment['amount'];
                                                                $Vatable = $Balance / 1.07;
                                                                $addtax = $Balance - $Vatable;
                                                            @endphp
                                                            @endforeach
                                                        @endif


                                                </table>
                                            </div>
                                            </div>
                                        </section>
                                        <section class="receipt-subtotal">

                                        <div class="d-flex  gap-2 flex-wrap justify-content-between w-100" >
                                            <div class="flex-grow-1" style="padding-left: 11%">** {{ $paymentGroup['remark'] }} **<span id="displayNoteEditBill"></span></div>

                                            <div class="right">
                                                <ul class="font-w-500">
                                                    <li>
                                                    <span>Total Balance(Baht) </span>
                                                    <span class="border-total-top" id="Balance">{{ number_format($Balance, 2) }}</span>
                                                    </li>
                                                    <li>
                                                    <span>Vatable</span>
                                                    <span id="Vatable">{{ number_format($Vatable, 2) }}</span>
                                                    </li>
                                                    <li>
                                                    <span>VAT 7 %</span>
                                                    <span id="VAT">{{ number_format($addtax, 2) }}</span>
                                                    </li>
                                                    <li>
                                                    <span>Non - Vatable</span>
                                                    <span>0</span>
                                                    </li>
                                                    <li>
                                                    <span>Total Amount (Baht)</span>
                                                    <span id="AmountBaht">{{ number_format($Balance, 2) }}</span>
                                                    </li>
                                                    <li class="font-w-600">
                                                    <span>Net Total</span>
                                                    <span class="border-total" id="Nettotal">{{ number_format($Balance, 2) }}</span>
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

                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
