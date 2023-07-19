<?php

namespace RaselSwe\Bkash;

class ConfigPayment
{
    protected $website_url;
    protected $bkash_sendbox_base_url;
    protected $bkash_live_base_url;
    protected $bkash_user_name;
    protected $bkash_password;
    protected $bkash_app_key;
    protected $bkash_app_secret;
    protected $bkash_callback_url;
    protected $bkash_payment_mode;

    public function __construct()
    {
        $this->website_url = config('bkash.website_url');
        $this->bkash_sendbox_base_url = config('bkash.bkash_sendbox_base_url');
        $this->bkash_live_base_url = config('bkash.bkash_live_base_url');
        $this->bkash_user_name = config('bkash.bkash_user_name');
        $this->bkash_password = config('bkash.bkash_password');
        $this->bkash_app_key = config('bkash.bkash_app_key');
        $this->bkash_app_secret = config('bkash.bkash_app_secret');
        $this->bkash_callback_url = config('bkash.bkash_callback_url');
        $this->bkash_payment_mode = config('bkash.bkash_payment_mode');
    }

    protected function curlWithBody($url, $header, $method, $body_data_json)
    {
        $bkash_base_url = $this->bkash_payment_mode === 'sendbox' ? $this->bkash_sendbox_base_url : $this->bkash_live_base_url;
        $curl = curl_init($bkash_base_url.$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    protected function grant()
    {
        $header = array(
            'Content-Type:application/json',
            'username:'.$this->bkash_user_name,
            'password:'.$this->bkash_password
        );
        $header_data_json = json_encode($header);
        $body_data = array('app_key'=> $this->bkash_app_key, 'app_secret'=>$this->bkash_app_secret);
        $body_data_json = json_encode($body_data);
        $response = $this->curlWithBody('/tokenized/checkout/token/grant',$header,'POST',$body_data_json);
        $token = json_decode($response)->id_token;
        return $token;
    }

    protected function authHeaders(){
        return array(
            'Content-Type:application/json',
            'Authorization:' .$this->grant(),
            'X-APP-Key:'.$this->bkash_app_key
        );
    }
    protected function executePayment( $paymentID )
    {
        $header = $this->authHeaders();
        $body_data = array('paymentID' => $paymentID);
        $body_data_json = json_encode($body_data);
        $response = $this->curlWithBody('/tokenized/checkout/execute', $header,'POST', $body_data_json);
        return $response;
    }
    protected function queryPayment( $paymentID )
    {
        $header =$this->authHeaders();
        $body_data = array('paymentID' => $paymentID);
        $body_data_json=json_encode($body_data);
        $response = $this->curlWithBody('/tokenized/checkout/payment/status',$header,'POST',$body_data_json);
        return $response;
    }
}
