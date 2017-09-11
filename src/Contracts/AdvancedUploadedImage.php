<?php

namespace Vinter\UploadedImage\Contracts;

interface AdvancedUploadedImage
{
    /**
     * Save the applied changed to (real) temporary file and return
     * UploadedImage instance for simple editing.
     *
     * @return \Vinter\UploadedImage\UploadedImage
     */
    public function simpleEditing();

    /**
     * Do not save the changes to (real) temporary file and return UploadedImage
     * instance for simple editing.
     *
     * @return \Vinter\UploadedImage\UploadedImage
     */
    public function doNotSaveAndSimpleEditing();

    /**
     * Don't save the changes to (real) temporary file and return UploadedImage
     * instance for simple editing.
     *
     * @return \Vinter\UploadedImage\UploadedImage
     */
    public function dontSaveAndsimpleEditing();

    /**
     * Create advanced uploaded image with given uploaded image instance.
     *
     * @param \Vinter\UploadedImage\UploadedImage $uploadedImage
     *
     * @return \Vinter\UploadedImage\AdvancedUploadedImage
     */
    public static function createFromBase($uploadedImage);
}
