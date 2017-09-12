# Uploaded image for Laravel

[![Build Status](https://travis-ci.org/vinterskogen/laravel-uploaded-image.svg?branch=master)](https://travis-ci.org/vinterskogen/laravel-uploaded-image) [![StyleCI](https://styleci.io/repos/103072768/shield?branch=master)](https://styleci.io/repos/103072768)

<p align="center"><a href="https://github.com/vinterskogen/laravel-uploaded-image" target="_blank"><img src="https://user-images.githubusercontent.com/8015372/30301362-f65eec58-9762-11e7-86cc-72137c48ba87.png"></a></p>

Gracefully deal with resizing, cropping and scaling uploaded images in Laravel
apps.

## About

This package allows you to retrieve an uploaded image object from request, apply
manipulations over the image content and then place the result to file storage
in few lines of code.

Under the hood this package uses the [Intervention Image](http://image.intervention.io/) -
a PHP image handling and manipulation library.

## Installation

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
- Imagick support is also provided. See the [documentation](http://image.intervention.io/getting_started/configuration)
  page to configure it.

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

Note: since Laravel 5.5 now includes a package auto-discovery feature you do
not have to do it anymore, if you are using 5.5 version of Laravel.

## Basic Usage

For example your app has a controller that handles the users' avatars uploads 
and saves the avatar images to file storage (cloud, local 'public' storage,
etc.). You want that avatars to fit to 250x250 pixels square and to be encoded
into PNG format before putting to storage.

This can be done as easy as:

```php
$request->image('avatar')
	->fit(250, 250)
	->encode('png')
	->store('images/users/avatars', 'public');
```

The `$request` object (and also the `Request` facade) now have an `image`
method, that works like the `file` method - retrieves the image file from the
input and returns it as an instance of `Vinterskogen\UploadedImage\Uploadedimage`
class. 

This class extends the Laravel's `Illuminate\Http\UploadedFile` and implements
a number of helpful image handling methods.

### Basic image handling methods

The list of public methods that are available on `Uploadedimage` instance to 
handle image:

- `fit(int $width, int $height)` - resize and crop the uploaded image to fit
  given width and height, keeping aspect ratio
- `crop(int $width, int $height, int $x = null, int $y = null)` - crop uploaded
  image to given width and height
- `encode(string $format, int $quality = null)` - encode uploaded image in given
format and quality
- `scale(int|float $percentage)` - scale the uploaded image size using given
percentage
- `resizeToWidth(int $width)` - resize the uploaded image to new width,
  constraining aspect ratio
- `resizeToHeight(int $height)` - resize the uploaded image to new height,
  constraining aspect ratio
- `height()` - get height of uploaded image (in pixels)
- `width()` - get width of uploaded image (in pixels)

## Advanced usage

Coming soon...

## License

The MIT license. See the accompanying `LICENSE.md` file.

--------------------------------------------------------------------------------

Copyright © 2017 Vinter Skogen.

