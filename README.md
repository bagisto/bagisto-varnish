# Bagisto Varnish

Varnish setup for Bagisto E-commerce.

## Requirements

- You need to setup the Varnish 6 in your system.

## Installation

- Run `composer require bagisto/bagisto-varnish`.

- After that `cacheable` middleware is available for your routes. Use this `cacheable` middleware to the routes which you want to cache. (Recomended Files: `packages/Webkul/Shop/src/Routes/store-front-routes.php` and `packages/Webkul/Velocity/src/Routes/front-routes.php`)

- Copy all the contents of the `vcls/6.0.vcl` file to the `/etc/varnish/default.vcl`.

- Restart your varnish server.
