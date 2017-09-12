<?php

namespace Vinterskogen\UploadedImage;

use LogicException;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Vinterskogen\UploadedImage\Contracts\UploadedImage as UploadedImageContract;

class UploadedImage extends UploadedFile implements UploadedImageContract
{
    /**
     * The intervention image instance.
     *
     * @var \Intervention\Image\Image
     */
    protected $advancedUplodedImage;

    /**
     * Get AdvancedUploadedImage instance for advanced editing.
     *
     * @return \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    public function advancedEditing()
    {
        return $this->getAdvancedUploadedImage();
    }

    /**
     * Get AdvancedUploadedImage instance, that points the same (real)
     * temporary file, as this uploaded image.
     *
     * @return \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    private function getAdvancedUploadedImage()
    {
        if (! isset($this->advancedUplodedImage)) {
            $this->advancedUplodedImage = $this->makeAdvancedUploadedImage();
        }

        return $this->advancedUplodedImage;
    }

    /**
     * Make an AdvancedUploadedImage instance from base.
     *
     * @return \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    private function makeAdvancedUploadedImage()
    {
        return AdvancedUploadedImage::createFromBase($this);
    }

    /**
     * Make an Intervention Image instance, that points the same (real)
     * temporary file, as this uploaded image.
     *
     * @return \Intervention\Image\Image
     */
    private function makeInterventionImage()
    {
        $realPath = $this->getRealPath();

        return Image::make($realPath);
    }

    /**
     * Get height in pixels of uploaded image.
     *
     * @return int
     */
    public function height()
    {
        return $this->makeInterventionImage()->height();
    }

    /**
     * Get width in pixels of uploaded image.
     *
     * @return int
     */
    public function width()
    {
        return $this->makeInterventionImage()->width();
    }

    /**
     * Resize the uploaded image to new width, constraining aspect ratio.
     *
     * @param int $width
     *
     * @return $this
     */
    public function resizeToWidth($width)
    {
        $this->makeInterventionImage()
            ->widen($width)
            ->save();

        return $this;
    }

    /**
     * Resize the uploaded image to new height, constraining aspect ratio.
     *
     * @param int $height
     *
     * @return $this
     */
    public function resizeToHeight($height)
    {
        $this->makeInterventionImage()
            ->heighten($height)
            ->save();

        return $this;
    }

    /**
     * Resize and crop the uploaded image to fit a given dimensions, keeping
     * aspect ratio.
     *
     * @param int $width
     * @param int $height
     *
     * @return $this
     */
    public function fit($width, $height)
    {
        $this->makeInterventionImage()
            ->fit($width, $height)
            ->save();

        return $this;
    }

    /**
     * Crop uploaded image to given width and height.
     *
     * @param int      $width
     * @param int      $height
     * @param int|null $x
     * @param int|null $y
     *
     * @return $this
     */
    public function crop($width, $height, $x = null, $y = null)
    {
        $this->makeInterventionImage()
            ->crop($width, $height, $x, $y)
            ->save();

        return $this;
    }

    /**
     * Encode uploaded image in given format and quality.
     *
     * @param mixed    $format
     * @param int|null $quality
     *
     * @return $this
     */
    public function encode($format, $quality = null)
    {
        $this->makeInterventionImage()
            ->encode($format, $quality)
            ->save();

        return $this;
    }

    /**
     * Scale the uploaded image size using given percentage.
     *
     * @param int|float $percentage
     *
     * @return $this
     */
    public function scale($percentage)
    {
        $this->validatePercentageValue($percentage);

        $percentage = floatval($percentage);
        $width = $this->width() / 100 * $percentage;
        $height = $this->height() / 100 * $percentage;

        $this->makeInterventionImage()
            ->resize($width, $height)
            ->save();

        return $this;
    }

    /**
     * Validate percentage value.
     *
     * @param int|float $percentage
     *
     * @throws \LogicException
     *
     * @return void
     */
    private function validatePercentageValue($percentage)
    {
        if (($percentage = floatval($percentage)) > 0) {
            return;
        }

        throw new LogicException(
            'Percentage should be an number greater than zero. '.
            "Value ({$percentage}) was given."
        );
    }
}
