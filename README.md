Solve Media Captcha for Laravel
===============================

Package integration of SolveMedia captcha for Laravel

Tested on Laravel 5.6

## Installation

```
composer require rubensrocha/solvemedia-captcha
```

## Laravel 5

### Setup

**_NOTE_** This package supports the auto-discovery feature of Laravel 5.5, So skip these `Setup` instructions if you're using Laravel 5.5.

In `app/config/app.php` add the following :

1- The ServiceProvider to the providers array :

```php
Rubensrocha\SolveMediaCaptcha\SolveMediaCaptchaServiceProvider::class,
```

2- The class alias to the aliases array :

```php
'SolveMediaCaptcha' => Rubensrocha\SolveMediaCaptcha\Facades\SolveMediaCaptcha::class,
```

3- Publish the config file

```ssh
php artisan vendor:publish --provider="Rubensrocha\SolveMediaCaptcha\SolveMediaCaptchaServiceProvider"
```

### Configuration

Add these fields in **.env** file :

```
SOLVEMEDIA_CKEY="your_public_key"
SOLVEMEDIA_VKEY="your_verification_key"
SOLVEMEDIA_HKEY="your_authentication_key"
SOLVEMEDIA_SSL=TRUE
```

(You can obtain them from [here]( https://portal.solvemedia.com )

#### Display CAPTCHA


```php
{!! SolveMediaCaptcha::display() !!}


#### Validation

Add `'adcopy_response' => 'required|solvemediacaptcha'` to rules array :

```php
$validate = Validator::make(Input::all(), [
	'adcopy_response' => 'required|solvemediacaptcha'
]);

```

##### Custom Validation Message

Add the following values to the `custom` array in the `validation` language file :

```php
'custom' => [
    'adcopy_response' => [
        'required' => 'Please verify that you are not a robot.',
        'solvemediacaptcha' => 'Captcha error! try again later or contact site admin.',
    ],
],
```

Then check for captcha errors in the `Form` :

```php
@if ($errors->has('adcopy_response'))
    <span class="help-block">
        <strong>{{ $errors->first('adcopy_response') }}</strong>
    </span>
@endif
```

## Packages used as reference for creating this package.

[anhskohbo/no-captcha] https://github.com/anhskohbo/no-captcha
[traderinteractive/solvemedia-client-php] https://github.com/traderinteractive/solvemedia-client-php

## Contribute

https://github.com/rubensrocha/solvemedia-captcha/pulls
