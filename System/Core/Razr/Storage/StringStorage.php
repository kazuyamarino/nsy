<?php
declare(strict_types=1);
namespace System\Core\Razr\Storage;

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
