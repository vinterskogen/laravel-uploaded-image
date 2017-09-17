<?php

namespace Vinterskogen\UploadedImage\Concerns;

trait SavesBeforeStoring
{
    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $path
     * @param  array|string  $options
     * @return string|false
     */
    public function store($path, $options = [])
    {
        if ($this->isModified()) {
            $this->save();
        }

        return parent::store($path, $options);
    }

    /**
     * Store the uploaded file on a filesystem disk with public visibility.
     *
     * @param  string  $path
     * @param  array|string  $options
     * @return string|false
     */
    public function storePublicly($path, $options = [])
    {
        if ($this->isModified()) {
            $this->save();
        }

        return parent::storePublicly($path, $options);
    }

    /**
     * Store the uploaded file on a filesystem disk with public visibility.
     *
     * @param  string  $path
     * @param  string  $name
     * @param  array|string  $options
     * @return string|false
     */
    public function storePubliclyAs($path, $name, $options = [])
    {
        if ($this->isModified()) {
            $this->save();
        }

        return parent::storePubliclyAs($path, $name, $options);
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $path
     * @param  string  $name
     * @param  array|string  $options
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
