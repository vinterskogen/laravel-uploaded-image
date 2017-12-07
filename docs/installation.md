# Installation

## How to install

Install via Composer:

`composer require vinterskogen/laravel-uploaded-image`

After the installation, open your Laravel config file `config/app.php` and paste
the following lines to the `providers` array to add service providers into your
app:

```php
Vinterskogen\UploadedImage\UploadedImageServiceProvider::class,
Intervention\Image\ImageServiceProvider::class,
```

> **Note**: since Laravel 5.5 now includes a package auto-discovery feature you do
not have to do it, if you are at latest version of Laravel.

## Requirements

- `PHP`: >=7.0
- `intervention/image`: ^2.2
- `illuminate/http`: >=5.4.15
- `illuminate/support`: ~5.4
- `symfony/http-foundation`: ~3.2
- `ext-gd` or `ext-imagick`

> **Note**: since Intervention Image requires [GD guide](http://php.net/manual/en/book.image.php) 
or [Imagick](https://www.imagemagick.org/) with [Imagick PHP extension](http://php.net/manual/en/book.imagick.php) -
you have to check if you have installed at least one of then. GD is used by default,
Imagick support is also provided - check the original Intervention Image's [documentation](http://image.intervention.io/getting_started/configuration) if you want to enable it.
