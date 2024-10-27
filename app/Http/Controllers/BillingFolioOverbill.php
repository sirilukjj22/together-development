<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingFolioOverbill extends Controller
{
    public function index(){
        return view('billingfolio.overbill.index');
    }
}
