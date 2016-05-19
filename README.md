# Authentication Signature generator for Walmart APIs

Walmart uses a signature calculation algorithm for authenticating API 
calls, it is fairly complicated so this library is intended to make it 
as easy as calling a function. 

## Build Status
[![Build Status](https://travis-ci.org/fillup/walmart-auth-signature-php.svg?branch=develop)](https://travis-ci.org/fillup/walmart-auth-signature-php) [![Coverage Status](https://coveralls.io/repos/github/fillup/walmart-auth-signature-php/badge.svg?branch=develop)](https://coveralls.io/github/fillup/walmart-auth-signature-php?branch=develop)

## Installation

The easiest way to install this library is using Composer. Simply add 
the following to your composer.json file:

    "fillup/walmart-auth-signature-php": "dev-master"
        
Or run ```composer require fillup/walmart-auth-signature-php:dev-master```. 
This assumes you have composer installed and available in your path 
as ```composer```.

## Versioning
This library uses semantic versioning as well as a Git Flow process
for development. Usage of ```dev-master``` should be safe as the 
```master``` branch is considered *production ready*.

## Usage
You can either use ```Walmart\Auth\Signature``` class in an object form, 
or by calling static methods on it.

### Object interface
If you need to make repeated API calls you can instantiate a Signature 
object and simply call the ```getSignature``` method to get a new 
signature over and over:

```php
<?php

use Walmart\Auth\Signature as AuthSignature;

$authSignature = new AuthSignature($consumerId, $privateKey, $requestUrl, $requestMethod);
$signatureString = $authSignature->getSignature();

// Make your call

// Get a new signature for a new call
$signatureString = $authSignature->getSignature(null,$newUrl);
```


### Static method interface
Sometimes just calling a static method is easier or more elegant, 
here you go:

```php
<?php

use Walmart\Auth\Signature as AuthSignature;

$signatureString = AuthSignature::calculateSignature($consumerId, $privateKey, $requestUrl, $requestMethod);

// Make your call

// Get a new signature for a new call
$signatureString = AuthSignature::calculateSignature($consumerId, $privateKey, $requestUrl, $requestMethod);
```

## Contributing
I appreciate issue reports and pull requests, so if you see opportunity 
for improvement, please let us know and send us a pull request.

## License
The MIT License (MIT)

Copyright (c) 2015 Phillip Shipley

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


