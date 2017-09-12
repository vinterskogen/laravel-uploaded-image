## Installation

### How to install

Install via Composer:

`composer require vinterskogen/laravel-uploaded-image`

After the installation, open your Laravel config file `config/app.php` and add
the following lines to the `$providers` array to add service providers for
this package:

```php
Vinterskogen\UploadedImage\UploadedImageServiceProvider::class,
Intervention\Image\ImageServiceProvider::class,
```

**Note**: since Laravel 5.5 now includes a package auto-discovery feature you do
not have to do it anymore, if you are using 5.5 version of Laravel.

### Requirements

- PHP version: >=7.0
- intervention/image: ^2.2
- illuminate/http: ~5.4
- illuminate/support: ~5.4
- symfony/http-foundation: ~3.2
- ext-gd

**Note**:

- Backward compatibility for PHP 5.6 and Laravel 5.3 is planned.
- Intervention Image requires GD Library (>=3.0) or Imagick PHP extension
  (>=6.5.7). Check if you have installed at least one of then.
- GD is used by Intervention Image as default driver for image handling.
- Imagick support is also provided - see the [documentation](http://image.intervention.io/getting_started/configuration)
  page to enable it.
