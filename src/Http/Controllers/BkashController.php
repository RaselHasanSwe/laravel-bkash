<?php

namespace RaselSwe\Bkash\Http\Controllers;

use App\Http\Controllers\Controller;
use RaselSwe\Bkash\Payment;
use Illuminate\Http\Request;

class BkashController extends Controller
{
    public function pay( Payment $bkashPayment )
    {
        $paymentUrl = $bkashPayment->init(200);
        if($paymentUrl['error'] === true){
            //handle error with view page
            dd($paymentUrl);
        }
        return redirect($paymentUrl['payment_url']);
    }

    public function confirmPayment( Request $request, Payment $bkashPayment )
    {
        $executePayment = $bkashPayment->callback($request);
        if($executePayment['success'] === true){
            //$paymentID = $executePayment['response']['paymentID'];
            //$trxID = $executePayment['response']['trxID'];
            // MAKE DB OPERATION HERE
            dd($executePayment);
        }else{
            //handle payment cancel with message
            // $message = $executePayment['message']
            dd($executePayment);
        }
    }

    public function refundPayment( Request $request, Payment $bkashPayment )
    {
        $body_data = array(
            'paymentID' => $request->paymentID ?? 'TR0011drkTg341689422678392',
            'amount' => $request->amount ?? 1,
            'trxID' => $request->trxID ?? 'AGF20BG9YW',
            'sku' => 'sku',
            'reason' => $request->reason ?? 'Test Reason'
        );

        $refund = $bkashPayment->refundPayment($body_data);
        if($refund['success'] === true){
            // DB operation or your logic
            dd($refund);
        }else{
            //handle cancel refund
            dd($refund);
        }

    }
}
