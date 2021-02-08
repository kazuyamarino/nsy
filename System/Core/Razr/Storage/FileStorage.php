<?php
namespace System\Core\Razr\Storage;

class FileStorage extends Storage
{
    /**
     * @{inheritdoc}
     */
    public function getContent()
    {
        return file_get_contents($this->template);
    }
}
