<?php

namespace Vinterskogen\UploadedImage;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;
use Vinterskogen\UploadedImage\Contracts\UploadedImage as UploadedImageContract;

class UploadedImage extends UploadedFile implements UploadedImageContract
{
    use Concerns\HandlesImage;
    use Concerns\SavesBeforeStoring;

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
        if (!isset($this->advancedUplodedImage)) {
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
        if (!isset($this->interventionImage)) {
            $this->interventionImage = $this->makeInterventionImage();
        }

        return $this->interventionImage;
    }

    /**
     * Set Intervention Image instance.
     *
     * @param \Intervention\Image\Image
     *
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
        return Image::make($this->getRealPath());
    }
}
