# Uploaded image for Laravel

[![Build Status](https://travis-ci.org/vinterskogen/laravel-uploaded-image.svg?branch=master)](https://travis-ci.org/vinterskogen/laravel-uploaded-image) [![StyleCI](https://styleci.io/repos/103072768/shield?branch=master)](https://styleci.io/repos/103072768)

<p align="center"><a href="https://github.com/vinterskogen/laravel-uploaded-image" target="_blank"><img src="https://user-images.githubusercontent.com/8015372/30256350-b7045fda-96b2-11e7-989e-1b509beccd4c.png"></a></p>

Gracefully deal with resizing, cropping and scaling uploaded images in Laravel
apps.

## Installation

### Requirements

- PHP version: >=7.0
- intervention/image: ^2.2
- illuminate/http: ~5.4
- illuminate/support: ~5.4
- symfony/http-foundation: ~3.2
- ext-gd

***Note**: support for PHP 5.6 and Laravel 5.3 is planned.*

### How to install

Install via Composer:

Coming soon...

## Basic Usage

This package allows you to retrieve an uploaded image object from request, apply
manipulations over the image content and then place the result to file storage.

Under the hud this package uses the [Intervention Image](http://image.intervention.io/) -
a PHP image handling and manipulation library.

For example your app has a controller that handles the users' avatars uploads 
and save the avatar images to file storage (to 's3', local 'public' storage,
or whatever else).You want that avatars to be cropped to 250x250 pixels and
encoded into PNG format before storing them.

This can be done as easy as:

```php
$request->image('avatar')
	->crop(250, 250)
	->encode('png')
	->store('images/avatars', 's3');
```

The `$request` object now (and also the `Request` facade) has an `image` method,
that works like the `file` method - retrieves the image file from the input 
as an instance of `Vinterskogen\UploadedImage\Uploadedimage` class. 

This class extends the Laravel's `Illuminate\Http\UploadedFile` and implements
a number of helpful image handling methods.

### Basics image handling methods

The list of public methods that are available on `Uploadedimage`:

- `crop(int $width, int $height, int $x = null, int $y = null)` - crop uploaded
  image to given width and height
- `encode(string $format, int $quality = null)` - encode uploaded image in given
format and quality
- `scale(int|float $percentage)` - crop uploaded image to given width and
  height
- `widen(int $width)` - resize the uploaded image to new width, constraining
   aspect ratio
- `heighten(int $height)` - resize the uploaded image to new height,
  constraining aspect ratio
- `resizeToBestFit(int $width, int $height)` - resize the uploaded image to best
  fit a given dimensions, keeping aspect ratio
- `height()` - get height in pixels of uploaded image
- `width()` - get width in pixels of uploaded image

## Advanced usage

Coming soon...

## License

The MIT license. See the accompanying `LICENSE.md` file.

--------------------------------------------------------------------------------

Copyright © 2017 Vinter Skogen.

