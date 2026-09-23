<?php
declare(strict_types=1);
namespace System\Core\Razr;

use System\Core\Razr\Directive\Directive;
use System\Core\Razr\Directive\DirectiveInterface;
use System\Core\Razr\Exception\RuntimeException;
use System\Core\Razr\Extension\CoreExtension;
use System\Core\Razr\Extension\ExtensionInterface;
use System\Core\Razr\Loader\LoaderInterface;
use System\Core\Razr\Storage\FileStorage;
use System\Core\Razr\Storage\Storage;
use System\Core\Razr\Storage\StringStorage;

class Engine
{
    const VERSION     = '0.10.0';

    const ANY_CALL    = 'any';
    const ARRAY_CALL  = 'array';
    const METHOD_CALL = 'method';

    protected Lexer $lexer;
    protected Parser $parser;
    protected ?string $current = null;
    protected string $charset = 'UTF-8';
    protected array $parents = array();
    protected array $globals = array();
    protected array $directives = array();
    protected array $functions = array();
    protected array $extensions = array();
    protected array $cache = array();
    protected array $cacheFiles = array();
    protected ?string $cachePath;
    protected LoaderInterface $loader;

    private bool $initialized = false;
    private ?Storage $template = null;
    private ?array $parameters = null;
    private ?array $extensionMtimes = null;
    private int $renderDepth = 0;
    private static array $classes = array();

    /**
     * Constructor.
     *
     * @param LoaderInterface $loader
     * @param string          $cachePath
     */
    public function __construct(LoaderInterface $loader, ?string $cachePath = null)
    {
        $this->loader    = $loader;
        $this->lexer     = new Lexer($this);
        $this->parser    = new Parser($this);
        $this->cachePath = $cachePath;

        $this->addExtension(new CoreExtension);
    }

    /**
     * Gets the lexer.
     *
     * @return Lexer
     */
    public function getLexer(): Lexer
    {
        return $this->lexer;
    }

    /**
     * Gets the parser.
     *
     * @return Parser
     */
    public function getParser(): Parser
    {
        return $this->parser;
    }

    /**
     * Gets the charset.
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Sets the charset.
     *
     * @param string $charset
     */
    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * Gets all global parameters.
     *
     * @return array
     */
    public function getGlobals(): array
    {
        return $this->globals;
    }

