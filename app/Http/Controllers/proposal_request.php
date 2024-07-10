<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dummy_quotation;
use Illuminate\Support\Facades\DB;
use App\Models\document_quotation;
use App\Models\Quotation;
class proposal_request extends Controller
{
    public function index()
    {
        $proposal = dummy_quotation::where('status_document', 2)
            ->groupBy('Company_ID')
            ->select('dummy_quotation.*',DB::raw("COUNT(DummyNo) as COUNTDummyNo"))
            ->get();
        return view('proposal_req.index',compact('proposal'));
    }
    public function view($id)
    {
        $proposal = dummy_quotation::where('Company_ID',$id)->get();
        return view('proposal_req.view',compact('proposal'));
    }
    public function Approve(Request $request){

    }
}
