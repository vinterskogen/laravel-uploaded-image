<?php

namespace Vinter\UploadedImage\Contracts;

interface UploadedImage
{
    /**
     * Get height in pixels of uploaded image.
     *
     * @return integer
     */
    public function height();

    /**
     * Get width in pixels of uploaded image.
     *
     * @return integer
     */
    public function width();

    /**
     * Resize the uploaded image to new width, constraining aspect ratio.
     *
     * @param integer $width
     * @return $this
     */
    public function widen($width);

    /**
     * Resize the uploaded image to new height, constraining aspect ratio.
     *
     * @param integer $height
     * @return $this
     */
    public function heighten($height);

    /**
     * Scale the uploaded image size using given percentage.
     *
     * @param integer $percentage
     * @return $this
     */
    public function scale($percentage);

    /**
     * Resize the uploaded image to best fit a given dimensions, keeping aspect
     * ratio.
     *
     * @param integer $width
     * @param integer $height
     * @return $this
     */
    public function resizeToBestFit($width, $height);

    /**
     * Resize an uploaded image to best fit a given dimensions, keeping aspect
     * ratio and with upsize constraint.
     *
     * @param integer $width
     * @param integer $height
     * @return $this
     */
    public function resizeToBestFitWithUpsizeConstraint($width, $height);

    /**
     * Crop uploaded image to given width and height.
     *
     * @param integer $width
     * @param integer $height
     * @param integer|null $x
     * @param integer|null $y
     * @return $this
     */
    public function crop($width, $height, $x = null, $y = null);

    /**
     * Encode uploaded image in given format and quality.
     *
     * @param mixed $format
     * @param integer|null $quality
     * @return $this
     */
    public function encode($format, $quality = null);

    /**
     * Get AdvancedUploadedImage istance for advanced editing.
     *
     * @return \Vinter\UploadedImage\AdvancedUploadedImage
     */
    public function advancedEditing();
}
