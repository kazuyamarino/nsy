<?php
namespace System\Razr\Extension;

use System\Razr\Engine;

interface ExtensionInterface
{
    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Initializes the extension.
     */
    public function initialize(Engine $engine);
}
