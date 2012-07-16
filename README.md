Silex - Kitchen Edition
=======================

See the [dedicated page on github](http://lyrixx.github.com/Silex-Kitchen-Edition/)

This project is a sample base for your silex applications.

It already embed :

* HTML5 boilerplate (http://html5boilerplate.com/)
* Twitter Bootstrap with form integration (http://twitter.github.com/bootstrap/) **version 2**
* Few extensions :
    * Assetics
    * Doctrine
    * Form
    * Monolog
    * Session
    * SymfonyBrige
    * Translation
    * Twig
    * UrlGenerator

![Homepage](https://raw.github.com/lyrixx/Silex-Kitchen-Edition/master/resources/assets/img/hp.jpg)
![Form sample](https://raw.github.com/lyrixx/Silex-Kitchen-Edition/master/resources/assets/img/form.jpg)

Installation
------------

### Without git clone

Run the following commands:

    curl -s http://getcomposer.org/installer | php
    php composer.phar create-project lyrixx/Silex-Kitchen-Edition PATH/TO/YOUR/APP
    cd PATH/TO/YOUR/APP

### With git clone

Run the following commands:

    git clone https://github.com/lyrixx/Silex-Kitchen-Edition.git PATH/TO/YOUR/APP
    cd PATH/TO/YOUR/APP
    curl -s http://getcomposer.org/installer | php
    php composer.phar install

### Then

You can edit `resources/config/prod.php` and start hacking in `src/controllers.php`

Todo
----

* Extensions

    * SwiftMailer

Tests
-----

[![Build Status](https://secure.travis-ci.org/lyrixx/Silex-Kitchen-Edition.png?branch=master)](http://travis-ci.org/lyrixx/Silex-Kitchen-Edition)

Just run `phpunit`

Sometimes, you have to run : `chmod 777 -R cache/ web/assets`,
because CLI does not run with the same user as  [mod_php|php-fpm|.*].

Help
----

* http://silex.sensiolabs.org/documentation

Licence
-------

    Copyright (C) 2012 Grégoire Pineau

    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUTOF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
    IN THE SOFTWARE.
