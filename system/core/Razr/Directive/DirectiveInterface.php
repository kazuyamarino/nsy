<?php
namespace System\Razr\Directive;

use System\Razr\Engine;
use System\Razr\Token;
use System\Razr\TokenStream;

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
