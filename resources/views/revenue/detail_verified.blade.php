@extends('layouts.test')

@section('content')
    <div class="page_target">
        <a href="javascript:history.back(1)" style="color: #2D7F7B; font-size: 20px; font-weight: 500;">Revenue</a> /
        {{ $title ?? '' }} <br>
        <h1>{{ $title ?? '' }}</h1>
    </div>
    <div class="back">
        <a href="javascript:history.back(1)"><button type="button">ย้อนกลับ</button></a>
    </div>

    <div class="search">
        <div>
            <table id="example" class="display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>วันที่</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_verified as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != '' ? $item->date_into : $item->date)->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>

    <link rel="stylesheet" href="dataTables.dataTables.css">

    <script>
        $(document).ready(function() {

            new DataTable('#example', {});

        });
    </script>
@endsection
