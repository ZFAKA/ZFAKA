## WriteiniFile

Write-ini-file php library for create, remove, erase, add, and update ini file.

[![Build Status](https://travis-ci.org/Magicalex/WriteiniFile.svg)](https://travis-ci.org/Magicalex/WriteiniFile)
[![Coverage Status](https://coveralls.io/repos/github/Magicalex/WriteiniFile/badge.svg?branch=master)](https://coveralls.io/github/Magicalex/WriteiniFile?branch=master)
[![StyleCI](https://styleci.io/repos/36994392/shield?branch=master)](https://styleci.io/repos/36994392)
[![Latest Stable Version](https://poser.pugx.org/magicalex/write-ini-file/v/stable)](https://packagist.org/packages/magicalex/write-ini-file)
[![Total Downloads](https://poser.pugx.org/magicalex/write-ini-file/downloads)](https://packagist.org/packages/magicalex/write-ini-file)
[![Latest Unstable Version](https://poser.pugx.org/magicalex/write-ini-file/v/unstable)](https://packagist.org/packages/magicalex/write-ini-file)
 [![License](https://poser.pugx.org/magicalex/write-ini-file/license)](https://packagist.org/packages/magicalex/write-ini-file)

## Installation

Use composer for install this library.

```bash
$ composer require magicalex/write-ini-file:1.2.*
```

## Usage

```php
<?php

require 'vendor/autoload.php';

use WriteiniFile\WriteiniFile;

$data = [
    'fruit' => ['orange' => '100g', 'fraise' => '10g'],
    'legume' => ['haricot' => '20g', 'oignon' => '100g'],
    'jus' => ['orange' => '1L', 'pomme' => '1,5L', 'pamplemousse' => '0,5L'],
];

// demo create ini file
$a = new WriteiniFile('file.ini');
$a->create($data);
$a->add([
    'music' => ['rap' => true, 'rock' => false]
]);
$a->rm([
    'jus' => ['pomme' => '1,5L']
]);
$a->update([
    'fruit' => ['orange' => '200g'] // 100g to 200g
]);
$a->write();

echo '<pre>'.file_get_contents('file.ini').'</pre>';

/* output file.ini
[fruit]
orange = "200g"
fraise = "10g"

[legume]
haricot = "20g"
oignon = "100g"

[jus]
orange = "1L"
pamplemousse = "0,5L"

[music]
rap = 1
rock = 0
*/

$b = new WriteiniFile('file.ini');
$b->erase();
$b->write();

// file.ini -> empty
```

## Contributing

To run the unit tests:

```bash
$ composer install
$ php vendor/bin/phpunit
```

## License

The WriteiniFile php library is released under the GNU General Public License v3.0.

https://github.com/Magicalex/WriteiniFile/blob/master/LICENSE.md
