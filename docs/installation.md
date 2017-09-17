# Installation

## How to install

Install via Composer:

`composer require vinterskogen/laravel-uploaded-image`

After the installation, open your Laravel config file `config/app.php` and paste
the following lines to the `providers` array to add service providers for
this package to your app:

```php
Vinterskogen\UploadedImage\UploadedImageServiceProvider::class,
Intervention\Image\ImageServiceProvider::class,
```

**Note**: since Laravel 5.5 now includes a package auto-discovery feature you do
not have to do it anymore, if you are using latest version of Laravel.

## Requirements

- PHP version: >=7.0
- intervention/image: ^2.2
- illuminate/http: ~5.4
- illuminate/support: ~5.4
- symfony/http-foundation: ~3.2
- ext-gd

**Note**:

- Intervention Image library requires GD library or Imagick PHP extension -
  check if you have installed at least one of then.
- GD is used by Intervention Image as a driver for image handling by default.
- Imagick support is also provided - check the Intervention Image's 
  [documentation](http://image.intervention.io/getting_started/configuration)
if you want to enable it.

