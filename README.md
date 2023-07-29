# Laravel Bkash Payment


Laravel Bkash is a Laravel package that makes it easy to take bkash payment from customer 
and also refund payment to bkash account.

- Developer can integrate bkash payment very easy to his laravel application
- bkash payment and refund payment available in this package.


## Installing Bkash Payment

The recommended way to install Bkash

```bash
composer require raselswe/bkash
```


## Setup Pakage

```bash
php artisan vendor:publish --provider="RaselSwe\Bkash\BkashServiceProvider"
```
You will get bkash.php file in your config directory.


## Config .env or bkash.php file with required information

```php

// COPY AND PAST THIS CODE CONFIG CODE TO .env FILE OR SET CREDENTIAL TO config/bkash.php

BKASH_SENDBOX_BASE_URL = 'https://tokenized.*******'
BKASH_LIVE_BASE_URL = 'https://tokenized.pay.*********' 
BKASH_USER_NAME = '****************'
BKASH_PASSWORD = '******************'
BKASH_APP_KEY = '******************'
BKASH_APP_SECRET ='*************'
BKASH_CALLBACK_URL = 'http://127.0.0.1:8000/bkash/execute-payment'
BKASH_PAYMENT_MODE = 'sendbox'  // in live mode set here 'live'


// TEST YOUR BKASH PAYMENT -- EXAMPLE CODE -- web.php

Route::get('/bkash/pay', [BkashPaymentController::class, 'pay']);
Route::get('/bkash/execute-payment', [BkashPaymentController::class, 'confirmPayment']); // This is callback url
Route::get('/bkash/refund-payment', [BkashPaymentController::class, 'refundPayment']);


// BkashPaymentController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use RaselSwe\Bkash\Payment;
use Illuminate\Http\Request;

class BkashPaymentController extends Controller
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


```

## License

LaravelBkash is made available under the MIT License (MIT).
