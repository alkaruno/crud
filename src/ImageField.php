<?php

namespace Xplosio\Crud;

class ImageField extends Field
{
    public $path;

    public function path($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getDatabaseValue($value)
    {
        // TODO удалять изображение, что было залито ранее.

        if (!isset($_FILES[$this->name]) || $_FILES[$this->name]['size'] == 0 || $_FILES[$this->name]['error'] != 0) {
            return $value;
        }

        if (file_exists($value)) {
            unlink($value);
        }

        $file = $_FILES[$this->name];

        $path = $this->path . DIRECTORY_SEPARATOR . $file['name'];
        $counter = 1;
        while (file_exists($path)) {
            $info = pathinfo($file['name']);
            $path = $this->path . DIRECTORY_SEPARATOR . $info['filename'] . ' (' . $counter++ . ').' . $info['extension'];
        }

        move_uploaded_file($file['tmp_name'], $path);

        return $path;
    }
}
