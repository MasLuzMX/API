# MasLuz's API

This is the official API for MasLuz's.

## How do I install it?

       clone repository
       https://github.com/MasLuzMX/API.git

## How do I use it?

The first thing to do is to instance a ```MasLuz``` class. You'll need to give a ```clientId``` and a ```clientSecret```.

### Including the Lib
Include the lib MasLuz in your project

```php
require '/MasLuz.php';
```
Start the development!

### Create an instance of MasLuz class
Simple like this
```php
$ml = new MasLuz('1234', 'a secret');
```
With this you can start working on MasLuz's APIs.

There are some design considerations worth to mention.

1. This SDK is just a thin layer on top of an http client to handle all the OAuth WebServer flow for you.

2. There is JSON parsing. this SDK will include [json](http://php.net/manual/en/book.json.php) for internal usage.

3. This SDK will include [curl](http://php.net/manual/en/book.curl.php) for internal usage.

4. If you already have the access_token you can pass in the constructor

```php
$ml = new MasLuz('1234', 'a secret', 'Access_Token', 'Refresh_Token');
```
#### Making GET calls

```php
$result = $ml->get('/customers');
```

#### Making POST calls

```php

  #this body will be converted into json for you
$body = array('foo' => 'bar', 'bar' => 'foo');

$response = $ml->post('/customers', $body);
```

#### Making PUT calls

```php

  #this body will be converted into json for you
$body = array('foo' => 'bar', 'bar' => 'foo');

$response = $ml->put('/customers', $body);
```

#### Making DELETE calls
```php
$response = $ml->delete('/customers/123')
```

## I want to contribute!

That is great! Just fork the project in github. Create a topic branch, write some code, and add some tests for your new code.

Thanks for helping!