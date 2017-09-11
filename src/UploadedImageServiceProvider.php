<?php

namespace Vinter\UploadedImage;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class UploadedImageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setImageMacroToRequest();
    }

    /**
     * Set image retrieving macro to request.
     *
     * @return void
     */
    private function setImageMacroToRequest()
    {
        Request::macro('image', $this->getImageRetrivingClosure());
    }

    /**
     * Get image retrieving closure.
     *
     * @return \Closure
     */
    private function getImageRetrivingClosure()
    {
        /**
         * Closure to retrieve an instnce of uploaded image with given filename
         * from request.
         *
         * @param string $filename
         * @return \Vinter\UploadedImage\UploadedImage|null
         */
        return function ($filename) {
            if (! Request::hasFile($filename)) {
                return;
            }

            $uploadedFile = Request::file($filename);

            return UploadedImage::createFromBase($uploadedFile);
        };
    }
}
