<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\confirmation_request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\log_company;
class confirmationrequest extends Controller
{
    public function sendRequest(Request $request){
        try {
            $save = new confirmation_request();
            $save->requester_id = auth()->id();
            $save->status = '1';
            $save->request_time = now();
            $save->expiration_time = now()->addMinutes(5);
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        return response()->json(['success' => true, 'request_id' => $save->id]);

    }
    public function cancelRequest($id){
        $deleted = confirmation_request::where('requester_id', $id)->delete();
        return response()->json(['success' => $deleted > 0]);
    }
    public function checkConfirmationStatus($id)
    {
        $confirmationRequest = confirmation_request::findOrFail($id);
        return response()->json(['status' => $confirmationRequest->status]);
    }
    public function showConfirmPage($id)
    {
        try {
            $save = confirmation_request::find($id);
            $save->confirmer_id = auth()->id();
            $save->status = '2';
            $save->confirmed_at = now();
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }

    }
    public function showCancelPage($id)
    {
        try {
            $save = confirmation_request::find($id);
            $save->confirmer_id = auth()->id();
            $save->status = '0';
            $save->confirmed_at = now();
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
    }
}
