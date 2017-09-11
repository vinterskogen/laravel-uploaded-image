<?php

namespace Vinterskogen\UploadedImage\Contracts;

interface AdvancedUploadedImage
{
    /**
     * Save the applied changed to (real) temporary file and return
     * UploadedImage instance for simple editing.
     *
     * @return \Vinterskogen\UploadedImage\UploadedImage
     */
    public function simpleEditing();

    /**
     * Do not save the changes to (real) temporary file and return UploadedImage
     * instance for simple editing.
     *
     * @return \Vinterskogen\UploadedImage\UploadedImage
     */
    public function doNotSaveAndSimpleEditing();

    /**
     * Don't save the changes to (real) temporary file and return UploadedImage
     * instance for simple editing.
     *
     * @return \Vinterskogen\UploadedImage\UploadedImage
     */
    public function dontSaveAndsimpleEditing();

    /**
     * Create advanced uploaded image with given uploaded image instance.
     *
     * @param \Vinterskogen\UploadedImage\UploadedImage $uploadedImage
     *
     * @return \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    public static function createFromBase($uploadedImage);
}
