<?php
namespace System\Core\Razr\Directive;

use System\Core\Razr\Token;
use System\Core\Razr\TokenStream;

class RawDirective extends Directive
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'raw';
    }

    /**
     * @{inheritdoc}
     */
    public function parse(TokenStream $stream, Token $token)
    {
        if ($stream->nextIf('raw') && $stream->expect('(')) {

            $out = 'echo';

            while (!$stream->test(T_CLOSE_TAG)) {
                $out .= $this->parser->parseExpression();
            }

            return $out;
        }
    }
}
