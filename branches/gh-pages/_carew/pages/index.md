---
title:    Silex Kitchen Sink Edition
subtitle: A sample/base silex application
layout:   doc
---

What is it
----------

This project is a sample or a bootstrap [silex](http://silex.sensiolabs.org/)
application. You can use it for your next php application.

### HTML / CSS / Javascripts are powered by:

* [HTML5 boilerplate](http://html5boilerplate.com/)
* [Twitter Bootstrap](http://twitter.github.com/bootstrap/) with form integration. **version 2.3.1**

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

Screenshots
----------

![Form sample]({{ relativeRoot }}/img/form.jpg)
![Homepage]({{ relativeRoot }}/img/hp.jpg)

Installation
------------

### With [composer](https://getcomposer.org)

Run the following commands:

    php composer.phar create-project -s dev lyrixx/Silex-Kitchen-Edition PATH/TO/YOUR/APP
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

Assets are generated for each request in debug mode. Letting Assetic generate
assets dynamically in a production environment is not optimized. Instead, each
time you deploy your app in the production environment, you should dump assets
using `php console assetic:dump`.

Todo
----

* SwiftMailer
* Web developper toolbar

Tests
-----

[![Build Status](https://secure.travis-ci.org/lyrixx/Silex-Kitchen-Edition.png?branch=master)](http://travis-ci.org/lyrixx/Silex-Kitchen-Edition)

    composer install --dev
    phpunit

If your web server do not run with the same user as your CLI, you may run :
`chmod 777 -R resources/cache/ web/assets/`.
