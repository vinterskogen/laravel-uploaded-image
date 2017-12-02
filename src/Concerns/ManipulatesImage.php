<?php

namespace Vinterskogen\UploadedImage\Concerns;

trait HandlesImage
{
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
