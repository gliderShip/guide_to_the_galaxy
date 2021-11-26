Answer to the Ultimate Question of Life, the Universe, and Everything Demo Application
========================

Description

Requirements
------------

* PHP 7.4 or higher
* [Composer][2]

Installation
------------

[Download The Application][1] or clone the repository from [GitHub][1]

```bash
$ git clone https://github.com/gliderShip/guide_to_the_galaxy.git
```

Go to the root of the application and run the following command to install the dependencies:

```bash
$ composer install
```

Usage
-----

There are two commands available:

#### The static labeler
```bash
php bin/console app:static-labeler
```

* The static labeler command will process the matrix defined statically inside `src/Command/StaticLabelerCommand.php` class.
* The static labeler uses the `src/Service/RecursiveLabeler.php` implementation of the `src/Service/ConnectedComponentLabeler.php`
* It is possible to switch `src/Service/ConnectedComponentLabeler.php` association strategy between `src/Service/FourConnectivityStrategy.php` and `src/Service/EightConnectivityStrategy.php`


#### The dynamic labeler
```bash
php bin/console app:dynamic-labeler
```

* The dynamic labeler command will accept a matrix from the standard input.
* The dynamic labeler actually  uses the `src/Service/StackLabeler.php` implementation of the `src/Service/ConnectedComponentLabeler.php`
* It is possible to switch `src/Service/ConnectedComponentLabeler.php` association strategy between `src/Service/FourConnectivityStrategy.php` and `src/Service/EightConnectivityStrategy.php`
