<?php

namespace Vinterskogen\UploadedImage\Contracts;

interface UploadedImage
{
    /**
     * Get height in pixels of uploaded image.
     *
     * @return int
     */
    public function height();

    /**
     * Get width in pixels of uploaded image.
     *
     * @return int
     */
    public function width();

    /**
     * Resize the uploaded image to new width, constraining aspect ratio.
     *
     * @param int $width
     *
     * @return $this
     */
    public function widen($width);

    /**
     * Resize the uploaded image to new height, constraining aspect ratio.
     *
     * @param int $height
     *
     * @return $this
     */
    public function heighten($height);

    /**
     * Scale the uploaded image size using given percentage.
     *
     * @param int $percentage
     *
     * @return $this
     */
    public function scale($percentage);

    /**
     * Resize the uploaded image to best fit a given dimensions, keeping aspect
     * ratio.
     *
     * @param int $width
     * @param int $height
     *
     * @return $this
     */
    public function resizeToBestFit($width, $height);

    /**
     * Resize an uploaded image to best fit a given dimensions, keeping aspect
     * ratio and with upsize constraint.
     *
     * @param int $width
     * @param int $height
     *
     * @return $this
     */
    public function resizeToBestFitWithUpsizeConstraint($width, $height);

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
    public function crop($width, $height, $x = null, $y = null);

    /**
     * Encode uploaded image in given format and quality.
     *
     * @param mixed    $format
     * @param int|null $quality
     *
     * @return $this
     */
    public function encode($format, $quality = null);

    /**
     * Get AdvancedUploadedImage instance for advanced editing.
     *
     * @return \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    public function advancedEditing();
}
