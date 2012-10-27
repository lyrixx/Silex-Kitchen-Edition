---
layout: page
title : Silex kitchen Sink Edition
tagline: A sample/base silex applications
---
{% include JB/setup %}

What is it
----------

This project is a sample or a bootstrap silex applications.
You can use it for your next php application.

### HTML / CSS / Javascripts are poweredby:

* [HTML5 boilerplate](http://html5boilerplate.com/)
* [Twitter Bootstrap](http://twitter.github.com/bootstrap/) with form integration. **version 2.1**

### Extensions included:

* Symfony/Console
* Assetics (with `assetic:dump` command)
* Doctrine (with `doctrine:database:create`, `doctrine:database:drop`,
  `doctrine:schema:load`, `doctrine:schema:show` commands)
* Form
* Monolog
* Session
* SymfonyBrige
* Translation
* Twig
* UrlGenerator

Screeshots
----------

![Form sample](/assets/img/form.jpg)
![Homepage](/assets/img/hp.jpg)

Installation
------------

### With composer

Run the following commands:

    php composer.phar create-project lyrixx/Silex-Kitchen-Edition PATH/TO/YOUR/APP
    cd PATH/TO/YOUR/APP

### With git

Run the following commands:

    git clone https://github.com/lyrixx/Silex-Kitchen-Edition.git PATH/TO/YOUR/APP
    cd PATH/TO/YOUR/APP
    curl -s http://getcomposer.org/installer | php
    php composer.phar install

### Then

You can edit `resources/config/prod.php` and start hacking in `src/controllers.php`

### Assets management

Assets are generated for each request in debug mode.
Letting Assetic generate assets dynamically
in a production environment is not optimized.
Instead, each time you deploy your app in the production
environment, you should dump assets using `php console assetic:dump`.

Todo
----

* SwiftMailer
* Security

Tests
-----

[![Build Status](https://secure.travis-ci.org/lyrixx/Silex-Kitchen-Edition.png?branch=master)](http://travis-ci.org/lyrixx/Silex-Kitchen-Edition)

    composer install --dev
    phpunit

If your web server do not run with the same user as your CLI,
you may run : `chmod 777 -R resources/cache/ web/assets/`.

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
