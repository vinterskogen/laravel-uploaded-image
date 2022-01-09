<?php

namespace Vinterskogen\UploadedImage\Concerns;

trait SavesBeforeStoring
{
    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param string       $path
     * @param string       $name
     * @param array|string $options
     *
     * @return string|false
     */
    public function storeAs($path, $name, $options = [])
    {
        if ($this->isModified()) {
            $this->save();
        }

        return parent::storeAs($path, $name, $options);
    }
}
