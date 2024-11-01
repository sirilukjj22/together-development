@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Audit Hotel & Water Park Revenue by date</div>
                </div>
                <div class="col-auto">
                        <a href="#" type="button" class="btn btn-color-green text-white lift btn_modal">Print Report</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 10vh;">
                            <form class="row g-3">
                                <div class="col-md-12">
                                    <h5>Search</h5>
                                </div>
                                <div class="col-12 d-flex flex-row gap-3">
                                    <label for="TextInput1" class="form-label">Filter by</label>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <button class="btn btn-secondary">Date</button>
                                        <button class="btn btn-outline-secondary">Month</button>
                                        <button class="btn btn-outline-secondary">Year</button>
                                        <button class="btn btn-outline-secondary">Custom Rang</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="TextInput1" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="TextInput1">
                                </div>
                                <div class="col-md-4">
                                    <label for="TextInput2" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="TextInput2">
                                </div>
                                <div class="col-md-8 d-flex flex-row gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">All</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2">Verified</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDisabled">
                                        <label class="form-check-label" for="flexRadioDisabled">Unverified</label>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>                                                     
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 70vh;">
                            <table id="masterTable" class="example ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th data-priority="1">#</th>
                                        <th data-priority="2">Date</th>
                                        <th data-priority="3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>02/10/2024</td>
                                        <td>
                                            <span class="badge bg-danger">Unverified</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>03/10/2024</td>
                                        <td>
                                            <span class="badge bg-success">Verified</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>04/10/2024</td>
                                        <td>
                                            <span class="badge bg-success">Verified</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
        
@endsection
