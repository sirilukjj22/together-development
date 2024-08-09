@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Receive Payment.</small>
                <h1 class="h4 mt-1">Receive Payment (รับการชำระเงิน)</h1>
            </div>
        </div>
    </div>
@endsection
<style>
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
</style>
@section('content')
<div class="container">
    <form action="{{url('/Document/invoice/receive/check/payment/'.$invoice->id)}}" method="POST">
        @csrf
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div style="border: 2px solid #2D7F7B;cursor: pointer; padding: 5px 10px;border-radius: 10px;">
                                <b>Invoice No. : {{$Invoice_ID}}</b>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-12"> </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <b>Payment Date</b><span style="color: red">*</span>
                            <input type="text" class="form-control" id="dateInput" name="dateInput" value="{{$Date}}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-lg-4 col-md-12 col-sm-12 mt-2">
                            <b for="Status">Receive Payment Method</b><span style="color: red">*</span>
                            <select name="Filter" id="Filter" class="form-select" >
                                <option value="" selected disabled>Select</option>
                                <option value="Cash">เงินสด</option>
                                <option value="BankTransfer">เงินโอนเข้าบัญชี</option>
                                <option value="CreditCard">เช็คธนาคาร</option>
                                <option value="Cheque">บัครเครดิต</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-2" id="cash" style="display:block">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 mt-2">
                                <b>Payment Amount</b><span style="color: red">*</span>
                                <input type="text" name="Amount" id="Amount1" class="form-control">
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 mt-2">
                                <b>Remark</b>
                                <input type="text" name="Remark" id="Remark1" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-2" id="BankTransfer" style="display:none">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 mt-2">
                                <b>Bank</b><span style="color: red">*</span>
                                <select name="Bank" id="Bank2"  class="select2" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Bank as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                                <b>Payment Amount</b><span style="color: red">*</span>
                                <input type="text" name="Amount" id="Amount2" class="form-control">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                                <b>Remark</b>
                                <input type="text" name="Remark" id="Remark2" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-2" id="CreditCard" style="display:none">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 mt-2">
                                <b>Bank</b><span style="color: red">*</span>
                                <select name="Bank" id="Bank3"  class="select2" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Bank as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 mt-2">
                                <b>Cheque No</b><span style="color: red">*</span>
                                <input type="text" name="Cheque" id="Cheque3" class="form-control">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                                <b>Payment Amount</b><span style="color: red">*</span>
                                <input type="text" name="Amount" id="Amount3"placeholder="0.00" class="form-control">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                                <b>Remark</b>
                                <input type="text" name="Remark" id="Remark3" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-2" id="ChequeNo" style="display:none">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 mt-2">
                                <b>Credit Card Number</b><span style="color: red">*</span>
                                <input type="text" name="Credit" id="Credit" placeholder="Credit Card Number" class="form-control">
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 mt-2">
                                <b>Expire Date</b><span style="color: red">*</span>
                                <input type="text" class="form-control" id="Expire" placeholder="dd/mm/yyyy" name="Expire">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                                <b>Payment Amount</b><span style="color: red">*</span>
                                <input type="text" name="Amount" id="Amount4" placeholder="0.00" class="form-control">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                                <b>Remark</b>
                                <input type="text" name="Remark" id="Remark4" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-4">
                        <div class="row">
                            <div class="col-4">
                            </div>
                            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                <button type="button"  onclick="window.location.href='{{ route('invoice.index') }}'" class="btn btn-secondary lift btn_modal btn-space" >
                                    Back
                                </button>
                                <button type="submit" class="btn btn-color-green lift btn_modal" >save</button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-4" style="border: 2px solid #2D7F7B;cursor: pointer; padding: 5px 10px;border-radius: 10px;">
                        <table id="Receive  " class="table display dataTable table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Invoice ID</th>
                                    <th>Proposal ID</th>
                                    <th>Company</th>
                                    <th>Issue Date</th>
                                    <th>Expiration Date</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">1</td>
                                    <td>{{$invoice->Invoice_ID}}</td>
                                    <td>{{$invoice->Quotation_ID}}</td>
                                    <td>{{$name->Company_Name}}</td>
                                    <td>{{$invoice->IssueDate}}</td>
                                    <td>{{$invoice->Expiration}}</td>
                                    <td>{{ number_format($invoice->sumpayment) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- .card end -->
            </div>
        </div>
    </form> <!-- .row end -->
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    flatpickr('#dateInput', {
        dateFormat: 'd/m/Y',
        locale: 'th' // ใช้ locale ภาษาไทยถ้าต้องการ
    });
    flatpickr('#Expire', {
        dateFormat: 'd/m/Y',
        locale: 'th' // ใช้ locale ภาษาไทยถ้าต้องการ
    });
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<script>
    document.getElementById('Filter').addEventListener('change', function() {
        const selectedValue = this.value;
        // ทำสิ่งที่คุณต้องการเมื่อมีการเปลี่ยนแปลง
        const cash = document.getElementById('cash');
        const BankTransfer = document.getElementById('BankTransfer');
        const CreditCard = document.getElementById('CreditCard');
        const ChequeNo = document.getElementById('ChequeNo');

        const Amount1 = document.getElementById('Amount1');
        const Remark1 = document.getElementById('Remark1');
        const Amount2 = document.getElementById('Amount2');
        const Remark2 = document.getElementById('Remark2');
        const Amount3 = document.getElementById('Amount3');
        const Remark3 = document.getElementById('Remark3');
        const Amount4 = document.getElementById('Amount4');
        const Remark4 = document.getElementById('Remark4');

        const Bank2 = document.getElementById('Bank2');
        const Bank3 = document.getElementById('Bank3');
        const Cheque3 = document.getElementById('Cheque3');

        const Credit = document.getElementById('Credit');
        const Expire = document.getElementById('Expire');


        if (selectedValue === 'Cash') {
            cash.style.display = 'block';
            BankTransfer.style.display= 'none';
            CreditCard.style.display= 'none';
            ChequeNo.style.display= 'none';
            Amount1.disabled = false;
            Remark1.disabled = false;
            Amount2.disabled = true;
            Remark2.disabled = true;
            Amount3.disabled = true;
            Remark3.disabled = true;
            Amount4.disabled = true;
            Remark4.disabled = true;

            Bank2.disabled = true;
            Bank3.disabled = true;
            Cheque3.disabled = true;
            Credit.disabled = true;
            Expire.disabled = true;
        } else if (selectedValue === 'BankTransfer') {
            cash.style.display = 'none';
            BankTransfer.style.display = 'block';
            CreditCard.style.display= 'none';
            ChequeNo.style.display= 'none';
            Amount1.disabled = true;
            Remark1.disabled = true;
            Amount2.disabled = false;
            Remark2.disabled = false;
            Amount3.disabled = true;
            Remark3.disabled = true;
            Amount4.disabled = true;
            Remark4.disabled = true;

            Bank2.disabled = false;
            Bank3.disabled = true;
            Cheque3.disabled = true;
            Credit.disabled = true;
            Expire.disabled = true;
        } else if (selectedValue === 'CreditCard') {

            cash.style.display = 'none';
            BankTransfer.style.display= 'none';
            CreditCard.style.display= 'block';
            ChequeNo.style.display= 'none';
            Amount1.disabled = true;
            Remark1.disabled = true;
            Amount2.disabled = true;
            Remark2.disabled = true;
            Amount3.disabled = false;
            Remark3.disabled = false;
            Amount4.disabled = true;
            Remark4.disabled = true;

            Bank2.disabled = true;
            Bank3.disabled = false;
            Cheque3.disabled = false;
            Credit.disabled = true;
            Expire.disabled = true;
        }else if (selectedValue === 'Cheque') {
            cash.style.display = 'none';
            BankTransfer.style.display= 'none';
            CreditCard.style.display= 'none';
            ChequeNo.style.display= 'block';

            Amount1.disabled = true;
            Remark1.disabled = true;
            Amount2.disabled = true;
            Remark2.disabled = true;
            Amount3.disabled = true;
            Remark3.disabled = true;
            Amount4.disabled = false;
            Remark4.disabled = false;

            Bank2.disabled = true;
            Bank3.disabled = true;
            Cheque3.disabled = true;
            Credit.disabled = false;
            Expire.disabled = false;
        }
    });
</script>
@endsection
