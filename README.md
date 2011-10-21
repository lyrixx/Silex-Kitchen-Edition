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

*  `git clone`
*  `git submodule update --init --recursive`
*  edit `src/config.php`
*  `chmod 777 -R cache/ web/assets`

Start hacking in `src/controllers.php`

Todo
----

* Extensions
  * Monolog
  * SwiftMailer
* Code Sample
  * Before / after

Tests
-----

Just run `phpunit`

sometimes, you have to run : `chmod 777 -R cache/ web/assets`, because CLI and [mode_php|(Gast)CGI|php-fpm|.*] are not the same user

Help
----

* http://silex.sensiolabs.org/documentation
