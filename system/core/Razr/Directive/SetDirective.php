<?php
namespace System\Razr\Directive;

use System\Razr\Token;
use System\Razr\TokenStream;

class SetDirective extends Directive
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'set';
    }

    /**
     * @{inheritdoc}
     */
    public function parse(TokenStream $stream, Token $token)
    {
        if ($stream->nextIf('set') && $stream->expect('(')) {

            $out = '';

            while (!$stream->test(T_CLOSE_TAG)) {
                $out .= $this->parser->parseExpression();
            }

            return $out;
        }
    }
}
