<?php
namespace RaselSwe\Bkash;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Payment extends ConfigPayment {

    public function init( $amount )
    {
        $status = ['error' => false, 'message' => null, 'payment_url' => null];
        if(!$amount || $amount < 1){
            $status['error'] = true;
            $status['message'] = 'Invalid amount';
        }

        $header = $this->authHeaders();
        $body_data = array(
            'mode'                  => '0011',
            'payerReference'        => ' ',
            'callbackURL'           => $this->bkash_callback_url,
            'amount'                => $amount,
            'currency'              => 'BDT',
            'intent'                => 'sale',
            'merchantInvoiceNumber' => "Inv".Str::random(8)
        );
        $body_data_json = json_encode($body_data);
        $response = $this->curlWithBody('/tokenized/checkout/create',$header,'POST',$body_data_json);
        $status['payment_url'] = json_decode($response)->bkashURL;
        return $status;
    }
    public function callback(Request $request)
    {
        $allRequest = $request->all();

        if(isset($allRequest['status']) && $allRequest['status'] == 'failure')
            return ['success' => false, 'message' => 'Payment Failed !!'];

        if(isset($allRequest['status']) && $allRequest['status'] == 'cancel')
            return ['success' => false, 'message' => 'Payment Cancelled !!'];

        $response = $this->executePayment($allRequest['paymentID']);
        $res_array = json_decode($response, true);

        if(array_key_exists("statusCode",$res_array) && $res_array['statusCode'] != '0000')
            return ['success' => false, 'message' => $res_array['statusMessage'] ];

        if(array_key_exists("message", $res_array)){
            sleep(1);
            $query = $this->queryPayment($allRequest['paymentID']);
            $query_array = json_decode($query, true);
            if(isset($query_array['trxID'])) return ['success' => true, 'message' => 'Success', 'response' => $query_array];
        }

        if(isset($res_array['trxID'])) return ['success' => true, 'message' => 'Success', 'response' => $res_array];
        return ['success' => false, 'message' => 'Unknown Error occured !!'];
    }


    public function refundPayment( $data = [] )
    {
        $header =$this->authHeaders();
        $body_data = array(
            'paymentID' => @$data['paymentID'],
            'amount' => @$data['amount'],
            'trxID' => @$data['trxID'],
            'sku' => 'sku',
            'reason' => @$data['reason']
        );

        $body_data_json = json_encode($body_data);
        $response = $this->curlWithBody('/tokenized/checkout/payment/refund',$header,'POST',$body_data_json);
        $res_array = json_decode($response, true);
        if(isset($res_array['refundTrxID'])) return ['success' => true, 'response' => $res_array];
        return ['success' => false, 'response' => $res_array];
    }


}
