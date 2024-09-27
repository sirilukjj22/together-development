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
</style>
@section('content')

    <div class="main px-xl-5 px-lg-4 px-md-3">
        <div class="body-header d-flex py-3">
            <div class="container-xl">
                <div class="flex-between mb-4"><h1 class="top-web">Receipt / Tax invoice</h1></div>

                <section class="wrap-show-income-d-grid-1rem bg-together-full">
                    <div class="d-grid-2column">
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
                                <button class="bt-tg mr-2" style="position: relative;">
                                <span data-toggle="modal" data-target="#modalAddBill" >Issue Bill</span>
                            </div>
                            <!-- Bill ที่สร้างขึ้นมาใหม่ -->
                            <div id="show-bill-acd" class="wrap-bt new-bill-entry"></div>
                            <div class="bottom">
                                <div class="flex-end pr-3">
                                <a href="#">
                                    <button id="nextSteptoSave" class="bt-tg green md float-right"> Next </button>
                                </a>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

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
                <div class="modal-body">
                    <form action="/" id="addNewBill" class="form-modal">
                    <div class="center header-bill">
                        <span>Receipt ID : <span id="generatedReceiptID"></span>
                        </span>
                        <span style="color:red">Proforma Invoice ID : <span id="generatedReceiptID"></span>
                        </span>
                    </div>
                    <h3>
                        <span>Customer Details</span>
                    </h3>
                    <div >
                        <label for="" class="star-red">Guest Name</label>

                            <select name="Guest" id="Guest" class="select2" onchange="data()" required>
                                <!-- ตัวเลือกแรก -->
                                <option value=""></option>
                                <option value="{{$name_ID}}">{{$name}}</option>

                                <!-- วนลูปข้อมูลเพื่อแสดงตัวเลือกอื่นๆ -->
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
                        <input type="text" class="form-control" id="reservationNo" required />
                    </div>
                    <div >
                        <label for="company">Company</label>
                        @if ($type == 'Company')
                            <input type="text" class="form-control " id="company" value="{{$name}}" />
                        @else
                            <input type="text" class="form-control " id="company" disabled  style="background-color: #59a89e81; color: #59a89e81;"/>
                        @endif
                    </div>
                    <div >
                        <label for="taxID">Tax ID/Gst Pass</label>
                        <input type="text" id="taxID" value="auto-select" class="form-control bg-green-light" />
                    </div>
                    <div >
                        <label for="address">Address</label>
                        <span>
                        <input type="text" id="address" value="auto-select" class="bg-green-light" />
                        </span>
                    </div>
                    <h3>
                        <span>Stay Details</span>
                    </h3>
                    <div >
                        <label class="star-red" for="roomNo">Room No.</label>
                        <span>
                        <input type="text" id="roomNo" required />
                        </span>
                    </div>
                    <div >
                        <label class="star-red" for="numberOfGuests">Number of Guests</label>
                        <span>
                        <input type="text" id="numberOfGuests" required />
                        </span>
                    </div>
                    <div >
                        <label for="arrival">Arrival</label>
                        <span>
                        <input type="date" id="arrival" value="11/02/2024" class="bg-green-light" />
                        </span>
                    </div>
                    <div >
                        <label for="departure">Departure</label>
                        <span>
                        <input type="date" id="departure" value="14/02/2024" class="bg-green-light" />
                        </span>
                    </div>
                    <h3>
                        <span>Payment Details</span>
                    </h3>
                    <div class="payment-container">
                        <label for="paymentType" class="star-red">Payment Type</label>
                        <select class="paymentType select2">
                        <option value="" disabled selected>Select Payment Type</option>
                        <option value="cash">Cash</option>
                        <option value="bankTransfer">Bank Transfer</option>
                        <option value="creditCard">Credit Card</option>
                        <option value="cheque">Cheque</option>
                        </select>
                        <!-- Cash Input -->
                        <div class="cashInput input-g" style="display: none;">
                        <label for="cashAmount" class="star-red">Cash Amount</label>
                        <span>
                            <input type="text" class="cashAmount" placeholder="Enter cash amount">
                        </span>
                        </div>
                        <!-- Bank Transfer Input -->
                        <div class="bankTransferInput input-g" style="display: none;">
                        <label for="bankName" class="star-red">Bank</label>
                        <select class="bankName select2">
                            <option value="SCB">SCB Bank Transfer - Together Resort Ltd - Reservation Deposit </option>
                        </select>
                        <label for="bankTransferAmount" class="star-red">Amount</label>
                        <span>
                            <input type="text" class="bankTransferAmount" placeholder="Enter transfer amount">
                        </span>
                        </div>
                        <!-- Credit Card Input -->
                        <div class="creditCardInput input-g" style="display: none;">
                        <label for="creditCardNumber" class="star-red">Credit Card Number</label>
                        <span>
                            <input type="text" class="creditCardNumber" placeholder="xxxx-xxxx-xxxx-xxxx" maxlength="19">
                        </span>
                        <label for="expiryDate" class="star-red">Expiry Date</label>
                        <span>
                            <input type="text" class="expiryDate" placeholder="MM/YY">
                        </span>
                        <label for="creditCardAmount" class="star-red">Amount</label>
                        <span>
                            <input type="text" class="creditCardAmount" placeholder="Enter amount">
                        </span>
                        </div>
                        <!-- Cheque Input -->
                        <div class="chequeInput input-g" style="display: none;">
                        <label for="chequeBank" class="star-red">Bank</label>
                        <span>
                            <input type="text" class="chequeBank" placeholder="Enter bank name">
                        </span>
                        <label for="chequeNumber" class="star-red">Cheque Number</label>
                        <span>
                            <input type="text" class="chequeNumber" placeholder="Enter cheque number">
                        </span>
                        <label for="chequeAmount" class="star-red">Amount</label>
                        <span>
                            <input type="text" class="chequeAmount" placeholder="Enter amount">
                        </span>
                        </div>
                    </div>
                    <div class="d-grid" style="height: max-content;">
                        <div class="input-g">
                        <label class="star-red" for="paymentDate">Date</label>
                        <span>
                            <input type="date" id="paymentDate" />
                        </span>
                        </div>
                        <div class="input-g">
                        <label for="note">Note</label>
                        <span>
                            <textarea id="note" placeholder="Enter details"></textarea>
                        </span>
                        </div>
                        <div class="input-g">
                        <label for="reference">Reference</label>
                        <span>
                            <input readonly type="text" id="reference" class="bg-green-light" />
                        </span>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bt-tg bt-grey sm" data-dismiss="modal"> Close </button>
                    <button type="button" id="billInput" class="bt-tg sm">Preview</button>
                </div>
                </div>
            </div>
        </div>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
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
            var id = $('#Guest').val();
            console.log(id);
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Data/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    // var fullName = response.data.First_name + ' ' + response.data.Last_name;
                    // var fullid = response.data.id ;
                    // if (response.Company_type.name_th === 'บริษัทจำกัด') {
                    //     var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด';
                    // }
                    // else if (response.Company_type.name_th === 'บริษัทมหาชนจำกัด') {
                    //     var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด'+' '+'(มหาชน)';
                    // }
                    // else if (response.Company_type.name_th === 'ห้างหุ้นส่วนจำกัด') {
                    //     var fullNameCompany = 'ห้างหุ้นส่วนจำกัด' + ' ' + response.company.Company_Name ;
                    // }
                    // var Address = response.company.Address + ' '+ 'ตำบล'+ response.Tambon.name_th;
                    // var Address2 = 'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    // var companyfax = response.company_fax.Fax_number;
                    // var CompanyEmail = response.company.Company_Email;
                    // var Discount_Contract_Rate = response.company.Discount_Contract_Rate;
                    // var TaxpayerIdentification = response.company.Taxpayer_Identification;
                    // var companyphone = response.company_phone.Phone_number;

                    // var Contactphones =response.Contact_phones.Phone_number;
                    // var Contactemail =response.data.Email;

                    // console.log(response.data.First_name);
                    // var formattedPhoneNumber = companyphone;


                    // var formattedContactphones = Contactphones;
                    // $('#Company_Contact').val(fullName).prop('disabled', true);
                    // $('#Company_Discount').val(Discount_Contract_Rate);
                    // $('#Company_Contactname').val(fullid);
                    // $('#Company_name').text(fullNameCompany);
                    // $('#Address').text(Address);
                    // $('#Address2').text(Address2);
                    // $('#Company_Number').text(formattedPhoneNumber);
                    // $('#Company_Fax').text(companyfax);
                    // $('#Company_Email').text(CompanyEmail);
                    // $('#Taxpayer').text(TaxpayerIdentification);
                    // $('#Company_contact').text(fullName);
                    // $('#Contact_Phone').text(formattedContactphones);
                    // $('#Contact_Email').text(Contactemail);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
    </script>
@endsection
