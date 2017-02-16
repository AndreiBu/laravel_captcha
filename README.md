CAPTCHA  
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
          {!! Captcha::create_cod(); !!}
          or
          {!! app('captcha')->create_cod(); !!}

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


## Contribute

https://github.com/AndreiBu/laravel_captcha/pulls
