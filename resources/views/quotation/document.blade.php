@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Log Document Proposal</div>
                </div>
                <div class="col-auto">
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-PDF" role="tab" onclick="nav($id='nav1')">Log PDF</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Log" onclick="nav($id='nav2')" role="tab">Log</a></li>{{--QUOTAION--}}
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-PDF" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposalLogTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th data-priority="1">Quotation ID</th>
                                                    <th class="text-center"data-priority="1">Quotation Type</th>
                                                    <th class="text-center">Correct No</th>
                                                    <th class="text-center">Created Date</th>
                                                    <th class="text-center">Export</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($log))
                                                    @foreach ($log as $key => $item)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $item->Quotation_ID }}</td>
                                                        <td class="text-center">{{ $item->QuotationType }}</td>
                                                        <td class="text-center">{{ $item->correct}}</td>
                                                        <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                        <td class="text-center">
                                                            @if ($item->correct == $correct)
                                                                @if ($correct == 0)
                                                                    <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                                        <i class="fa fa-print"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ asset($path.$item->Quotation_ID.'-'.$correct.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                                        <i class="fa fa-print"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                                    <i class="fa fa-print"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Log" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposal-LogTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th  class="text-center">No</th>
                                                    <th >Category</th>
                                                    <th  class="text-center">Type</th>
                                                    <th  class="text-center">Created_by</th>
                                                    <th  class="text-center">Created Date</th>
                                                    <th  class="text-center">Content</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($logproposal))
                                                    @foreach($logproposal as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">{{$key +1 }}</td>
                                                        <td style="text-align: left;">{{$item->Category}}</td>
                                                        <td style="text-align: center;">{{$item->type}}</td>
                                                        <td style="text-align: center;">{{@$item->userOperated->name}}</td>
                                                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                        @php
                                                            // แยกข้อมูล content ออกเป็น array
                                                            $contentArray = explode('+', $item->content);
                                                        @endphp
                                                        <td style="text-align: left;">

                                                            <b style="color:#0000FF ">{{$item->Category}}</b>
                                                            @foreach($contentArray as $contentItem)
                                                                <div style="white-space:wrap">{{ $contentItem }}</div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row mt-5">
                                <div class="col-4"></div>
                                <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                    <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('Proposal.index') }}'">
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    <script>
        function nav(id) {
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
    </script>

@endsection
