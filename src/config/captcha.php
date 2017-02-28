<?php

return [
    'min' => env('CAPTCHA_MIN', 1000),
    'max' => env('CAPTCHA_MAX', 999999),
    'width' => env('CAPTCHA_WIDTH', 180),
    'height' => env('CAPTCHA_HEIGHT', 50),
    'life_time' => env('CAPTCHA_TIME', 360),
    'garbage' => env('CAPTCHA_GARBAGE', 50),
    'redraw' => env('CAPTCHA_REDRAW', 5),
];
