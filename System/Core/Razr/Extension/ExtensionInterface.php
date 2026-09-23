<?php
declare(strict_types=1);
namespace System\Core\Razr\Extension;

use System\Core\Razr\Engine;

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
