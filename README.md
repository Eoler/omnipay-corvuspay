# Omnipay: CorvusPay

**CorvusPay driver for the Omnipay payment processing PHP library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.6+. This package implements [CorvusPay](https://www.corvuspay.com/) 
support for Omnipay.

## Installation

Omnipay is installed via [Composer](https://getcomposer.org/). To install, add it to your
`composer.json` file:

```json
{
    "require": {
        "eoler/omnipay-corvuspay": "~1.0"
    }
}
```

And run composer to update your dependencies:

```
composer update
```

Or you can simply run:

```
composer require eoler/omnipay-corvuspay
```

## Basic Usage

1. Use Omnipay gateway class:

```php
    use Omnipay\Omnipay;
```

2. Initialize CorvusPay gateway:

```php
    $gateway = Omnipay::create('CorvusPay');
    $gateway->initialize([
        'apiKey'   => env('API_KEY'),
        'storeId'  => env('STORE_ID'),
        'testMode' => env('APP_DEBUG'),
        'language' => \App::getLocale(),
    ]);
```

3. Call purchase, it will send user to CorvusPay checkout endpoint:

```php
    $response = $gateway->purchase([
        'transactionId' => $order->id,
        'amount'      => $order->amount,
        'currency'    => $order->currencyCode,
        'description' => $order->products->list(),
        'email'       => $order->customer->email,
    ])->send();
    return $response->getRedirectResponse();
```

4. Create a webhook to handle the callback request at your `RESULT_URL` and catch notification with:

```php
    $gateway = Omnipay::create('CorvusPay');
    $gateway->initialize([
        'apiKey'   => env('API_KEY'),
        'storeId'  => env('STORE_ID'),
        'testMode' => env('APP_DEBUG'),
        'language' => \App::getLocale(),
    ]);

    $purchase = $gateway->completePurchase()->send();   
    if ($purchase->isSuccessful()) {
        // Bookkeeping: save completed status, etc.
    }
```

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/eoler/omnipay-corvuspay/issues),
or better yet, fork the library and submit a pull request.
