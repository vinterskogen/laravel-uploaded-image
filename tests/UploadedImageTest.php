<?php

namespace Vinter\UploadedImage\Tests;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Vinter\UploadedImage\UploadedImage;
use Vinter\UploadedImage\AdvancedUploadedImage;

class UploadedImageTest extends TestCase
{
    /**
     * Test height method.
     *
     * @return void
     */
    public function testHeight()
    {
        $filename = 'image.jpg';
        $width = mt_rand(1000, 1200);
        $height = mt_rand(600, 800);

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $this->assertEquals($height, $uploadedImage->height());
    }

    /**
     * Test width method.
     *
     * @return void
     */
    public function testWidth()
    {
        $filename = 'image.jpg';
        $width = mt_rand(1000, 1200);
        $height = mt_rand(600, 800);

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $this->assertEquals($width, $uploadedImage->width());
    }

    /**
     * Test widen method.
     *
     * @return void
     */
    public function testWiden()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $uploadedImage->widen($widen = 250);

        $expectedWidth = 250;
        $expectedHeight = 150;
        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test heighten method.
     *
     * @return void
     */
    public function testHeighten()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $uploadedImage->heighten($heighten = 300);

        $expectedWidth = 500;
        $expectedHeight = 300;
        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test scale method when upsizing.
     *
     * @return void
     */
    public function testScaleUpsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $uploadedImage->scale($percentage = 150);

        $expectedWidth = 1500;
        $expectedHeight = 900;
        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test scale method when downsizing.
     *
     * @return void
     */
    public function testScaleDownsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $uploadedImage->scale($percentage = 75.0);

        $expectedWidth = 750;
        $expectedHeight = 450;
        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test scale method throws LogicException on wrong percentage.
     *
     * @expectedException \LogicException
     *
     * @return void
     */
    public function testScaleThrowsLogicExceptionOnWrongPercentage()
    {
        $uploadedFile = UploadedFile::fake()->image('image.jpg');
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        $uploadedImage->scale($wrongPercentage = 0);
    }

    /**
     * Test advanced editing.
     *
     * @return void
     */
    public function testAdvancedEditing()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $actualResult = $uploadedImage->advancedEditing();

        $this->assertInstanceOf(AdvancedUploadedImage::class, $actualResult);
        $this->assertAttributeEquals($uploadedImage, 'uploadedImage', $actualResult);
    }

    /**
     * Test resize to best fit method when downsizing.
     *
     * @return void
     */
    public function testResizeToBestFitDownsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $fitToWidth = 1000;
        $fitToHeight = 300;
        $uploadedImage->resizeToBestFit($fitToWidth, $fitToHeight);

        $expectedWidth = 500;
        $expectedHeight = 300;

        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test resize to best fit method when upsizing.
     *
     * @return void
     */
    public function testResizeToBestFitUpsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $fitToWidth = 2000;
        $fitToHeight = 2000;
        $uploadedImage->resizeToBestFit($fitToWidth, $fitToHeight);

        $expectedWidth = 2000;
        $expectedHeight = 1200;

        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test resize to best fit with upsize constraint method when downsizing.
     *
     * @return void
     */
    public function testresizeToBestFitWithUpsizeConstraintDownsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $fitToWidth = 1000;
        $fitToHeight = 300;

        $uploadedImage->resizeToBestFitWithUpsizeConstraint($fitToWidth, $fitToHeight);

        $expectedWidth = 500;
        $expectedHeight = 300;
        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }

    /**
     * Test resize to best fit with upsize constraint method does not change
     * image dimensions when upsizing.
     *
     * @return void
     */
    public function testresizeToBestFitWithUpsizeConstraintUpsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $fitToUpsizedWidth = 2000;
        $fitToUpsizedHeight = 2000;

        $uploadedImage->resizeToBestFitWithUpsizeConstraint(
            $fitToUpsizedWidth,
            $fitToUpsizedHeight
        );

        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($width, $actualWidth);
        $this->assertEquals($height, $actualHeight);
    }

    /**
     * Test crop method.
     *
     * @return void
     */
    public function testCrop()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $cropToWidth = 200;
        $cropToHeight = 200;

        $uploadedImage->crop($cropToWidth, $cropToHeight);

        list($actualWidth, $actualHeight) = getimagesize($uploadedImage->getRealPath());

        $this->assertEquals($cropToWidth, $actualWidth);
        $this->assertEquals($cropToHeight, $actualHeight);
    }

    /**
     * Test encode method.
     *
     * @return void
     */
    public function testEncode()
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedFile->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($realPath) {
            return (new ImageManager())->make($realPath);
        });

        $uploadedImage->encode($format = 'jpg', $quality = 95);

        $actualImageType = exif_imagetype($realPath);
        $actualImageMime = getimagesize($realPath)['mime'];

        $this->assertEquals(IMAGETYPE_JPEG, $actualImageType);
        $this->assertRegexp('/jpeg/i', $actualImageMime);
    }
}
