<?php
namespace System\Core\Razr\Directive;

use System\Core\Razr\Engine;
use System\Core\Razr\Token;
use System\Core\Razr\TokenStream;

interface DirectiveInterface
{
    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the engine.
     *
     * @param $engine
     */
    public function setEngine(Engine $engine);

    /**
     * Parses a directive.
     *
     * @param  TokenStream $stream
     * @param  Token       $token
     * @return string
     */
    public function parse(TokenStream $stream, Token $token);
}
