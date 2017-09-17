<?php

namespace Vinterskogen\UploadedImage;

use Exception;
use Intervention\Image\Facades\Image;
use Intervention\Image\AbstractDriver;
use Intervention\Image\Image as InterventionImage;
use Intervention\Image\Exception\NotSupportedException;
use Vinterskogen\UploadedImage\Contracts\AdvancedUploadedImage as AdvancedUploadedImageContract;

class AdvancedUploadedImage extends InterventionImage implements AdvancedUploadedImageContract
{
    /**
     * The uploaded image instance.
     *
     * @var \Vinterskogen\UploadedImage\UploadedImage
     */
    protected $uploadedImage;

    /**
     * Creates a new image instance.
     *
     * @param \Vinterskogen\UploadedImage\UploadedImage $uploadedImage
     * @param \Intervention\Image\AbstractDriver  $driver
     * @param mixed                               $core
     */
    public function __construct(UploadedImage $uploadedImage, AbstractDriver $driver = null, $core = null)
    {
        $this->uploadedImage = $uploadedImage;
        $this->driver = $driver;
        $this->core = $core;
    }

    /**
     * Return UploadedImage instance for simple editing.
     *
     * @return \Vinterskogen\UploadedImage\UploadedImage
     */
    public function simpleEditing()
    {
        return $this->uploadedImage->setInterventionImage($this);
    }

    /**
     * Do not save the changes to (real) temporary file and return UploadedImage
     * instance for simple editing.
     *
     * @return \Vinterskogen\UploadedImage\UploadedImage
     */
    public function doNotSaveAndSimpleEditing()
    {
        return $this->uploadedImage;
    }

    /**
     * Don't save the changes to (real) temporary file and return UploadedImage
     * instance for simple editing.
     *
     * @return \Vinterskogen\UploadedImage\UploadedImage
     */
    public function dontSaveAndsimpleEditing()
    {
        return $this->doNotSaveAndSimpleEditing();
    }

    /**
     * Handle the method call.
     *
     * @param string $method
     * @param array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        try {
            // First we try to pass the call to given method up to proper method
            // on the parent class. So we call the corresponding method on the
            // Intervention\Image\Image class and just return the result.
            return call_user_func_array([parent::class, $method], $arguments);
        } catch (Exception $exception) {
            // If catched exception is not a 'command not supported' exception,
            // which is only one we are expecting here, we understand that
            // something unwanted took place, so we throw it further.
            if (! $this->isExceptedException($exception)) {
                throw $exception;
            }

            // If we find the calling method is related to placing the file to
            // storage, we hit the 'save' method on this object to write any
            // possibly unsaved changes before actually storing the file.
            if ($this->isStorageRelatedMethod($method)) {
                $this->save();
            }

            // Finally we pass the call to the UploadedImage instance. If it
            // has no method or macro with given name an exception will be
            // thrown. It can be handled as usual on higher level in app.
            return call_user_func_array([$this->uploadedImage, $method], $arguments);
        }
    }

    /**
     * Determine if the given exception is an expected exception (while
     * handling the __call).
     *
     * For example - an instance of 'command not supported' exception (from
     * intervention image's driver).
     *
     * @see \Intervention\Image\AbstractDriver::executeCommand()
     * @see \Intervention\Image\AbstractDriver::getCommandClassName()
     *
     * @param Exception $exception
     * @return bool
     */
    private function isExceptedException(Exception $exception)
    {
        return in_array(get_class($exception), $this->getExceptedExceptionsList());
    }

    /**
     * Get the list of expected exceptions (while handling the __call).
     *
     * @return array
     */
    private function getExceptedExceptionsList()
    {
        return [
            NotSupportedException::class,
        ];
    }

    /**
     * Determine if the given method in the list of methods of UploadedFile
     * class, that are related to placing the uploaded file to storage.
     *
     * @param string $method
     * @return bool
     */
    private function isStorageRelatedMethod($method)
    {
        return in_array($method, $this->getStorageRelatedMethodsList());
    }

    /**
     * Get the list of method names related to placing the uploaded file to
     * storage.
     *
     * @return array
     */
    private function getStorageRelatedMethodsList()
    {
        return [
            'storePubliclyAs',
            'storePublicly',
            'storeAs',
            'store',
        ];
    }

    /**
     * Create advanced uploaded image with given uploaded image instance.
     *
     * @param \Vinterskogen\UploadedImage\UploadedImage $uploadedImage
     * @return \Vinterskogen\UploadedImage\AdvancedUploadedImage
     */
    public static function createFromBase($uploadedImage)
    {
        $interventionImage = $uploadedImage->getInterventionImage();

        $driver = $interventionImage->getDriver();
        $core = $interventionImage->getCore();

        $image = new self($uploadedImage, $driver, $core);

        $image->mime = $interventionImage->mime();
        $image->setFileInfoFromPath($uploadedImage->getRealPath());

        return $image;
    }

    /**
     * Make intervention image instance, that points the same (real) temporary
     * file, as the uploaded image.
     *
     * @param \Vinterskogen\UploadedImage\UploadedImage $uploadedImage
     * @return \Intervention\Image\Image
     */
    private static function makeInterventionImage(UploadedImage $uploadedImage)
    {
        $realPath = $uploadedImage->getRealPath();

        return Image::make($realPath);
    }
}
