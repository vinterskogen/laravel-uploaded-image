<?php

namespace Vinterskogen\UploadedImage\Tests;

use Mockery;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Vinterskogen\UploadedImage\UploadedImage;
use Vinterskogen\UploadedImage\AdvancedUploadedImage;

class AdvancedUploadedImageTest extends TestCase
{
    /**
     * Test construct method.
     *
     * @return void
     */
    public function testConstruct()
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedImage->getRealPath();

        $actualAdvancedUploadedImage = new AdvancedUploadedImage(
            $uploadedImage,
            new Driver(),
            imagecreatefrompng($realPath)
        );

        $this->assertAttributeEquals(
            $uploadedImage,
            'uploadedImage',
            $actualAdvancedUploadedImage
        );

        $this->assertAttributeInternalType(
            'resource',
            'core',
            $actualAdvancedUploadedImage
        );
    }

    /**
     * Test store method saves image and returns path.
     *
     * @return void
     */
    public function testStoreSavesImage()
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $originalUploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $originalUploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $basePath = 'foo/bar/baz';
        $hashName = $originalUploadedImage->hashName();
        $expectedPath = "{$basePath}/{$hashName}";

        $uploadedImage = Mockery::mock($originalUploadedImage);
        $uploadedImage->shouldReceive('store')
            ->andReturnUsing(function ($basePath) use ($hashName) {
                return "{$basePath}/{$hashName}";
            });

        $advancedUploadedImage = AdvancedUploadedImage::createFromBase($uploadedImage);

        $cropWidth = 100;
        $cropHeight = 50;

        $actualPath = $advancedUploadedImage->crop($cropWidth, $cropHeight)
            ->store($basePath);

        $this->assertEquals($expectedPath, $actualPath);

        list($actualWidth, $actualHeight) = getimagesize($realPath);
        $this->assertEquals($cropWidth, $actualWidth);
        $this->assertEquals($cropHeight, $actualHeight);
    }

    /**
     * Test store method works as expected after a number of chaining method
     * calls.
     *
     * @return void
     */
    public function testStoreAfterMethodChaining()
    {
        $filename = 'image.png';
        $width = mt_rand(1000, 1200);
        $height = mt_rand(600, 800);

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $originalUploadedImage = UploadedImage::createFromBase($uploadedFile);

        $uploadedImage = Mockery::mock($originalUploadedImage);
        $uploadedImage->shouldReceive('store')->andReturn();

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $advancedUploadedImage = AdvancedUploadedImage::createFromBase($uploadedImage);

        $cropWidth = mt_rand(300, 350);
        $cropHeight = mt_rand(200, 250);
        $veryDarkCyan = '#006666'; // R:0, G:102, B:102

        $basePath = 'foo/bar/baz';

        // Action:
        // - call some commands to manipulate the image content (fill with
        //   color, crop and rotate)
        // - then call store method to save and place the file to storage
        $actualPath = $advancedUploadedImage->fill($veryDarkCyan)
            ->crop($cropWidth, $cropHeight)
            ->rotate(-90)
            ->store($basePath);

        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());
        $this->assertEquals($cropWidth, $actualHeight);
        $this->assertEquals($cropHeight, $actualWidth);

        $actualImage = imagecreatefrompng($uploadedImage->getRealPath());
        $x = mt_rand(10, $actualWidth - 10);
        $y = mt_rand(10, $actualHeight - 10);
        $colorIndex = imagecolorat($actualImage, $x, $y);
        $expectedRgbColors = ['red' => 0, 'green' => 102, 'blue' => 102];
        $actualRgbColors = imagecolorsforindex($actualImage, $colorIndex);

        $this->assertArraySubset($expectedRgbColors, $actualRgbColors);
    }

    /**
     * Test create from base method.
     *
     * @return void
     */
    public function testCreateFromBase()
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $actualResult = AdvancedUploadedImage::createFromBase($uploadedImage);

        $this->assertAttributeEquals(
            $uploadedImage,
            'uploadedImage',
            $actualResult
        );

        $this->assertRegexp(
            '/png/i',
            $this->readAttribute($actualResult, 'mime')
        );
    }

    /**
     * Test simple editing method.
     *
     * @return void
     */
    public function testSimpleEditing()
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 800;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $advancedUploadedImage = AdvancedUploadedImage::createFromBase($uploadedImage);

        $actualResult = $advancedUploadedImage->simpleEditing();

        $this->assertInstanceOf(UploadedImage::class, $actualResult);
    }
}
