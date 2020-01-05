<?php

namespace System\Razr\Storage;

class StringStorage extends Storage
{
    /**
     * @{inheritdoc}
     */
    public function getContent()
    {
        return $this->template;
    }
}
