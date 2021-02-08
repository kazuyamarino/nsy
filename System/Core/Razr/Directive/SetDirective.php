<?php
namespace System\Core\Razr\Directive;

use System\Core\Razr\Token;
use System\Core\Razr\TokenStream;

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
