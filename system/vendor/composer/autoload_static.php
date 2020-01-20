<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInityour_suffixed
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '182b55b2b69e7e1b561ee34d36775279' => __DIR__ . '/../../..' . '/system/core/NSY_Helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'System\\' => 7,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'C' => 
        array (
            'Carbon\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'System\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'PhpOption\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'Carbon\\' => 
        array (
            0 => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon',
        ),
    );

    public static $prefixesPsr0 = array (
        'F' => 
        array (
            'FtpClient' => 
            array (
                0 => __DIR__ . '/..' . '/nicolab/php-ftp-client/src',
            ),
        ),
    );

    public static $classMap = array (
        'Carbon\\Carbon' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Carbon.php',
        'Carbon\\CarbonImmutable' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonImmutable.php',
        'Carbon\\CarbonInterface' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonInterface.php',
        'Carbon\\CarbonInterval' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonInterval.php',
        'Carbon\\CarbonPeriod' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonPeriod.php',
        'Carbon\\CarbonTimeZone' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonTimeZone.php',
        'Carbon\\Cli\\Invoker' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Cli/Invoker.php',
        'Carbon\\Exceptions\\BadUnitException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/BadUnitException.php',
        'Carbon\\Exceptions\\InvalidDateException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidDateException.php',
        'Carbon\\Exceptions\\NotAPeriodException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/NotAPeriodException.php',
        'Carbon\\Exceptions\\ParseErrorException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/ParseErrorException.php',
        'Carbon\\Factory' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Factory.php',
        'Carbon\\FactoryImmutable' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/FactoryImmutable.php',
        'Carbon\\Language' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Language.php',
        'Carbon\\Laravel\\ServiceProvider' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Laravel/ServiceProvider.php',
        'Carbon\\Traits\\Boundaries' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Boundaries.php',
        'Carbon\\Traits\\Cast' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Cast.php',
        'Carbon\\Traits\\Comparison' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Comparison.php',
        'Carbon\\Traits\\Converter' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Converter.php',
        'Carbon\\Traits\\Creator' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Creator.php',
        'Carbon\\Traits\\Date' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Date.php',
        'Carbon\\Traits\\Difference' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Difference.php',
        'Carbon\\Traits\\Localization' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Localization.php',
        'Carbon\\Traits\\Macro' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Macro.php',
        'Carbon\\Traits\\Mixin' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Mixin.php',
        'Carbon\\Traits\\Modifiers' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Modifiers.php',
        'Carbon\\Traits\\Mutability' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Mutability.php',
        'Carbon\\Traits\\Options' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Options.php',
        'Carbon\\Traits\\Rounding' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Rounding.php',
        'Carbon\\Traits\\Serialization' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Serialization.php',
        'Carbon\\Traits\\Test' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Test.php',
        'Carbon\\Traits\\Timestamp' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Timestamp.php',
        'Carbon\\Traits\\Units' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Units.php',
        'Carbon\\Traits\\Week' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Week.php',
        'Carbon\\Translator' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Translator.php',
        'Dotenv\\Dotenv' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Dotenv.php',
        'Dotenv\\Environment\\AbstractVariables' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/AbstractVariables.php',
        'Dotenv\\Environment\\Adapter\\AdapterInterface' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/Adapter/AdapterInterface.php',
        'Dotenv\\Environment\\Adapter\\ApacheAdapter' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/Adapter/ApacheAdapter.php',
        'Dotenv\\Environment\\Adapter\\ArrayAdapter' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/Adapter/ArrayAdapter.php',
        'Dotenv\\Environment\\Adapter\\EnvConstAdapter' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/Adapter/EnvConstAdapter.php',
        'Dotenv\\Environment\\Adapter\\PutenvAdapter' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/Adapter/PutenvAdapter.php',
        'Dotenv\\Environment\\Adapter\\ServerConstAdapter' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/Adapter/ServerConstAdapter.php',
        'Dotenv\\Environment\\DotenvFactory' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/DotenvFactory.php',
        'Dotenv\\Environment\\DotenvVariables' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/DotenvVariables.php',
        'Dotenv\\Environment\\FactoryInterface' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/FactoryInterface.php',
        'Dotenv\\Environment\\VariablesInterface' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Environment/VariablesInterface.php',
        'Dotenv\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/ExceptionInterface.php',
        'Dotenv\\Exception\\InvalidFileException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/InvalidFileException.php',
        'Dotenv\\Exception\\InvalidPathException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/InvalidPathException.php',
        'Dotenv\\Exception\\ValidationException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/ValidationException.php',
        'Dotenv\\Lines' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Lines.php',
        'Dotenv\\Loader' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Loader.php',
        'Dotenv\\Parser' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Parser.php',
        'Dotenv\\Regex\\Error' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Regex/Error.php',
        'Dotenv\\Regex\\Regex' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Regex/Regex.php',
        'Dotenv\\Regex\\Result' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Regex/Result.php',
        'Dotenv\\Regex\\Success' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Regex/Success.php',
        'Dotenv\\Validator' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Validator.php',
        'FtpClient\\FtpClient' => __DIR__ . '/..' . '/nicolab/php-ftp-client/src/FtpClient/FtpClient.php',
        'FtpClient\\FtpException' => __DIR__ . '/..' . '/nicolab/php-ftp-client/src/FtpClient/FtpException.php',
        'FtpClient\\FtpWrapper' => __DIR__ . '/..' . '/nicolab/php-ftp-client/src/FtpClient/FtpWrapper.php',
        'PhpOption\\LazyOption' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/LazyOption.php',
        'PhpOption\\None' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/None.php',
        'PhpOption\\Option' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/Option.php',
        'PhpOption\\Some' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/Some.php',
        'Symfony\\Component\\Translation\\Catalogue\\AbstractOperation' => __DIR__ . '/..' . '/symfony/translation/Catalogue/AbstractOperation.php',
        'Symfony\\Component\\Translation\\Catalogue\\MergeOperation' => __DIR__ . '/..' . '/symfony/translation/Catalogue/MergeOperation.php',
        'Symfony\\Component\\Translation\\Catalogue\\OperationInterface' => __DIR__ . '/..' . '/symfony/translation/Catalogue/OperationInterface.php',
        'Symfony\\Component\\Translation\\Catalogue\\TargetOperation' => __DIR__ . '/..' . '/symfony/translation/Catalogue/TargetOperation.php',
        'Symfony\\Component\\Translation\\Command\\XliffLintCommand' => __DIR__ . '/..' . '/symfony/translation/Command/XliffLintCommand.php',
        'Symfony\\Component\\Translation\\DataCollectorTranslator' => __DIR__ . '/..' . '/symfony/translation/DataCollectorTranslator.php',
        'Symfony\\Component\\Translation\\DataCollector\\TranslationDataCollector' => __DIR__ . '/..' . '/symfony/translation/DataCollector/TranslationDataCollector.php',
        'Symfony\\Component\\Translation\\DependencyInjection\\TranslationDumperPass' => __DIR__ . '/..' . '/symfony/translation/DependencyInjection/TranslationDumperPass.php',
        'Symfony\\Component\\Translation\\DependencyInjection\\TranslationExtractorPass' => __DIR__ . '/..' . '/symfony/translation/DependencyInjection/TranslationExtractorPass.php',
        'Symfony\\Component\\Translation\\DependencyInjection\\TranslatorPass' => __DIR__ . '/..' . '/symfony/translation/DependencyInjection/TranslatorPass.php',
        'Symfony\\Component\\Translation\\DependencyInjection\\TranslatorPathsPass' => __DIR__ . '/..' . '/symfony/translation/DependencyInjection/TranslatorPathsPass.php',
        'Symfony\\Component\\Translation\\Dumper\\CsvFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/CsvFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\DumperInterface' => __DIR__ . '/..' . '/symfony/translation/Dumper/DumperInterface.php',
        'Symfony\\Component\\Translation\\Dumper\\FileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/FileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\IcuResFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/IcuResFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\IniFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/IniFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\JsonFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/JsonFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\MoFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/MoFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\PhpFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/PhpFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\PoFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/PoFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\QtFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/QtFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\XliffFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/XliffFileDumper.php',
        'Symfony\\Component\\Translation\\Dumper\\YamlFileDumper' => __DIR__ . '/..' . '/symfony/translation/Dumper/YamlFileDumper.php',
        'Symfony\\Component\\Translation\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/symfony/translation/Exception/ExceptionInterface.php',
        'Symfony\\Component\\Translation\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/symfony/translation/Exception/InvalidArgumentException.php',
        'Symfony\\Component\\Translation\\Exception\\InvalidResourceException' => __DIR__ . '/..' . '/symfony/translation/Exception/InvalidResourceException.php',
        'Symfony\\Component\\Translation\\Exception\\LogicException' => __DIR__ . '/..' . '/symfony/translation/Exception/LogicException.php',
        'Symfony\\Component\\Translation\\Exception\\NotFoundResourceException' => __DIR__ . '/..' . '/symfony/translation/Exception/NotFoundResourceException.php',
        'Symfony\\Component\\Translation\\Exception\\RuntimeException' => __DIR__ . '/..' . '/symfony/translation/Exception/RuntimeException.php',
        'Symfony\\Component\\Translation\\Extractor\\AbstractFileExtractor' => __DIR__ . '/..' . '/symfony/translation/Extractor/AbstractFileExtractor.php',
        'Symfony\\Component\\Translation\\Extractor\\ChainExtractor' => __DIR__ . '/..' . '/symfony/translation/Extractor/ChainExtractor.php',
        'Symfony\\Component\\Translation\\Extractor\\ExtractorInterface' => __DIR__ . '/..' . '/symfony/translation/Extractor/ExtractorInterface.php',
        'Symfony\\Component\\Translation\\Extractor\\PhpExtractor' => __DIR__ . '/..' . '/symfony/translation/Extractor/PhpExtractor.php',
        'Symfony\\Component\\Translation\\Extractor\\PhpStringTokenParser' => __DIR__ . '/..' . '/symfony/translation/Extractor/PhpStringTokenParser.php',
        'Symfony\\Component\\Translation\\Formatter\\IntlFormatter' => __DIR__ . '/..' . '/symfony/translation/Formatter/IntlFormatter.php',
        'Symfony\\Component\\Translation\\Formatter\\IntlFormatterInterface' => __DIR__ . '/..' . '/symfony/translation/Formatter/IntlFormatterInterface.php',
        'Symfony\\Component\\Translation\\Formatter\\MessageFormatter' => __DIR__ . '/..' . '/symfony/translation/Formatter/MessageFormatter.php',
        'Symfony\\Component\\Translation\\Formatter\\MessageFormatterInterface' => __DIR__ . '/..' . '/symfony/translation/Formatter/MessageFormatterInterface.php',
        'Symfony\\Component\\Translation\\IdentityTranslator' => __DIR__ . '/..' . '/symfony/translation/IdentityTranslator.php',
        'Symfony\\Component\\Translation\\Loader\\ArrayLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/ArrayLoader.php',
        'Symfony\\Component\\Translation\\Loader\\CsvFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/CsvFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\FileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/FileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\IcuDatFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/IcuDatFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\IcuResFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/IcuResFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\IniFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/IniFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\JsonFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/JsonFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\LoaderInterface' => __DIR__ . '/..' . '/symfony/translation/Loader/LoaderInterface.php',
        'Symfony\\Component\\Translation\\Loader\\MoFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/MoFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\PhpFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/PhpFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\PoFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/PoFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\QtFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/QtFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\XliffFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/XliffFileLoader.php',
        'Symfony\\Component\\Translation\\Loader\\YamlFileLoader' => __DIR__ . '/..' . '/symfony/translation/Loader/YamlFileLoader.php',
        'Symfony\\Component\\Translation\\LoggingTranslator' => __DIR__ . '/..' . '/symfony/translation/LoggingTranslator.php',
        'Symfony\\Component\\Translation\\MessageCatalogue' => __DIR__ . '/..' . '/symfony/translation/MessageCatalogue.php',
        'Symfony\\Component\\Translation\\MessageCatalogueInterface' => __DIR__ . '/..' . '/symfony/translation/MessageCatalogueInterface.php',
        'Symfony\\Component\\Translation\\MetadataAwareInterface' => __DIR__ . '/..' . '/symfony/translation/MetadataAwareInterface.php',
        'Symfony\\Component\\Translation\\Reader\\TranslationReader' => __DIR__ . '/..' . '/symfony/translation/Reader/TranslationReader.php',
        'Symfony\\Component\\Translation\\Reader\\TranslationReaderInterface' => __DIR__ . '/..' . '/symfony/translation/Reader/TranslationReaderInterface.php',
        'Symfony\\Component\\Translation\\Translator' => __DIR__ . '/..' . '/symfony/translation/Translator.php',
        'Symfony\\Component\\Translation\\TranslatorBagInterface' => __DIR__ . '/..' . '/symfony/translation/TranslatorBagInterface.php',
        'Symfony\\Component\\Translation\\Util\\ArrayConverter' => __DIR__ . '/..' . '/symfony/translation/Util/ArrayConverter.php',
        'Symfony\\Component\\Translation\\Util\\XliffUtils' => __DIR__ . '/..' . '/symfony/translation/Util/XliffUtils.php',
        'Symfony\\Component\\Translation\\Writer\\TranslationWriter' => __DIR__ . '/..' . '/symfony/translation/Writer/TranslationWriter.php',
        'Symfony\\Component\\Translation\\Writer\\TranslationWriterInterface' => __DIR__ . '/..' . '/symfony/translation/Writer/TranslationWriterInterface.php',
        'Symfony\\Contracts\\Translation\\LocaleAwareInterface' => __DIR__ . '/..' . '/symfony/translation-contracts/LocaleAwareInterface.php',
        'Symfony\\Contracts\\Translation\\Test\\TranslatorTest' => __DIR__ . '/..' . '/symfony/translation-contracts/Test/TranslatorTest.php',
        'Symfony\\Contracts\\Translation\\TranslatorInterface' => __DIR__ . '/..' . '/symfony/translation-contracts/TranslatorInterface.php',
        'Symfony\\Contracts\\Translation\\TranslatorTrait' => __DIR__ . '/..' . '/symfony/translation-contracts/TranslatorTrait.php',
        'Symfony\\Polyfill\\Ctype\\Ctype' => __DIR__ . '/..' . '/symfony/polyfill-ctype/Ctype.php',
        'Symfony\\Polyfill\\Mbstring\\Mbstring' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/Mbstring.php',
        'System\\Controllers\\Welcome' => __DIR__ . '/../../..' . '/system/controllers/Welcome.php',
        'System\\Core\\Migration_Impl' => __DIR__ . '/../../..' . '/system/core/NSY_Interface.php',
        'System\\Core\\NSY_AssetManager' => __DIR__ . '/../../..' . '/system/core/NSY_AssetManager.php',
        'System\\Core\\NSY_CSRF' => __DIR__ . '/../../..' . '/system/core/NSY_CSRF.php',
        'System\\Core\\NSY_Controller' => __DIR__ . '/../../..' . '/system/core/NSY_Controller.php',
        'System\\Core\\NSY_DB' => __DIR__ . '/../../..' . '/system/core/NSY_DB.php',
        'System\\Core\\NSY_Desk' => __DIR__ . '/../../..' . '/system/core/NSY_Desk.php',
        'System\\Core\\NSY_Migration' => __DIR__ . '/../../..' . '/system/core/NSY_Migration.php',
        'System\\Core\\NSY_Model' => __DIR__ . '/../../..' . '/system/core/NSY_Model.php',
        'System\\Core\\NSY_Router' => __DIR__ . '/../../..' . '/system/core/NSY_Router.php',
        'System\\Core\\NSY_System' => __DIR__ . '/../../..' . '/system/core/NSY_System.php',
        'System\\Core\\NSY_XSS_Filter' => __DIR__ . '/../../..' . '/system/core/NSY_XSS_Filter.php',
        'System\\Libraries\\Aliases' => __DIR__ . '/../../..' . '/system/libraries/Aliases.php',
        'System\\Libraries\\Assets' => __DIR__ . '/../../..' . '/system/libraries/Assets.php',
        'System\\Libraries\\Cookie' => __DIR__ . '/../../..' . '/system/libraries/Cookie.php',
        'System\\Libraries\\Curl' => __DIR__ . '/../../..' . '/system/libraries/Curl.php',
        'System\\Libraries\\Exception\\JsonException' => __DIR__ . '/../../..' . '/system/libraries/Exception/JsonException.php',
        'System\\Libraries\\File' => __DIR__ . '/../../..' . '/system/libraries/File.php',
        'System\\Libraries\\ImageResize' => __DIR__ . '/../../..' . '/system/libraries/ImageResize.php',
        'System\\Libraries\\ImageResizeException' => __DIR__ . '/../../..' . '/system/libraries/ImageResizeException.php',
        'System\\Libraries\\Ip' => __DIR__ . '/../../..' . '/system/libraries/Ip.php',
        'System\\Libraries\\Json' => __DIR__ . '/../../..' . '/system/libraries/Json.php',
        'System\\Libraries\\JsonLastError' => __DIR__ . '/../../..' . '/system/libraries/JsonLastError.php',
        'System\\Libraries\\LanguageCode' => __DIR__ . '/../../..' . '/system/libraries/LanguageCode.php',
        'System\\Libraries\\LanguageCodeCollection' => __DIR__ . '/../../..' . '/system/libraries/LanguageCodeCollection.php',
        'System\\Libraries\\LoadTime' => __DIR__ . '/../../..' . '/system/libraries/LoadTime.php',
        'System\\Libraries\\Request' => __DIR__ . '/../../..' . '/system/libraries/Request.php',
        'System\\Libraries\\Session' => __DIR__ . '/../../..' . '/system/libraries/Session.php',
        'System\\Libraries\\Str' => __DIR__ . '/../../..' . '/system/libraries/Str.php',
        'System\\Libraries\\Validate' => __DIR__ . '/../../..' . '/system/libraries/Validate.php',
        'System\\Migrations\\timnas' => __DIR__ . '/../../..' . '/system/migrations/timnas.php',
        'System\\Models\\Model_Welcome' => __DIR__ . '/../../..' . '/system/models/Model_Welcome.php',
        'System\\Modules\\Homepage\\Controllers\\Hello' => __DIR__ . '/../../..' . '/system/modules/homepage/controllers/Hello.php',
        'System\\Modules\\Homepage\\Models\\Model_Hello' => __DIR__ . '/../../..' . '/system/modules/homepage/models/Model_Hello.php',
        'System\\Razr\\Directive\\BlockDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/BlockDirective.php',
        'System\\Razr\\Directive\\ControlDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/ControlDirective.php',
        'System\\Razr\\Directive\\Directive' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/Directive.php',
        'System\\Razr\\Directive\\DirectiveInterface' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/DirectiveInterface.php',
        'System\\Razr\\Directive\\ExtendDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/ExtendDirective.php',
        'System\\Razr\\Directive\\FunctionDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/FunctionDirective.php',
        'System\\Razr\\Directive\\IncludeDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/IncludeDirective.php',
        'System\\Razr\\Directive\\RawDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/RawDirective.php',
        'System\\Razr\\Directive\\SetDirective' => __DIR__ . '/../../..' . '/system/core/Razr/Directive/SetDirective.php',
        'System\\Razr\\Engine' => __DIR__ . '/../../..' . '/system/core/Razr/Engine.php',
        'System\\Razr\\Exception\\ExceptionInterface' => __DIR__ . '/../../..' . '/system/core/Razr/Exception/ExceptionInterface.php',
        'System\\Razr\\Exception\\InvalidArgumentException' => __DIR__ . '/../../..' . '/system/core/Razr/Exception/InvalidArgumentException.php',
        'System\\Razr\\Exception\\RuntimeException' => __DIR__ . '/../../..' . '/system/core/Razr/Exception/RuntimeException.php',
        'System\\Razr\\Exception\\SyntaxErrorException' => __DIR__ . '/../../..' . '/system/core/Razr/Exception/SyntaxErrorException.php',
        'System\\Razr\\Extension\\CoreExtension' => __DIR__ . '/../../..' . '/system/core/Razr/Extension/CoreExtension.php',
        'System\\Razr\\Extension\\ExtensionInterface' => __DIR__ . '/../../..' . '/system/core/Razr/Extension/ExtensionInterface.php',
        'System\\Razr\\Lexer' => __DIR__ . '/../../..' . '/system/core/Razr/Lexer.php',
        'System\\Razr\\Loader\\ChainLoader' => __DIR__ . '/../../..' . '/system/core/Razr/Loader/ChainLoader.php',
        'System\\Razr\\Loader\\FilesystemLoader' => __DIR__ . '/../../..' . '/system/core/Razr/Loader/FilesystemLoader.php',
        'System\\Razr\\Loader\\LoaderInterface' => __DIR__ . '/../../..' . '/system/core/Razr/Loader/LoaderInterface.php',
        'System\\Razr\\Loader\\StringLoader' => __DIR__ . '/../../..' . '/system/core/Razr/Loader/StringLoader.php',
        'System\\Razr\\Parser' => __DIR__ . '/../../..' . '/system/core/Razr/Parser.php',
        'System\\Razr\\Storage\\FileStorage' => __DIR__ . '/../../..' . '/system/core/Razr/Storage/FileStorage.php',
        'System\\Razr\\Storage\\Storage' => __DIR__ . '/../../..' . '/system/core/Razr/Storage/Storage.php',
        'System\\Razr\\Storage\\StringStorage' => __DIR__ . '/../../..' . '/system/core/Razr/Storage/StringStorage.php',
        'System\\Razr\\Token' => __DIR__ . '/../../..' . '/system/core/Razr/Token.php',
        'System\\Razr\\TokenStream' => __DIR__ . '/../../..' . '/system/core/Razr/TokenStream.php',
        'System\\Routes\\Api' => __DIR__ . '/../../..' . '/system/routes/Api.php',
        'System\\Routes\\Migration' => __DIR__ . '/../../..' . '/system/routes/Migration.php',
        'System\\Routes\\Web' => __DIR__ . '/../../..' . '/system/routes/Web.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInityour_suffixed::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInityour_suffixed::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInityour_suffixed::$prefixesPsr0;
            $loader->classMap = ComposerStaticInityour_suffixed::$classMap;

        }, null, ClassLoader::class);
    }
}
