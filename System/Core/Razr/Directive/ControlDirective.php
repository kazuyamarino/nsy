<?php
declare(strict_types=1);
namespace System\Core\Razr\Directive;

use System\Core\Razr\Token;
use System\Core\Razr\TokenStream;

class ControlDirective extends Directive
{
    protected $control;
    protected $controlEnd;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'control';
        $this->control = array(T_FOR, T_FOREACH, T_IF, T_ELSEIF, T_ELSE, T_WHILE, T_SWITCH, T_CASE, T_DEFAULT);
        $this->controlEnd = array(T_ENDFOR, T_ENDFOREACH, T_ENDIF, T_ENDWHILE, T_ENDSWITCH);
    }

    /**
     * @{inheritdoc}
     */
    public function parse(TokenStream $stream, Token $token)
    {
        $control = in_array($token->getType(), $this->control);

        if ($control || in_array($token->getType(), $this->controlEnd)) {

            $out = '';

            while (!$stream->test(T_CLOSE_TAG)) {
                $out .= $this->parser->parseExpression();
            }

            if ($control) {
                $out .= ':';
            }

            return $out;
        }
    }
}
