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

Add Route to reload captcha `routes/web.php`.

```
    Route::get('/captcha/{key?}', function($key='') {
        $json=array('img'=>Captcha::img($key),'key'=>Captcha::md5());
        return json_encode($json);
    });    
```

### Configuration
 you must copy in  `/public/fonts/` ttf font and specify its path in the configuration `CAPTCHA_FONT`
  
(optional) Add key in .env file (without brackets):


```php
    CAPTCHA_MIN=[0-999 999 999]
    CAPTCHA_MAX=[0-999 999 999]
    CAPTCHA_WIDTH=[0-1000]
    CAPTCHA_HEIGHT=[0-1000]
    CAPTCHA_TIME=[60-3600]
    CAPTCHA_GARBAGE=[0-100]
    CAPTCHA_REDRAW=[1-10]
    CAPTCHA_FONT='/fonts/times.ttf'

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
		{!! Captcha::img(); !!}

```

##### display CAPTCHA + reload

```php
                       <div class="pwd_reset_captcha">
                                @if ($errors->has('captcha_cod'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('captcha_cod') }}</strong>
                                    </span>
                                @endif
                     		{!! Captcha::create_cod(); !!}
                            <input name="captcha_md5" type="hidden" value="{!! Captcha::md5(); !!}">
                            <input name="captcha_cod" type="text" value="">
                            {!! Captcha::img(); !!}
                            <a href="#" onclick="captcha_redraw()">reload</a>
                            <script>
                            function captcha_redraw(key){
                                var key=$('.pwd_reset_captcha input[name=captcha_md5]').val();
                                $.get('/captcha/'+key,'', function (data){
                                    try{
                                       var json=JSON.parse(data);
                                       $('.pwd_reset_captcha img.captcha').replaceWith(json.img);
                                       if(json.key!=''){$('.pwd_reset_captcha input[name=captcha_md5]').val(json.key);}
                                    }
                                    catch(e){console.log(e);}
                                });
                            }                            
                            </script>
 						</div>

```


##### Validation

Add `'captcha_cod' => 'required|captcha'` to rules array.

```php
$validate = Validator::make(Input::all(), [
	'captcha_cod' => 'required|captcha'
]);

```


## Contribute

https://github.com/AndreiBu/laravel_captcha/pulls
