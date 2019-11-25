## Collection of RAWG API Web Services for Laravel 6
Provides a convenient way of setting up and making requests to the RAWG API from a [Laravel](http://laravel.com/) application. 
For RAWG endpoint documentation, rate limits and licencing please visit the [RAWG API Docs](https://api.rawg.io/docs).


Dependency
------------
* [PHP cURL](http://php.net/manual/en/curl.installation.php)
* [PHP 7](http://php.net/)


Installation
------------

Install the package via Composer:

```php
composer require dherran/laravel-rawg
```

Configuration
------------

Publish the configuration file using **`php artisan vendor:publish --provider="Rawg\RawgServiceProvider"`** or simply copy the package configuration file and paste it into **`config/rawg.php`**

Open the config file **`config/rawg.php`** and add your app User Agent.
```php
    /*
    |----------------------------------
    | User Agent
    |------------------------------------
    */
    
    'user_agent' => 'Stay Meta',
```

As per the RAWG API Docs: Every API request should have a User-Agent header with your app name. If you don’t provide it, we may ban your requests.


Usage
------------

Here is an example of making request to Games endpoint:
```php
$response = \Rawg::load('games')->setParams([
                'page' => 1,
                'page_size' => 40,
                'ordering' => '-rating',
            ])->get();
```

Or finding details for a specific game:
```php
$response = \Rawg::load('games/{id}')->setParams([
                'id' => 86,
            ])->get();
```

The principle is that the load method takes the endpoint as provided by RAWG.

Available methods
------------

* [`load( $endpoint )`](#load)
* [`setParamByKey($key, $value)`](#setParamByKey)
* [`setParams($parameters)`](#setParams)
* [`get()`](#get)
* [`get($key)`](#get)

---

<a name="load"></a>
**`load( $serviceName )`** - prepare the endpoint name

Accepts string as parameter. You can find all endpoints in the RAWG API Docs.
Returns a reference to itself.

```php

\Rawg::load('publishers') 
... 

```

---

<a name="setParamByKey"></a>
**`setParamByKey( $key, $value )`** - set the request parameter using key:value pair

Accepts two parameters:
* `key` - body parameter name
* `value` - body parameter value 

Deeply nested arrays can use 'dot' notation to assign values.  
Returns a reference to itself.

```php
$endpoint = \Rawg::load('publishers')
   ->setParamByKey('page', 3)
   ->setParamByKey('page_size', 10)
    ...
```

---

<a name="setParams"></a>
**`setParams( $parameters)`** - set all request parameters at once

Accepts and array of parameters  
Returns a reference to itself.

```php
$response = \Rawg::load('games')
                ->setParam([
                   'search' => 'monster',
                   'tags' => 'multiplayer',
                ])
...
```

---

<a name="get"></a>
* **`get()`** - performs a RESTful request via GET
* **`get($key)`** - filter downs the request result. Dot notation is supported here

```php
$response = \Rawg::load('games')
                ->setParamByKey('search', 'monster')
                 ->get();
```

Example with `$key` parameter

```php
$response = \Rawg::load('games')
                ->setParamByKey('search', 'monster')
                 ->get('results');
```

Support
-------

[Please open an issue on GitHub](https://github.com/dherran/laravel-rawg/issues)
