<?php
declare(strict_types=1);
namespace System\Core\Razr;

use System\Core\Razr\Exception\SyntaxErrorException;

class Parser
{
    protected $engine;
    protected $stream;
    protected $filename;
    protected $variables;

    /**
     * Constructor.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Parsing method.
     *
     * @param  TokenStream $stream
     * @param  string      $filename
     * @return string
     */
    public function parse($stream, $filename = null)
    {
        $this->stream = $stream;
        $this->filename = $filename;
        $this->variables = array();

        return $this->parseMain();
    }

    /**
     * Parse main.
     *
     * @return string
     */
    public function parseMain()
    {
        $out = '';

        while ($token = $this->stream->next()) {
            if ($token->test(T_COMMENT, '/* OUTPUT */')) {
                $out .= $this->parseOutput();
            } elseif ($token->test(T_COMMENT, '/* DIRECTIVE */')) {
                $out .= $this->parseDirective();
            } else {
                $out .= $token->getValue();
            }
        }

        if ($this->variables) {
            $info = sprintf('<?php /* %s */ extract(%s, EXTR_SKIP) ?>', $this->filename, str_replace("\n", '', var_export($this->variables, true)));
        } else {
            $info = sprintf('<?php /* %s */ ?>', $this->filename);
        }

        return $info.$out;
    }

    /**
     * Parse output.
     *
     * @return string
     */
    public function parseOutput()
    {
        $inner = '';
        $token = $this->stream->get();

        while ($token !== null && !$token->test(T_CLOSE_TAG)) {
            $inner .= $this->parseExpression();
            $token = $this->stream->get();
        }

        // "@()" / "@( )" produce an empty expression that would become escape(())
        if (trim($inner, " \t\n\r\0\x0B()") === '') {
            throw new SyntaxErrorException(sprintf('Empty output expression "@()" at line %d in file %s.', $token ? $token->getLine() : 0, $this->filename));
        }

        return "echo \$this->escape($inner) ";
    }

    /**
     * Parse directive.
     *
     * @return string
     */
    public function parseDirective()
    {
        $out = '';
        $token = $this->stream->get();

        foreach ($this->engine->getDirectives() as $directive) {
            $result = $directive->parse($this->stream, $this->stream->get());
            if ($result !== null && $result !== '') {
                $out = $result;
                break;
            }
        }

        // No directive consumed the token
        if ($out === '' && $token !== null && $this->stream->get() === $token) {
            // PHP control-flow keywords (@break, @continue, @return, ...) pass through as raw statements
            if ($token->getType() !== T_STRING) {
                $this->stream->next();
                return $token->getValue();
            }

            // Unknown directive: fail clearly instead of emitting broken PHP
            throw new SyntaxErrorException(sprintf(
                'Unknown directive "@%s" at line %d in file %s. Escape a literal "@" as "@@", or use @raw().',
                $token->getValue(),
                $token->getLine(),
                $this->filename
            ));
        }

        return $out;
    }

    /**
     * Parse expression.
     *
     * @return string
     */
    public function parseExpression()
    {
        $out = '';
        $brackets = array();

        do {

            if ($token = $this->stream->nextIf(T_STRING)) {

                $name = $token->getValue();

                if ($this->stream->test('(') && $this->engine->getFunction($name)) {
                    $out .= sprintf("\$this->callFunction('%s', array%s)", $name, $this->parseExpression());
                } else {
                    $out .= $name;
                }

            } elseif ($token = $this->stream->nextIf(T_VARIABLE)) {

                $out .= $this->parseSubscript($var = $token->getValue());
                $this->variables[ltrim($var, '$')] = null;

            } else {

                $token = $this->stream->next();

                if ($token->test(array('(', '['))) {
                    array_push($brackets, $token);
                } elseif ($token->test(array(')', ']'))) {
                    array_pop($brackets);
                }

                $out .= $token->getValue();
            }

        } while (!empty($brackets));

        return $out;
    }

    /**
     * Parse subscript.
     *
     * @param  string $out
     * @return string
     */
    public function parseSubscript($out)
    {
        while (true) {
            if ($this->stream->nextIf('.')) {

                if (!$this->stream->test(T_STRING)) {
                    $this->stream->prev();
                    break;
                }

                $val = $this->stream->next()->getValue();
                $out = sprintf("\$this->getAttribute(%s, '%s'", $out, $val);

                if ($this->stream->test('(')) {
                    $out .= sprintf(", array%s, 'method')", $this->parseExpression());
                } else {
                    $out .= ")";
                }

            } elseif ($this->stream->nextIf('[')) {

                $exp = '';

                while (!$this->stream->test(']')) {
                    $exp .= $this->parseExpression();
                }

                $this->stream->expect(']');
                $this->stream->next();

                $out = sprintf("\$this->getAttribute(%s, %s, array(), 'array')", $out, $exp);

            } else {
                break;
            }
        }

        return $out;
    }
}
