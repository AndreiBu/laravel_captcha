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

##### create CAPTCHA cod

```php
          {!! Captcha::create_cod(); !!}
          or
          {!! app('captcha')->create_cod(); !!}

```

##### display CAPTCHA 

```php
        {!! Captcha::create_cod(); !!}
		<input name="captcha_md5" type="hidden" value="{!! Captcha::md5(); !!}">
		<input name="captcha_cod" type="text" value="">
		<img src="{!! Captcha::img(); !!}">

```


##### Validation

Add `'g-recaptcha-response' => 'required|captcha'` to rules array.

```php
$validate = Validator::make(Input::all(), [
	'captcha_cod' => 'required|captcha'
]);

```


## Contribute

https://github.com/AndreiBu/laravel_captcha/pulls
