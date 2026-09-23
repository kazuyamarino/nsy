<?php
declare(strict_types=1);
namespace System\Core\Razr\Directive;

use System\Core\Razr\Token;
use System\Core\Razr\TokenStream;

/**
 * FunctionDirective — reusable directive for extension authors.
 *
 * NOT registered by CoreExtension (the core uses directives + functions directly).
 * Register your own from an {@see ExtensionInterface} to expose a callable as
 * a template directive, e.g.:
 *
 *     $engine->addDirective(new FunctionDirective('greet', fn($name) => "Hi $name", true));
 *     // template: @greet('World')
 */
class FunctionDirective extends Directive
{
    protected $function;
    protected $escape;

    /**
     * Constructor.
     *
     * @param string   $name
     * @param callable $function
     * @param bool     $escape
     */
    public function __construct($name, $function, $escape = false)
    {
        $this->name     = $name;
        $this->function = $function;
        $this->escape   = $escape;
    }

    /**
     * Calls the function with an array of arguments.
     *
     * @param  array $args
     * @return mixed
     */
    public function call(array $args = array())
    {
        return call_user_func_array($this->function, $args);
    }

    /**
     * @{inheritdoc}
     */
    public function parse(TokenStream $stream, Token $token)
    {
        if ($stream->nextIf($this->name)) {

            $out = sprintf("\$this->getDirective('%s')->call(%s)", $this->name, $stream->test('(') ? 'array' . $this->parser->parseExpression() : '');

            if ($this->escape) {
                $out = sprintf("\$this->escape(%s)", $out);
            }

            return sprintf("echo(%s)", $out);
        }
    }
}
