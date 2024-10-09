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

    .sub {
        background-color: white;
        border-radius: 7px
    }
    .expiryDate {
    position: relative;  /* เพื่อให้ปฏิทินจัดตำแหน่งสัมพันธ์กับ input */
}

.expiryDate + .daterangepicker {
    position: absolute;
    top: 40px;  /* ปรับตำแหน่งของปฏิทินให้แสดงใกล้กับ input */
    left: 0;
    z-index: 9999;  /* ทำให้ปฏิทินอยู่บนสุด */
}
.w-spaceWrap-less860px {
display: flex;
flex-wrap: nowrap;
white-space: nowrap
      }
      @media (max-width:860px) {
        .w-spaceWrap-less860px {
            flex-wrap: wrap;
        }
      }
</style>
@section('content')

    <div class="main px-xl-5 px-lg-4 px-md-3">
        <div class="body-header d-flex py-3">
            <div class="container-xl">
                <div class="flex-between mb-4"><h1 class="top-web">Receipt / Tax invoice</h1></div>

                <section class="wrap-show-income-d-grid-1rem bg-together-full">
                    {{-- <div class="d-grid-2column">
                        <div class="card-d-grid1-2row bg-together">
                            <span id="proposalID">Proposal ID : {{$Proposal->Quotation_ID}}</span>
                            <span id="proposalAmount" class="proposalAmount">{{ number_format($Proposal->Nettotal, 2) }}</span>
                        </div>
                        <div class="card-d-grid1-2row bg-together">
                            <span id="totalReceipt">Proforma Invoice ID : {{$invoices->Invoice_ID}}</span>
                            <span id="receiptAmount" class="receiptAmount">{{ number_format($invoices->sumpayment, 2) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="sub-bill">
                            <div class="sub" >
                            <div class="top flex-end" >
                                <div class="flex-grow-1"></div>
                                <button class="bt-tg mr-2" style="position: relative" data-toggle="modal" data-target="#modalAddBill">
                                    <span >Issue Bill</span>
                            </div>
                            <!-- Bill ที่สร้างขึ้นมาใหม่ -->
                            <div id="show-bill-acd" class="wrap-bt new-bill-entry"></div>
                            <div class="bottom">
                                <div class="flex-end pr-3">
                                    <button id="nextSteptoSave" class="bt-tg green md float-right" onclick="submit()"> Next </button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div> --}}
                </section>
            </div>
        </div>
        <input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
        <input type="hidden" class="form-control" id="InvoiceID" value="{{$Invoice_ID}}" />
        <!-- Modal ออกบิลปกติ-->

    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />




@endsection
