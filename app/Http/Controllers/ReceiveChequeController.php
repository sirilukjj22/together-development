<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReceiveChequeController extends Controller
{
    public function index()
    {

        return view('recevie_cheque.index');
    }
}
