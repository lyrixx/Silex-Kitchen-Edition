Silex - Kitchen Edition
=======================

This project is a base for your silex applications.

It already embed :

* HTML5 boilerplate (http://html5boilerplate.com/)
* Twitter Bootrap with form integration (http://twitter.github.com/bootstrap/)
* Few extensions :

  * Doctrine
  * Form
  * Session
  * SymfonyBrige
  * Translation
  * Twig (with debug extension)
  * UrlGenerator

Installation
------------

Run the following commands:

   curl -s http://getcomposer.org/installer | php
   php composer.phar install

And then you can edit `src/config.php` and start hacking in `src/controllers.php`

Todo
----

* Extensions
  * SwiftMailer
* Code Sample
  * Before / after

Tests
-----

[![Build Status](https://secure.travis-ci.org/lyrixx/Silex-Kitchen-Edition.png?branch=master)](http://travis-ci.org/lyrixx/Silex-Kitchen-Edition)

Just run `phpunit`

sometimes, you have to run : `chmod 777 -R cache/ web/assets`, because CLI does not run with the same user as  [mode_php|php-fpm|.*].

Help
----

* http://silex.sensiolabs.org/documentation
