@extends('layouts.test')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .Usertablecontainer {
        width: 90%;
        display: block;
        margin: auto;
        margin-top: 40px;
        background-color: white;
        padding: 5% 10%;
    }
    .titleh1 {
        font-size: 32px;
    }
    .Usertable{
        display: block;
        margin: auto;
        width: 100%;
        border-style: solid;
        border-radius: 8px;
        border-width: 1px;
        border-color: #9a9a9a;
        background-color: white;
        padding: 10px;
        margin-top: 40px;

    }
    .image-read-button {
            background: url('{{ asset('assets2/images/analytics.png') }}') no-repeat center center;
            background-size: cover;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
        }
</style>
<div class="Usertablecontainer">
    <div class="row">
        <div class="titleh1 col-lg-6 col-md-6 col-sm-6">
            <h1>Data Massage to Boss</h1>
        </div>
    </div>
    <div>
        <table  class="table table-hover">
            <thead class="table-secondary">
              <tr>
                <th scope="col" style="width: 10%">วันที่ส่ง</th>
                <th scope="col"style="width: 50%;text-align: left;" >ชื่อบริษัท</th>
                <th scope="col"style="width: 15%">ผู้ส่งเรื่อง</th>
                <th scope="col"style="width: 15%">ดูข้อมูล</th>
                {{-- ชื่อ user ที่กดรับทราบ ถ้าไม่ให้รอ --}}
                <th scope="col"style="width: 10%">สถานะ </th>
              </tr>
            </thead>
            <tbody>
                @if (!empty($Company_massage))
                    @foreach ($Company_massage as $key => $item)
                    <tr>
                        <th>{{ $item->created_at->format('d/m/Y') }}</th>
                        <td style="width: 50%;text-align: left;">{{ $item->Company_Name }}</td>
                        <td >{{ @$item->member->First_name }} {{ @$item->member->Last_name }}</td>
                        <td>
                            <button type="button" class="image-read-button" onclick="window.location.href = '{{ url('/Freelancer/boss/view/data/'.$item->id) }}'"></button>
                        </td>

                        @if ($item->status == 1)
                            <td>
                                <label style=" font-size: 16px; color:#06ac3b">อนุมัติ </label>
                            </td>
                        @elseif(($item->status == 2))
                            <td>
                                {{ $item->Operated_by }}
                            </td>
                        @else
                            <td>
                                <label style=" font-size: 16px; color:#ccc">รอตรวจสอบ </label>
                            </td>
                        @endif

                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<script>
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
</script>
@endsection
