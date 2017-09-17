<?php

namespace Vinterskogen\UploadedImage\Tests;

use Mockery;
use ReflectionClass;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Vinterskogen\UploadedImage\UploadedImage;
use Intervention\Image\Image as InterventionImage;
use Vinterskogen\UploadedImage\AdvancedUploadedImage;

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

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
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

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $this->assertEquals($width, $uploadedImage->width());
    }

    /**
     * Test resize to width method.
     *
     * @return void
     */
    public function testResizeToWidth()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->resizeToWidth($newWidth = 250);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(250, $actualInterventionImage->width());
        $this->assertEquals(150, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
    }

    /**
     * Test resize to height method.
     *
     * @return void
     */
    public function testResizeToHeight()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->resizeToHeight($newHeight = 300);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(500, $actualInterventionImage->width());
        $this->assertEquals(300, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
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
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->scale($percentage = 150);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(1500, $actualInterventionImage->width());
        $this->assertEquals(900, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
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
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->scale($percentage = 75.0);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(750, $actualInterventionImage->width());
        $this->assertEquals(450, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
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

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->advancedEditing();

        $this->assertInstanceOf(AdvancedUploadedImage::class, $actualResult);
        $this->assertAttributeEquals($uploadedImage, 'uploadedImage', $actualResult);
    }

    /**
     * Test fit method, when downsizing image.
     *
     * @return void
     */
    public function testFitDownsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->fit($fitToWidth = 300, $fitToHeight = 300);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(300, $actualInterventionImage->width());
        $this->assertEquals(300, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
    }

    /**
     * Test fit method, when upsizing image.
     *
     * @return void
     */
    public function testFitUpsize()
    {
        $filename = 'image.jpg';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->fit($fitToWidth = 1200, $fitToHeight = 1200);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(1200, $actualInterventionImage->width());
        $this->assertEquals(1200, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
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
        $realPath = $uploadedImage->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->crop($cropToWidth = 200, $cropToHeight = 200);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertEquals(200, $actualInterventionImage->width());
        $this->assertEquals(200, $actualInterventionImage->height());

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
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

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->encode($toFormat = 'jpg', $quality = 95);

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertAttributeEquals(true, 'isModified', $uploadedImage);

        $actualInterventionImage = $this->readAttribute($uploadedImage, 'interventionImage');
        $this->assertRegexp('/jpg|jpeg/i', $actualInterventionImage->mime());

        $actualRealImageType = exif_imagetype($realPath);
        $actualRealImageMime = getimagesize($realPath)['mime'];
        $this->assertEquals(IMAGETYPE_PNG, $actualRealImageType);
        $this->assertRegexp('/png/i', $actualRealImageMime);
    }

    /**
     * Test save method.
     *
     * @return void
     */
    public function testSave()
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $uploadedImage = UploadedImage::createFromBase($uploadedFile);
        $realPath = $uploadedFile->getRealPath();

        Image::shouldReceive('make')->andReturnUsing(function ($path) {
            return (new ImageManager())->make($path);
        });

        $actualResult = $uploadedImage->save();

        $this->assertSame($uploadedImage, $actualResult);
        $this->assertFalse($this->readAttribute($uploadedImage, 'isModified'));

        list($actualRealFileWidth, $actualRealFileHeight) = getimagesize($realPath);
        $actualRealImageType = exif_imagetype($realPath);
        $actualRealImageMime = getimagesize($realPath)['mime'];

        $this->assertEquals($width, $actualRealFileWidth);
        $this->assertEquals($height, $actualRealFileHeight);
        $this->assertEquals(IMAGETYPE_PNG, $actualRealImageType);
        $this->assertRegexp('/png/i', $actualRealImageMime);
    }

    /**
     * Test store methods.
     *
     * @dataProvider storingMethodsDataProvider
     * @return void
     */
    public function testStore($storingMethodName)
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $originalUploadedImage = UploadedImage::createFromBase($uploadedFile);

        $containerMock = Mockery::mock();
        $factoryMock = Mockery::mock();
        $adapterMock = Mockery::mock();

        $containerMock->shouldReceive('make')->andReturn($factoryMock);
        $factoryMock->shouldReceive('disk')->andReturn($adapterMock);
        $adapterMock->shouldReceive('putFileAs')->andReturnUsing(
            function ($path) use ($uploadedFile) {
                return "{$path}/{$uploadedFile->hashName()}";
            }
        );

        Mockery::mock('overload:Illuminate\Container\Container')
            ->shouldReceive('getInstance')
            ->andReturn($containerMock);

        $uploadedImage = Mockery::mock($originalUploadedImage);
        $uploadedImage->shouldReceive('save')->andReturnSelf();

        $storeToPath = 'foo/bar';
        $actualPath = $uploadedImage->$storingMethodName($storeToPath);

        $this->assertRegexp("/foo\/bar\/[A-Za-z0-9]{40}\.png/", $actualPath);
    }

    /**
     * Test store as methods.
     *
     * @dataProvider storingAsMethodsDataProvider
     * @return void
     */
    public function testStoreAs($storingAsMethodName)
    {
        $filename = 'image.png';
        $width = 1000;
        $height = 600;

        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);
        $originalUploadedImage = UploadedImage::createFromBase($uploadedFile);

        $containerMock = Mockery::mock();
        $factoryMock = Mockery::mock();
        $adapterMock = Mockery::mock();

        $storeToPath = 'foo/bar';
        $filename = 'avatar.png';

        $containerMock->shouldReceive('make')->andReturn($factoryMock);
        $factoryMock->shouldReceive('disk')->andReturn($adapterMock);
        $adapterMock->shouldReceive('putFileAs')->andReturnUsing(
            function ($path) use ($filename) {
                return "{$path}/{$filename}";
            }
        );

        Mockery::mock('overload:Illuminate\Container\Container')
            ->shouldReceive('getInstance')
            ->andReturn($containerMock);

        $uploadedImage = Mockery::mock($originalUploadedImage);
        $uploadedImage->shouldReceive('save')->andReturnSelf();

        $actualPath = $uploadedImage->$storingAsMethodName($storeToPath, $filename);

        $this->assertRegexp("/foo\/bar\/avatar\.png/", $actualPath);
    }

    /**
     * Storing methods data provider.
     *
     * @return array
     */
    public function storingMethodsDataProvider()
    {
        return [
            ['storePublicly'],
            ['store'],
        ];
    }

    /**
     * Storing as methods data provider.
     *
     * @return array
     */
    public function storingAsMethodsDataProvider()
    {
        return [
            ['storePubliclyAs'],
            ['storeAs'],
        ];
    }

}
