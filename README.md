# Bagisto Varnish

Varnish setup for [Bagisto E-commerce](https://github.com/bagisto/bagisto).

Note: This setup is only for [Bagisto E-commerce](https://github.com/bagisto/bagisto). If you want you can customize this as per your need.

## Requirements

- You need to setup the Varnish 6 in your system.

- Copy all the contents of the `vcls/6.0.vcl` file to the `/etc/varnish/default.vcl`.

- Restart your varnish server.

## Installation

- Run `composer require bagisto/bagisto-varnish`.

- After that `cacheable` middleware is available for your routes. Use this `cacheable` middleware to the routes which you want to cache.

- In `packages/Webkul/Shop/src/Routes/store-front-routes.php`, add `cacheable` in middleware key,

  ~~~php
  # File: packages/Webkul/Shop/src/Routes/store-front-routes.php
  ...
  Route::group(['middleware' => ['web', 'locale', 'theme', 'currency', 'cacheable']], function () {
    ...
  });
  ...
  ~~~

- In `packages/Webkul/Velocity/src/Routes/front-routes.php`, add `cacheable` in middleware key,

  ~~~php
  # File: packages/Webkul/Velocity/src/Routes/front-routes.php
  ...
  Route::group(['middleware' => ['web', 'locale', 'theme', 'currency', 'cacheable']], function () {
    ...
  });
  ...
  ~~~

- Run `php artisan optimize:clear`