    /**
     * Adds a global parameter.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addGlobal(string $name, mixed $value): void
    {
        $this->globals[$name] = $value;
    }

    /**
     * Gets a directives.
     *
     * @param  string $name
     * @return Directive
     */
    public function getDirective(string $name): ?DirectiveInterface
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return isset($this->directives[$name]) ? $this->directives[$name] : null;
    }

    /**
     * Gets the directives.
     *
     * @return array
     */
    public function getDirectives(): array
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->directives;
    }

    /**
     * Adds a directive.
     *
     * @param  DirectiveInterface $directive
     * @throws Exception\RuntimeException
     */
    public function addDirective(DirectiveInterface $directive): void
    {
        if ($this->initialized) {
            throw new RuntimeException(sprintf('Unable to add directive "%s" as they have already been initialized.', $directive->getName()));
        }

        $directive->setEngine($this);

        $this->directives[$directive->getName()] = $directive;
    }

    /**
     * Gets a function.
     *
     * @param  string $name
     * @return callable
     */
    public function getFunction(string $name): ?callable
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return isset($this->functions[$name]) ? $this->functions[$name] : null;
    }

    /**
     * Gets the functions.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->functions;
    }

    /**
     * Adds a function.
     *
     * @param  string   $name
     * @param  callable $function
     * @throws RuntimeException
     */
    public function addFunction(string $name, callable $function): void
    {
        if ($this->initialized) {
            throw new RuntimeException(sprintf('Unable to add function "%s" as they have already been initialized.', $name));
        }

        $this->functions[$name] = $function;
    }

    /**
     * Gets an extension.
     *
     * @param  string $name
     * @return ExtensionInterface
     */
    public function getExtension(string $name): ?ExtensionInterface
    {
        return isset($this->extensions[$name]) ? $this->extensions[$name] : null;
    }

    /**
     * Gets the extensions.
     *
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * Adds an extension.
     *
     * @param  ExtensionInterface $extension
     * @throws Exception\RuntimeException
     */
    public function addExtension(ExtensionInterface $extension): void
    {
        if ($this->initialized) {
            throw new RuntimeException(sprintf('Unable to add extension "%s" as they have already been initialized.', $extension->getName()));
        }

        $this->extensions[$extension->getName()] = $extension;
    }

    /**
     * Gets an attribute value from an array or object.
     *
     * @param  mixed  $object
     * @param  mixed  $name
     * @param  array  $args
     * @param  string $type
     * @throws \BadMethodCallException
     * @throws \Exception
     * @return mixed
     */
    public function getAttribute($object, $name, array $args = array(), $type = self::ANY_CALL)
    {
        // array
        if ($type == self::ANY_CALL || $type == self::ARRAY_CALL) {

            $key = is_bool($name) || is_float($name) ? (int) $name : $name;

            if ((is_array($object) && array_key_exists($key, $object)) || ($object instanceof \ArrayAccess && isset($object[$key]))) {
                return $object[$key];
            }

            if ($type == self::ARRAY_CALL) {
                return null;
            }
        }

        // object
        if (!is_object($object)) {
            return null;
        }

        // property
        if ($type == self::ANY_CALL && isset($object->$name)) {
            return $object->$name;
        }

        // method
        $call  = false;
        $name  = (string) $name;
        $item  = strtolower($name);
        $class = get_class($object);

        if (!isset(self::$classes[$class])) {
            self::$classes[$class] = array_change_key_case(array_flip(get_class_methods($object)));
        }

        if (!isset(self::$classes[$class][$item])) {
            if (isset(self::$classes[$class]["get$item"])) {
                $name = "get$name";
            } elseif (isset(self::$classes[$class]["is$item"])) {
                $name = "is$name";
            } elseif (isset(self::$classes[$class]["__call"])) {
                $call = true;
            } else {
                return null;
            }
        }

        try {
            return call_user_func_array(array($object, $name), $args);
        } catch (\BadMethodCallException $e) {
            if (!$call) { throw $e;
            }
        }
    }

    /**
     * Calls a function with an array of arguments.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function callFunction(string $name, array $args = array()): mixed
    {
        return call_user_func_array($this->getFunction($name), $args);
    }

    /**
     * Decorates a template with another template.
     *
     * @param string $template
     */
    public function extend(string $template): void
    {
        $this->parents[$this->current] = $template;
    }

    /**
     * Escapes a html entities in a string.
     *
     * @param  mixed $value
     * @return string
     */
    public function escape($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, $this->charset, false) : $value;
    }

    /**
     * Renders a template.
     *
     * @param  string $name
     * @param  array  $parameters
     * @throws RuntimeException
     * @return string
     */
    public function render(string $name, array $parameters = array()): string
    {
        $this->renderDepth++;

        try {
            $storage = $this->load($name);
            $parameters = array_replace($this->getGlobals(), $parameters);

            $this->current = $key = sha1(serialize($storage));
            $this->parents[$key] = null;

            if (false === $content = $this->evaluate($storage, $parameters)) {
                throw new RuntimeException('The template cannot be rendered.');
            }

            if ($this->parents[$key]) {
                $content = $this->render($this->parents[$key], $parameters);
            }

            return $content;
        } finally {
            $this->renderDepth--;

            // Reset blocks after the outermost render so @block/@extend never leak across renders
            if ($this->renderDepth === 0) {
                $core = $this->getExtension('core');
                if ($core instanceof CoreExtension) {
                    $core->resetBlocks();
                }
            }
        }
    }

    /**
     * Evaluates a template.
     *
     * @param  Storage $template
     * @param  array   $parameters
     * @return string|false
     */
    protected function evaluate(Storage $template, array $parameters = array())
    {
        $this->template = $template;
        $this->parameters = $parameters;
        unset($template, $parameters);

        extract($this->parameters, EXTR_SKIP);
        $this->parameters = null;

        if ($this->template instanceof FileStorage) {

            ob_start();
            include $this->template;
            $this->template = null;

            return ob_get_clean();

        } elseif ($this->template instanceof StringStorage) {

            ob_start();
            eval('; ?>'.$this->template.'<?php ;');
            $this->template = null;

            return ob_get_clean();
        }

        return false;
    }

    /**
     * Compiles a template.
     *
     * @param  string $source
     * @param  string $filename
     * @return string
     */
    protected function compile($source, $filename = null)
    {
        $tokens = $this->lexer->tokenize($source, $filename);
        $source = $this->parser->parse($tokens, $filename);

        return $source;
    }

    /**
     * Loads a template.
     *
     * @param  string $name
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @return Storage
     */
    protected function load($name)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        // In-memory cache hit — but re-validate freshness (safe for long-running processes)
        if (isset($this->cache[$name])) {
            $cachedFile = $this->cacheFiles[$name] ?? null;

            if ($cachedFile === null || (is_file($cachedFile) && $this->isTemplateFresh($name, filemtime($cachedFile)))) {
                return $this->cache[$name];
            }

            unset($this->cache[$name], $this->cacheFiles[$name]);
        }

        // Use the loader's cache key so distinct templates that share a name never collide
        $cache = false;
        if ($this->cachePath) {
            $cache = sprintf('%s/%s.cache', $this->cachePath, sha1((string) $this->loader->getCacheKey($name)));
        }

        if (!$cache) {

            $storage = new StringStorage($this->compile($this->loader->getSource($name), $name));

        } else {

            if (!is_file($cache) || !$this->isTemplateFresh($name, filemtime($cache))) {
                $this->writeCacheFile($cache, $this->compile($this->loader->getSource($name), $name));
            }

            $storage = new FileStorage($cache);
        }

        $this->cacheFiles[$name] = $cache ?: null;

        return $this->cache[$name] = $storage;
    }

    /**
     * Initializes the extensions.
     *
     * @return void
     */
    protected function initialize()
    {
        foreach ($this->extensions as $extension) {
            $extension->initialize($this);
        }

        $this->initialized = true;
    }

    /**
     * Writes cache file
     *
     * @param  string $file
     * @param  string $content
     * @throws RuntimeException
     */
    protected function writeCacheFile($file, $content)
    {
        $dir = dirname($file);

        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException("Unable to create the cache directory ($dir).");
            }
        } elseif (!is_writable($dir)) {
            throw new RuntimeException("Unable to write in the cache directory ($dir).");
        }

        // Atomic write: temp file + rename, so concurrent requests never read a partial cache
        $tmp = $file . '.' . uniqid('', true) . '.tmp';

        if (false === @file_put_contents($tmp, $content, LOCK_EX)) {
            @unlink($tmp);
            throw new RuntimeException("Failed to write cache file ($tmp).");
        }

        if (!@rename($tmp, $file)) {
            @unlink($tmp);
            throw new RuntimeException("Failed to replace cache file ($file).");
        }
    }

    /**
     * Checks if template is fresh
     *
     * @param  string $name
     * @param  int    $time
     * @return bool
     */
    protected function isTemplateFresh($name, $time)
    {
        // Memoize extension file mtimes per-process (ReflectionObject + filemtime is costly per render)
        if ($this->extensionMtimes === null) {
            $this->extensionMtimes = array();
            foreach ($this->extensions as $extension) {
                $r = new \ReflectionObject($extension);
                $this->extensionMtimes[] = filemtime($r->getFileName());
            }
        }

        foreach ($this->extensionMtimes as $mtime) {
            if ($mtime > $time) {
                return false;
            }
        }

        return $this->loader->isFresh($name, $time);
    }
}
