CAPTCHA reCAPTCHA 
==========


## Installation

```
composer require andreibu/laravel_captcha
```

## Laravel 5

### Setup

Add ServiceProvider to the providers array in `config/app.php`.

```

   'providers' => [
    ...
	AndreiBu\laravel_captcha\CaptchaServiceProvider::class,
	
	],
	
   'aliases' => [
    ...
    
	'Captcha' => AndreiBu\laravel_captcha\Facades\Captcha::class,
	],
	
```

### Configuration


```
[]
```

### Usage

##### Display CAPTCHA

```php
{!! app('captcha')->display(); !!}
```

With custom attributes and language support:

```
{!! app('captcha')->display($attributes = [], $lang = null); !!}
```

##### Validation

Add `'g-recaptcha-response' => 'required|captcha'` to rules array.

```php

$validate = Validator::make(Input::all(), [
	'g-recaptcha-response' => 'required|captcha'
]);

```

### Testing

When using the [Laravel Testing functionality](http://laravel.com/docs/5.1/testing), you will need to mock out the response for the captcha form element. To do this:

1) Setup NoCaptcha facade in config/app.conf

```php
'NoCaptcha' => 'Anhskohbo\NoCaptcha\Facades\NoCaptcha'
```

2) For any form tests involving the captcha, you can then mock the facade behaviour:

```php
// prevent validation error on captcha
NoCaptcha::shouldReceive('verifyResponse')
    ->once()
    ->andReturn(true);
// provide hidden input for your 'required' validation
NoCaptcha::shouldReceive('display')
    ->zeroOrMoreTimes()
    ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');
```

You can then test the remainder of your form as normal.

## Without Laravel

Checkout example below:

```php
<?php

require_once "vendor/autoload.php";

$secret  = '';
$sitekey = '';
$captcha = new \Anhskohbo\NoCaptcha\NoCaptcha($secret, $sitekey);

if ( ! empty($_POST)) {
    var_dump($captcha->verifyResponse($_POST['g-recaptcha-response']));
    exit();
}

?>

<form action="?" method="POST">
    <?php echo $captcha->display(); ?>
    <button type="submit">Submit</button>
</form>

```

## Contribute

https://github.com/anhskohbo/no-captcha/pulls
