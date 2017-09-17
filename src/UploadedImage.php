<?php

namespace Vinterskogen\UploadedImage;

use LogicException;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;
use Vinterskogen\UploadedImage\Concerns\SavesBeforeStoring;
use Vinterskogen\UploadedImage\Contracts\UploadedImage as UploadedImageContract;

class UploadedImage extends UploadedFile implements UploadedImageContract
{
    use SavesBeforeStoring;

    /**
     * The advanced uploaded image instance.
     *
     * @var \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    protected $advancedUplodedImage;

    /**
     * The intervention image instance.
     *
     * @var \Intervention\Image\Image
     */
    protected $interventionImage;

    /**
     * Intervention image modification flag.
     *
     * @var bool
     */
    protected $isModified = false;

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
     * Determine if intervention image is modified.
     *
     * @return bool
     */
    public function isModified()
    {
        return $this->isModified;
    }

    /**
     * Set intervention image is modified flag to true.
     *
     * @return bool
     */
    protected function toggleModified()
    {
        return $this->isModified = true;
    }

    /**
     * Set intervention image is modified flag to false.
     *
     * @return bool
     */
    protected function toggleNotModified()
    {
        return $this->isModified = false;
    }

    /**
     * Fire intervention image saving.
     *
     * @return $this
     */
    public function save()
    {
        $this->getInterventionImage()->save();

        $this->toggleNotModified();

        return $this;
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
     * Get InterventionImage instance, that points the same (real) temporary
     * file, as this uploaded image.
     *
     * @return \Intervention\Image\Image
     */
    public function getInterventionImage()
    {
        if (! isset($this->interventionImage)) {
            $this->interventionImage = $this->makeInterventionImage();
        }

        return $this->interventionImage;
    }

    /**
     * Set Intervention Image instance.
     *
     * @param \Intervention\Image\Image
     * @return $this
     */
    public function setInterventionImage(InterventionImage $interventionImage)
    {
        $this->interventionImage = $interventionImage;

        return $this;
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
        return $this->getInterventionImage()->height();
    }

    /**
     * Get width in pixels of uploaded image.
     *
     * @return int
     */
    public function width()
    {
        return $this->getInterventionImage()->width();
    }

    /**
     * Resize the uploaded image to new width, constraining aspect ratio.
     *
     * @param int $width
     * @return $this
     */
    public function resizeToWidth($width)
    {
        $this->toggleModified();

        $this->getInterventionImage()->widen($width);

        return $this;
    }

    /**
     * Resize the uploaded image to new height, constraining aspect ratio.
     *
     * @param int $height
     * @return $this
     */
    public function resizeToHeight($height)
    {
        $this->toggleModified();

        $this->getInterventionImage()->heighten($height);

        return $this;
    }

    /**
     * Resize and crop the uploaded image to fit a given dimensions, keeping
     * aspect ratio.
     *
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function fit($width, $height)
    {
        $this->toggleModified();

        $this->getInterventionImage()->fit($width, $height);

        return $this;
    }

    /**
     * Crop uploaded image to given width and height.
     *
     * @param int      $width
     * @param int      $height
     * @param int|null $x
     * @param int|null $y
     * @return $this
     */
    public function crop($width, $height, $x = null, $y = null)
    {
        $this->toggleModified();

        $this->getInterventionImage()->crop($width, $height, $x, $y);

        return $this;
    }

    /**
     * Encode uploaded image in given format and quality.
     *
     * @param mixed    $format
     * @param int|null $quality
     * @return $this
     */
    public function encode($format, $quality = null)
    {
        $this->toggleModified();

        $this->getInterventionImage()->encode($format, $quality);

        return $this;
    }

    /**
     * Scale the uploaded image size using given percentage.
     *
     * @param int|float $percentage
     * @return $this
     */
    public function scale($percentage)
    {
        $this->validatePercentageValue($percentage);

        $this->toggleModified();

        $percentage = floatval($percentage);
        $width = $this->width() / 100 * $percentage;
        $height = $this->height() / 100 * $percentage;

        $this->getInterventionImage()->resize($width, $height);

        return $this;
    }

    /**
     * Validate percentage value.
     *
     * @param int|float $percentage
     * @return void
     *
     * @throws \LogicException
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
