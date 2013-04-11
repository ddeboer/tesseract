[![Build Status](https://travis-ci.org/ddeboer/tesseract.png?branch=master)](https://travis-ci.org/ddeboer/tesseract)

Tesseract: a wrapper for the Tesseract OCR engine
================================================

A small PHP >=5.3 library that makes working with the open source [Tesseract OCR engine](https://code.google.com/p/tesseract-ocr/) 
easier.

Installation
------------

You need a working Tesseract installation. For more information about 
installation and adding language support, see Tesseract's [README](https://code.google.com/p/tesseract-ocr/wiki/ReadMe).

Then install this library, which is available on [Packagist](http://packagist.org/packages/ddeboer/tesseract),
through [Composer](http://getcomposer.org/):

    $ composer require ddeboer/tesseract:1.0

Usage
-----

If the `tesseract` binary is in your path, just do:

```php
use Ddeboer\Tesseract\Tesseract;

$tesseract = new Tesseract();
```

Otherwise, construct Tesseract with the path to the binary:

```php
$tesseract = new Tesseract('/usr/local/bin/tesseract');
```

Get version and supported languages information:

```php
$version = $tesseract->getVersion();

$languages = $tesseract->getSupportedLanguages();
```

Perform OCR on an image file:

```php
$text = $tesseract->recognize('myfile.tif');
```

Optionally, specify the language(s) as second argument:

```php
$text = $tesseract->recognize('myfile.tif', array('nld', 'eng'));
```

And specify Tesseract’s page seg mode as third argument:

```php
$text = $tesseract->recognize('myfile.tif', null, Tesseract::PAGE_SEG_MODE_AUTOMATIC_OSD);
```
