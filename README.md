# Laravel Bkash Payment


Laravel Bkash is a Laravel package that makes it easy to take bkash payment from customer 
and also refund payment to bkash account.

- Developer can integrate bkash payment very easyly to his laravel application
- bkash payment and refund payment available in this package.


## Installing Bkash Payment

The recommended way to install Bkash

```bash
composer require raselswe/bkash
```


## Setup Pakage

```bash
php artisan vendor:publish 
```
publish raselswe/bkash vendor file. you will get bkash.php file in your config directory.


## Config .env or bkash.php file with required information

BKASH_SENDBOX_BASE_URL = 'https://tokenized.*******'
BKASH_LIVE_BASE_URL = 'https://tokenized.pay.*********'
BKASH_USER_NAME = '****************'
BKASH_PASSWORD = '******************'
BKASH_APP_KEY = '******************'
BKASH_APP_SECRET ='*************'
BKASH_CALLBACK_URL = '**************'
BKASH_PAYMENT_MODE = 'sendbox'  // in live mode set here 'live'


```php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RaselSwe\Bkash\Payment;

class BkashController extends Controller
{
    public function index( Payment $bkashPayment )
    {
        $paymentUrl = $bkashPayment->init(200);
        if($paymentUrl['error'] === true){
            //handle error with view page
            //dd($paymentUrl)
        }
        return redirect($paymentUrl['payment_url']);
    }

    public function callback( Request $request, Payment $bkashPayment )
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

}

```

## License

LaravelBkash is made available under the MIT License (MIT).
