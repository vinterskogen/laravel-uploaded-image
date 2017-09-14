# Uploaded image for Laravel

[![Build Status](https://travis-ci.org/vinterskogen/laravel-uploaded-image.svg?branch=master)](https://travis-ci.org/vinterskogen/laravel-uploaded-image) [![StyleCI](https://styleci.io/repos/103072768/shield?branch=master)](https://styleci.io/repos/103072768)

<p align="center"><a href="https://github.com/vinterskogen/laravel-uploaded-image" target="_blank"><img src="https://user-images.githubusercontent.com/8015372/30301362-f65eec58-9762-11e7-86cc-72137c48ba87.png"></a></p>

Gracefully deal with resizing, cropping and scaling uploaded images in Laravel
apps.

## About

This package allows you to retrieve an uploaded image object from request, apply
manipulations over the image content and then place the result to file storage
in a few lines of code.

Under the hood this package is using [Intervention Image](http://image.intervention.io/) -
a PHP image handling and manipulation library.

## Installation

Install via Composer:

`composer require vinterskogen/laravel-uploaded-image`

Check the [Installation](docs/installation.md) page for full information about
package requirements and notes.

## Basic Usage

For example your app has a controller that handles the users' avatars uploads 
and saves the avatar images to file storage (cloud, local 'public' storage,
etc.).

You want that avatars to fit to 250x250 pixels square and to be encoded
into PNG format before putting to storage.

This can be done as easy as:

```php
$request->image('avatar')
        ->fit(250, 250)
        ->encode('png')
        ->store('images/users/avatars', 'public');
```

The `$request` object (and also the `Request` facade) now have an `image`
method, that works just like the `file` method - retrieves an image file from the
input and returns it as an instance of `Vinterskogen\UploadedImage\Uploadedimage`
class. 

This class extends the Laravel's `Illuminate\Http\UploadedFile` and implements
a number of helpful image handling methods.

**Note**: 

To be sure the file you are going to handle like an image is actually an image 
file, you have to apply [request form validation](https://laravel.com/docs/master/validation#form-request-validation)
constraints on your controller's input (if you haven't done that yet, of course).

### Basic image handling methods

The list of public image handling methods that are available on `Uploadedimage` 
instance:

#### Fit

`fit(int $width, int $height)` 

Resize and crop the uploaded image to fit given width and height, keeping aspect 
ratio.

#### Crop

`crop(int $width, int $height, int $x = null, int $y = null)`

Crop uploaded  image to given width and height.

#### Encode

`encode(string $format, int $quality = null)` 

Encode uploaded image in given format and quality.

#### Scale

`scale(int|float $percentage)`

Scale the uploaded image size using given percentage.

#### Resize to width

`resizeToWidth(int $width)` 

Resize the uploaded image to new width, constraining aspect ratio. 

#### Resize to height

`resizeToHeight(int $height)`

Resize the uploaded image to new height,  constraining aspect ratio.

#### Height

`height()`

Get height of uploaded image (in pixels).

#### Width

`width()`

Get width of uploaded image (in pixels).

## Advanced usage

Coming soon...

## License

The MIT license. See the accompanying `LICENSE.md` file.

--------------------------------------------------------------------------------

Copyright © 2017 Vinter Skogen.

