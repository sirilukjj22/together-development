@extends('layouts.masterLayout')
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

.image-container .titleh1 {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 10px;
}
.titleQuotation{
    display:flex;
    justify-content:center;
    align-items:center;
}
.select2{
    margin: 4px 0;
    border: 1px solid #ffffff;
    border-radius: 4px;
    box-sizing: border-box;
}
.calendar-icon {
    background: #fff no-repeat center center;
    vertical-align: middle;
    margin-right: 5px;
    transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
}
.calendar-icon:hover {
    background: #fff ;
    transform: scale(1.1);
}
.calendar-icon:active {
    transform: scale(0.9);
}
.com {
    display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
    border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
    padding-bottom: 5px;
    font-size: 20px;
}
.Profile{
    width: 15%;
}
.styled-hr {
    border: none; /* เอาขอบออก */
    border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
}
.table-borderless1{
    border-radius: 6px;
    background-color: #109699;
    color:#fff;
}
.centered-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.centered-content4 {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    border: 2px solid #6b6b6b; /* กำหนดกรอบสี่เหลี่ยม */
    padding: 20px; /* เพิ่ม padding ภายในกรอบ */
    border-radius: 5px; /* เพิ่มมุมโค้ง (ถ้าต้องการ) */
    height: 120px;
    width: 120px; /* กำหนดความสูงของกรอบ */
}
.proposal{
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    top: 0px;
    right: 6;
    width: 70%;
    height: 60px;
    border: 3px solid #2D7F7B;
    border-radius: 10px;
    background-color: #109699;
}
.proposalcode{
    top: 0px;
    right: 6;
    width: 70%;
    height: 90px;
    border: 3px solid #2D7F7B;
    border-radius: 10px;
}
.btn-space {
    margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
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
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to View Receipt.</small>
                <h1 class="h4 mt-1">View Receipt (ดูใบเสร็จ)</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container">
        <div class="container mt-3">
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <div class="card p-4 mb-4">
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                                <img src="{{ asset('assets2/images/logo_crop.png') }}" alt="Together Resort Logo" class="logo"/>
                                <div class="info">
                                    <p class="titleh1">Together Resort Limited Partnership</p>
                                    <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                                    <p>Tel : 032-708-888, 098-393-944-4 Fax :</p>
                                    <p>Email : reservation@together-resort.com Website : www.together-resort.com</p>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="row">
                                    <b class="titleQuotation" style="font-size: 24px;color:rgba(45, 127, 123, 1);">Receipt Payment</b>
                                    <span class="titleQuotation">Receipt ID : {{$receipt_ID}}</span>
                                    <span class="titleQuotation">Propossal ID : {{$QuotationID}}</span>
                                    <input type="hidden" id="receipt_ID" name="receipt_ID" value="{{$receipt_ID}}">
                                    <input type="hidden" id="QuotationID" name="QuotationID" value="{{$QuotationID}}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-7 col-md-12 col-sm-12" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                                <b class="com mt-2 my-2"style="font-size:18px">Company Information</b>
                                <table>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Company Name :</b></td>
                                        <td>
                                            <span id="Company_name" name="Company_name" >{{$comtypefullname}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Address :</b></td>
                                        <td><span id="Address" >{{$CompanyID->Address}} {{'ตำบล' . $TambonID->name_th}} </span></td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span id="Address2" >{{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Number :</b></td>
                                        <td>
                                            <span id="Company_Number">{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}</span>
                                            <b style="margin-left: 10px;color:#000;">Company Fax : </b>
                                            @if (is_object($company_fax) && property_exists($company_fax, 'Fax_number'))
                                                <span id="Company_Fax">{{ $company_fax->Fax_number }}</span>
                                            @else
                                                <span id="Company_Fax">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Email :</b></td>
                                        <td><span id="Company_Email">{{$CompanyID->Company_Email}}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Taxpayer Identification : </b></td>
                                        <td><span id="Taxpayer">{{$CompanyID->Taxpayer_Identification}}</span></td>
                                    </tr>

                                </table>
                                <div>
                                    <br>
                                </div>
                                <div>
                                    <b class="com my-2" style="font-size:18px">Personal Information</b>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-6">
                                        <p style="display: inline-block;font-weight: bold;margin-left: 10px;">Contact Name :</p>
                                        <p style="display: inline-block;"><span id="Company_contact">{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span></p>
                                    </div>
                                    <div class="col-6">
                                        <p style="display: inline-block;font-weight: bold;">Contact Number :</p>
                                        <p style="display: inline-block;"><span id="Contact_Phone">{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span></p>
                                    </div>
                                    <div>
                                        <p style="display: inline-block;font-weight: bold;margin-left: 10px;">Contact Email :</p>
                                        <p style="display: inline-block;"><span id="Contact_Email">{{$Contact_name->Email}}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div><br><br><br><br></div>
                                <div class="col-12 row" >
                                    <div class="col-lg-6">
                                        <p style="display: inline-block;font-weight: bold;">Check In :</p><br>
                                        <p style="display: inline-block;font-weight: bold;">Check Out :</p><br>
                                        <p style="display: inline-block;font-weight: bold;">Length of Stay :</p><br>
                                        <p style="display: inline-block;font-weight: bold;">Number of Guests :</p>
                                    </div>
                                    <div class="col-lg-6">

                                        @if ($Quotation->checkin == NULL)
                                            <p style="display: inline-block;"><span id="checkinpo">No Check in date</span></p><br>
                                            <p style="display: inline-block;"><span id="checkoutpo">-</span></p><br>
                                        @else
                                            <p style="display: inline-block;"><span id="checkinpo">{{$Quotation->checkin}}</span></p><br>
                                            <p style="display: inline-block;"><span id="checkoutpo">{{$Quotation->checkout}}</span></p><br>
                                        @endif
                                        @if ($Quotation->day == null)
                                            <p style="display: inline-block;"><span id="daypo">-</span><span id="nightpo"></span></P><br>
                                        @else
                                            <p style="display: inline-block;"><span >{{$Quotation->day}}</span> วัน <span >{{$Quotation->night}}</span> คืน</p><br>
                                        @endif
                                        <p style="display: inline-block;"><span id="Adultpo">{{$Quotation->adult}}</span> Adult , <span id="Childrenpo">{{$Quotation->children}}</span> Children</p>
                                    </div>
                                </div>
                            </div>
                            <div class="styled-hr"></div>
                        </div>
                        <div class="mt-2">
                            <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
                        </div>
                        <div class="row mt-2">
                            <table class=" table  align-middle mb-0" style="width:100%">
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">No.</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Description</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:1%;"></th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Quantity</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Price / Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Discount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Price Discount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="display-selected-items">
                                    @if (!empty($selectproduct))
                                        @foreach ($selectproduct as $key => $item)
                                            @foreach ($unit as $singleUnit)
                                                @if($singleUnit->id == @$item->product->unit)
                                                    @php
                                                    $var = $key+1;
                                                    @endphp
                                                    <tr id="tr-select-main{{$item->Product_ID}}">
                                                        <input type="hidden" id="tr-select-main{{$item->Product_ID}}" name="tr-select-main[]" value="{{$item->Product_ID}}">
                                                        <td><input type="hidden" id="ProductID" name="ProductIDmain[]" value="{{$item->Product_ID}}">{{$key+1}}</td>
                                                        <td style="text-align:left;">{{@$item->product->name_th}} <span class="fa fa-info-circle" data-bs-toggle="tooltip" data-placement="top" title="{{@$item->product->maximum_discount}} %"></span></td>
                                                        <td style="color: #fff">
                                                            <input type="hidden" id="pax{{$var}}" name="pax[]" value="{{$item->pax}}" rel="{{$var}}">
                                                            <span id="paxtotal{{$var}}">{{ floatval($item->pax) * floatval($item->Quantity) }}</span>
                                                        </td>
                                                        <td class="Quantity" data-value="{{$item->Quantity}}" style="text-align:center;">
                                                            <input type="text" id="quantity{{$var}}" name="Quantitymain[]" rel="{{$var}}" style="text-align:center;"class="quantity-input form-control" value="{{$item->Quantity}} "oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"disabled>
                                                        </td>
                                                        <td>{{ $singleUnit->name_th }}</td>
                                                        <td class="priceproduct" data-value="{{$item->priceproduct}}"style="text-align:center;"><input type="hidden" id="totalprice-unit{{$var}}" name="priceproductmain[]" value="{{$item->priceproduct}}">{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                        <td class="discount"style="text-align:center;">
                                                            <div class="input-group">
                                                                <input type="text" id="discount{{$var}}" name="discountmain[]" rel="{{$var}}"style="text-align:center;" class="discount-input form-control" value="{{$item->discount}}"oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"disabled>
                                                                <input type="hidden" id="maxdiscount{{$var}}" name="maxdiscount[]" rel="{{$var}}" class=" form-control" value="{{$item->product->maximum_discount}}">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </td>
                                                        <td class="net-price"style="text-align:center;" ><span id="net_discount{{$var}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</span></td>
                                                        <td class="item-total"style="text-align:center;"><span id="all-total{{$var}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <table class="table table-borderless" >
                                <thead >
                                    <tr>
                                        <th style="width:8%"></th>
                                        <th style="width:40%"></th>
                                        <th style="width:8%;text-align:left;"></th>
                                        <th style="width:15%;text-align:left;"></th>
                                        <th style="width:15%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receiptdata as $key => $item)
                                    <tr>
                                        <td>ใบที่ {{$key +1}} </td>
                                        <td>ใบเสร็จชำระเงิน อ้างอิงจาก Receip ID : {{$item->receipt_ID}} <input type="hidden" id="receipt_ID" name="receipt_ID" value="{{$item->receipt_ID}}"></td>
                                        <td>
                                        </td>
                                        <td style="text-align:right;font-size: 14px;"><b>Deposit amount</b></td>
                                        <td>{{number_format($item->deposit)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tbody>
                                    @foreach ($invoice as $key => $item)
                                    <tr>
                                        <td>ใบที่ {{$key +1}} </td>
                                        <td>ใบแจ้งชำระเงิน อ้างอิงจาก Invoice ID : {{$item->Invoice_ID}} <input type="hidden" id="Invoice_ID" name="Invoice_ID" value="{{$item->Invoice_ID}}"></td>
                                        <td>
                                        @if ($item->status_receive == 1 )
                                            <span class="badge rounded-pill bg-success">เอกสารสมบูรณ์</span>
                                        @else
                                            <span class="badge rounded-pill "style="background-color: #FF6633	">เอกสารไม่สมบูรณ์</span>
                                        @endif
                                        </td>
                                        <td style="text-align:right;font-size: 14px;"><b>Deposit amount</b></td>
                                        <td>{{number_format($item->sumpayment)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (@Auth::user()->roleMenuDiscount('Proposal',Auth::user()->id) == 1)
                            <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="1">
                        @else
                            <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="0">
                        @endif
                        <input type="hidden" id="paxold" name="paxold" value="{{$Quotation->TotalPax}}">
                        <input type="hidden" name="discountuser" id="discountuser" value="{{@Auth::user()->discount}}">
                        <div class="col-12 row ">
                            <div class="col-lg-8 col-md-8 col-sm-12 mt-2" >
                                <span >Notes or Special Comment</span>
                                <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 " >
                                @if ($vat_type == 50 )
                                    <table class="table table-borderless" id="PRICE_INCLUDE_VAT" >
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    <span id="sp">{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscount">{{ number_format($subtotal, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Price Before Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="Net-price">{{ number_format($beforeTax, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Value Added Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($AddTax, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Deposit Receipt</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($totalreceipt, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Deposit Invoice</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($totalinvoice, 2, '.', ',') }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @elseif ($vat == 51)
                                    <table class="table table-borderless" id="PRICE_EXCLUDE_VAT">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amountEXCLUDE">{{ number_format($totalAmount, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    <span id="spEXCLUDE">{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscountEXCLUDE">{{ number_format($subtotal, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Deposit Receipt</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($totalreceipt, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Deposit Ivoice</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($totalinvoice, 2, '.', ',') }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @elseif ($vat == 52)
                                    <table class="table table-borderless "id="PRICE_PLUS_VAT">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amountpus">{{ number_format($totalAmount, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    <span id="sppus">{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscountpus">{{ number_format($subtotal, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Value Added Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vatpus">{{ number_format($AddTax, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Deposit Receipt</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($totalreceipt, 2, '.', ',') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Deposit Invoice</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">{{ number_format($totalinvoice, 2, '.', ',') }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 row"></div>
                        <div class="col-12 row">
                            <div class="col-lg-8 col-md-3 col-sm-12"></div>
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <table class="table table-borderless1" >
                                    <tbody>
                                        <tr>
                                            <td style="text-align:center;">
                                                <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;padding:5px;  padding-bottom: 8px;">
                                                    <b style="font-size: 14px;">Net Total</b>
                                                    <strong id="total-Price" style="font-size: 16px; margin-left: 10px;"><span id="Net-Total">{{ number_format($Nettotal, 2, '.', ',') }} </span></strong><input type="hidden" id="Nettotal" name="Nettotal" value="{{$Nettotal-$totalinvoice}}">
                                                    <input type="hidden" id="total" name="total" value="{{$Nettotal}}">
                                                    <input type="hidden" id="deposit" name="deposit" value="{{$totalinvoice}}">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8"></div>
                            <div class="col-4 styled-hr"></div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8">
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-12" id="Pax" style="display: block">
                                <table class="table table-borderless" >
                                    <tbody>
                                        <tr>
                                            <td style="text-align:right;width: 55%;font-size: 14px;"><b>Number of Guests :</b></td>
                                            <td style="text-align:left;width: 45%;font-size: 14px;"><span id="PaxToTal">{{$TotalPax}}</span> Adults
                                                <input type="hidden" name="PaxToTalall" id="PaxToTalall">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width: 55%;font-size: 14px;"><b>Average per person :</b></td>
                                            <td style="text-align:left;width: 45%;font-size: 14px;"><span id="Average">{{ number_format($totalaverage, 2, '.', ',') }}</span> THB</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <strong class="com" style="font-size: 18px">Method of Payment</strong>
                            </div>
                            <span class="col-md-8 col-sm-12"id="Payment50" style="display: block" >
                                Please make a 50% deposit within 7 days after confirmed. <br>
                                Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                            </span>
                            <span class="col-md-8 col-sm-12"  id="Payment100" style="display: none">
                                Please make a 100% deposit within 3 days after confirmed. <br>
                                Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                            </span>
                            <div class="row">
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <div class="col-12  mt-2">
                                        <div class="row">
                                            <div class="col-2 mt-3" style="display: flex;justify-content: center;align-items: center;">
                                                <img src="{{ asset('/image/bank/SCB.jpg') }}" style="width: 60%;border-radius: 50%;"/>
                                            </div>
                                            <div class="col-7 mt-2">
                                                <strong>The Siam Commercial Bank Public Company Limited <br>Bank Account No. 708-226791-3<br>Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="col-12 mt-2">
                            <div class="col-4">
                                <strong class="titleh1">รับรอง</strong>
                            </div>
                            <div class="col-12 my-2">
                                <div class="row">
                                    <div class="col-lg-2 centered-content">
                                        <span>สแกนเพื่อเปิดด้วยเว็บไซต์</span>
                                        @php
                                            use SimpleSoftwareIO\QrCode\Facades\QrCode;

                                        @endphp
                                        <div class="mt-3">
                                            {!! QrCode::size(90)->generate('No found'); !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้ออกเอกสาร (ผู้ขาย)</span><br>
                                        <br><br>
                                        <span>{{@Auth::user()->name}}</span>
                                        <span id="issue_date_document"></span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้อนุมัติเอกสาร (ผู้ขาย)</span><br>
                                        <br><br>
                                        <span>{{@Auth::user()->name}}</span>
                                        <span id="issue_date_document1"></span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ตราประทับ (ผู้ขาย)</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้รับเอกสาร (ลูกค้า)</span>
                                        <br><br><br>
                                        ______________________
                                        <span>_____/__________/_____</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span >ตราประทับ (ลูกค้า)</span>
                                        <div class="centered-content4 mt-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="col-12 row mt-5">
                            <div class="col-4"></div>
                            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('receipt.index') }}'">
                                    Back
                                </button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
