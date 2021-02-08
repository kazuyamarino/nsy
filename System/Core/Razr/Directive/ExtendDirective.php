<?php
namespace System\Core\Razr\Directive;

use System\Core\Razr\Token;
use System\Core\Razr\TokenStream;

class ExtendDirective extends Directive
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'extend';
    }

    /**
     * @{inheritdoc}
     */
    public function parse(TokenStream $stream, Token $token)
    {
        if ($stream->nextIf('extend') && $stream->expect('(')) {
            return sprintf("\$this->extend%s", $this->parser->parseExpression());
        }
    }
}
