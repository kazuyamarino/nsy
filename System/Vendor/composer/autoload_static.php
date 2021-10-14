<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInityour_suffixed
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        'a1105708a18b76903365ca1c4aa61b02' => __DIR__ . '/..' . '/symfony/translation/Resources/functions.php',
        'e1df08c522e459215e49702ec8129902' => __DIR__ . '/../../..' . '/System/Helpers/CodeIgniter_Helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'System\\' => 7,
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
        ),
        'O' => 
        array (
            'Optimus\\Onion\\' => 14,
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
            0 => __DIR__ . '/../../..' . '/System',
        ),
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
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
        'Optimus\\Onion\\' => 
        array (
            0 => __DIR__ . '/..' . '/optimus/onion/src',
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
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Carbon\\Carbon' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Carbon.php',
        'Carbon\\CarbonConverterInterface' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonConverterInterface.php',
        'Carbon\\CarbonImmutable' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonImmutable.php',
        'Carbon\\CarbonInterface' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonInterface.php',
        'Carbon\\CarbonInterval' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonInterval.php',
        'Carbon\\CarbonPeriod' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonPeriod.php',
        'Carbon\\CarbonTimeZone' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/CarbonTimeZone.php',
        'Carbon\\Cli\\Invoker' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Cli/Invoker.php',
        'Carbon\\Doctrine\\CarbonDoctrineType' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/CarbonDoctrineType.php',
        'Carbon\\Doctrine\\CarbonImmutableType' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/CarbonImmutableType.php',
        'Carbon\\Doctrine\\CarbonType' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/CarbonType.php',
        'Carbon\\Doctrine\\CarbonTypeConverter' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/CarbonTypeConverter.php',
        'Carbon\\Doctrine\\DateTimeDefaultPrecision' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/DateTimeDefaultPrecision.php',
        'Carbon\\Doctrine\\DateTimeImmutableType' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/DateTimeImmutableType.php',
        'Carbon\\Doctrine\\DateTimeType' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Doctrine/DateTimeType.php',
        'Carbon\\Exceptions\\BadComparisonUnitException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/BadComparisonUnitException.php',
        'Carbon\\Exceptions\\BadFluentConstructorException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/BadFluentConstructorException.php',
        'Carbon\\Exceptions\\BadFluentSetterException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/BadFluentSetterException.php',
        'Carbon\\Exceptions\\BadMethodCallException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/BadMethodCallException.php',
        'Carbon\\Exceptions\\Exception' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/Exception.php',
        'Carbon\\Exceptions\\ImmutableException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/ImmutableException.php',
        'Carbon\\Exceptions\\InvalidArgumentException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidArgumentException.php',
        'Carbon\\Exceptions\\InvalidCastException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidCastException.php',
        'Carbon\\Exceptions\\InvalidDateException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidDateException.php',
        'Carbon\\Exceptions\\InvalidFormatException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidFormatException.php',
        'Carbon\\Exceptions\\InvalidIntervalException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidIntervalException.php',
        'Carbon\\Exceptions\\InvalidPeriodDateException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidPeriodDateException.php',
        'Carbon\\Exceptions\\InvalidPeriodParameterException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidPeriodParameterException.php',
        'Carbon\\Exceptions\\InvalidTimeZoneException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidTimeZoneException.php',
        'Carbon\\Exceptions\\InvalidTypeException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/InvalidTypeException.php',
        'Carbon\\Exceptions\\NotACarbonClassException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/NotACarbonClassException.php',
        'Carbon\\Exceptions\\NotAPeriodException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/NotAPeriodException.php',
        'Carbon\\Exceptions\\NotLocaleAwareException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/NotLocaleAwareException.php',
        'Carbon\\Exceptions\\OutOfRangeException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/OutOfRangeException.php',
        'Carbon\\Exceptions\\ParseErrorException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/ParseErrorException.php',
        'Carbon\\Exceptions\\RuntimeException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/RuntimeException.php',
        'Carbon\\Exceptions\\UnitException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnitException.php',
        'Carbon\\Exceptions\\UnitNotConfiguredException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnitNotConfiguredException.php',
        'Carbon\\Exceptions\\UnknownGetterException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnknownGetterException.php',
        'Carbon\\Exceptions\\UnknownMethodException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnknownMethodException.php',
        'Carbon\\Exceptions\\UnknownSetterException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnknownSetterException.php',
        'Carbon\\Exceptions\\UnknownUnitException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnknownUnitException.php',
        'Carbon\\Exceptions\\UnreachableException' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Exceptions/UnreachableException.php',
        'Carbon\\Factory' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Factory.php',
        'Carbon\\FactoryImmutable' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/FactoryImmutable.php',
        'Carbon\\Language' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Language.php',
        'Carbon\\Laravel\\ServiceProvider' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Laravel/ServiceProvider.php',
        'Carbon\\PHPStan\\Macro' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/PHPStan/Macro.php',
        'Carbon\\PHPStan\\MacroExtension' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/PHPStan/MacroExtension.php',
        'Carbon\\PHPStan\\MacroScanner' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/PHPStan/MacroScanner.php',
        'Carbon\\Traits\\Boundaries' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Boundaries.php',
        'Carbon\\Traits\\Cast' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Cast.php',
        'Carbon\\Traits\\Comparison' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Comparison.php',
        'Carbon\\Traits\\Converter' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Converter.php',
        'Carbon\\Traits\\Creator' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Creator.php',
        'Carbon\\Traits\\Date' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Date.php',
        'Carbon\\Traits\\Difference' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Difference.php',
        'Carbon\\Traits\\IntervalRounding' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/IntervalRounding.php',
        'Carbon\\Traits\\IntervalStep' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/IntervalStep.php',
        'Carbon\\Traits\\Localization' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Localization.php',
        'Carbon\\Traits\\Macro' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Macro.php',
        'Carbon\\Traits\\Mixin' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Mixin.php',
        'Carbon\\Traits\\Modifiers' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Modifiers.php',
        'Carbon\\Traits\\Mutability' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Mutability.php',
        'Carbon\\Traits\\ObjectInitialisation' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/ObjectInitialisation.php',
        'Carbon\\Traits\\Options' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Options.php',
        'Carbon\\Traits\\Rounding' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Rounding.php',
        'Carbon\\Traits\\Serialization' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Serialization.php',
        'Carbon\\Traits\\Test' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Test.php',
        'Carbon\\Traits\\Timestamp' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Timestamp.php',
        'Carbon\\Traits\\Units' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Units.php',
        'Carbon\\Traits\\Week' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Traits/Week.php',
        'Carbon\\Translator' => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon/Translator.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
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
        'Optimus\\Onion\\LayerInterface' => __DIR__ . '/..' . '/optimus/onion/src/LayerInterface.php',
        'Optimus\\Onion\\Onion' => __DIR__ . '/..' . '/optimus/onion/src/Onion.php',
        'PhpOption\\LazyOption' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/LazyOption.php',
        'PhpOption\\None' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/None.php',
        'PhpOption\\Option' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/Option.php',
        'PhpOption\\Some' => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption/Some.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'Symfony\\Component\\Translation\\Catalogue\\AbstractOperation' => __DIR__ . '/..' . '/symfony/translation/Catalogue/AbstractOperation.php',
        'Symfony\\Component\\Translation\\Catalogue\\MergeOperation' => __DIR__ . '/..' . '/symfony/translation/Catalogue/MergeOperation.php',
        'Symfony\\Component\\Translation\\Catalogue\\OperationInterface' => __DIR__ . '/..' . '/symfony/translation/Catalogue/OperationInterface.php',
        'Symfony\\Component\\Translation\\Catalogue\\TargetOperation' => __DIR__ . '/..' . '/symfony/translation/Catalogue/TargetOperation.php',
        'Symfony\\Component\\Translation\\Command\\TranslationPullCommand' => __DIR__ . '/..' . '/symfony/translation/Command/TranslationPullCommand.php',
        'Symfony\\Component\\Translation\\Command\\TranslationPushCommand' => __DIR__ . '/..' . '/symfony/translation/Command/TranslationPushCommand.php',
        'Symfony\\Component\\Translation\\Command\\TranslationTrait' => __DIR__ . '/..' . '/symfony/translation/Command/TranslationTrait.php',
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
        'Symfony\\Component\\Translation\\Exception\\IncompleteDsnException' => __DIR__ . '/..' . '/symfony/translation/Exception/IncompleteDsnException.php',
        'Symfony\\Component\\Translation\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/symfony/translation/Exception/InvalidArgumentException.php',
        'Symfony\\Component\\Translation\\Exception\\InvalidResourceException' => __DIR__ . '/..' . '/symfony/translation/Exception/InvalidResourceException.php',
        'Symfony\\Component\\Translation\\Exception\\LogicException' => __DIR__ . '/..' . '/symfony/translation/Exception/LogicException.php',
        'Symfony\\Component\\Translation\\Exception\\MissingRequiredOptionException' => __DIR__ . '/..' . '/symfony/translation/Exception/MissingRequiredOptionException.php',
        'Symfony\\Component\\Translation\\Exception\\NotFoundResourceException' => __DIR__ . '/..' . '/symfony/translation/Exception/NotFoundResourceException.php',
        'Symfony\\Component\\Translation\\Exception\\ProviderException' => __DIR__ . '/..' . '/symfony/translation/Exception/ProviderException.php',
        'Symfony\\Component\\Translation\\Exception\\ProviderExceptionInterface' => __DIR__ . '/..' . '/symfony/translation/Exception/ProviderExceptionInterface.php',
        'Symfony\\Component\\Translation\\Exception\\RuntimeException' => __DIR__ . '/..' . '/symfony/translation/Exception/RuntimeException.php',
        'Symfony\\Component\\Translation\\Exception\\UnsupportedSchemeException' => __DIR__ . '/..' . '/symfony/translation/Exception/UnsupportedSchemeException.php',
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
        'Symfony\\Component\\Translation\\Provider\\AbstractProviderFactory' => __DIR__ . '/..' . '/symfony/translation/Provider/AbstractProviderFactory.php',
        'Symfony\\Component\\Translation\\Provider\\Dsn' => __DIR__ . '/..' . '/symfony/translation/Provider/Dsn.php',
        'Symfony\\Component\\Translation\\Provider\\FilteringProvider' => __DIR__ . '/..' . '/symfony/translation/Provider/FilteringProvider.php',
        'Symfony\\Component\\Translation\\Provider\\NullProvider' => __DIR__ . '/..' . '/symfony/translation/Provider/NullProvider.php',
        'Symfony\\Component\\Translation\\Provider\\NullProviderFactory' => __DIR__ . '/..' . '/symfony/translation/Provider/NullProviderFactory.php',
        'Symfony\\Component\\Translation\\Provider\\ProviderFactoryInterface' => __DIR__ . '/..' . '/symfony/translation/Provider/ProviderFactoryInterface.php',
        'Symfony\\Component\\Translation\\Provider\\ProviderInterface' => __DIR__ . '/..' . '/symfony/translation/Provider/ProviderInterface.php',
        'Symfony\\Component\\Translation\\Provider\\TranslationProviderCollection' => __DIR__ . '/..' . '/symfony/translation/Provider/TranslationProviderCollection.php',
        'Symfony\\Component\\Translation\\Provider\\TranslationProviderCollectionFactory' => __DIR__ . '/..' . '/symfony/translation/Provider/TranslationProviderCollectionFactory.php',
        'Symfony\\Component\\Translation\\PseudoLocalizationTranslator' => __DIR__ . '/..' . '/symfony/translation/PseudoLocalizationTranslator.php',
        'Symfony\\Component\\Translation\\Reader\\TranslationReader' => __DIR__ . '/..' . '/symfony/translation/Reader/TranslationReader.php',
        'Symfony\\Component\\Translation\\Reader\\TranslationReaderInterface' => __DIR__ . '/..' . '/symfony/translation/Reader/TranslationReaderInterface.php',
        'Symfony\\Component\\Translation\\Test\\ProviderFactoryTestCase' => __DIR__ . '/..' . '/symfony/translation/Test/ProviderFactoryTestCase.php',
        'Symfony\\Component\\Translation\\Test\\ProviderTestCase' => __DIR__ . '/..' . '/symfony/translation/Test/ProviderTestCase.php',
        'Symfony\\Component\\Translation\\TranslatableMessage' => __DIR__ . '/..' . '/symfony/translation/TranslatableMessage.php',
        'Symfony\\Component\\Translation\\Translator' => __DIR__ . '/..' . '/symfony/translation/Translator.php',
        'Symfony\\Component\\Translation\\TranslatorBag' => __DIR__ . '/..' . '/symfony/translation/TranslatorBag.php',
        'Symfony\\Component\\Translation\\TranslatorBagInterface' => __DIR__ . '/..' . '/symfony/translation/TranslatorBagInterface.php',
        'Symfony\\Component\\Translation\\Util\\ArrayConverter' => __DIR__ . '/..' . '/symfony/translation/Util/ArrayConverter.php',
        'Symfony\\Component\\Translation\\Util\\XliffUtils' => __DIR__ . '/..' . '/symfony/translation/Util/XliffUtils.php',
        'Symfony\\Component\\Translation\\Writer\\TranslationWriter' => __DIR__ . '/..' . '/symfony/translation/Writer/TranslationWriter.php',
        'Symfony\\Component\\Translation\\Writer\\TranslationWriterInterface' => __DIR__ . '/..' . '/symfony/translation/Writer/TranslationWriterInterface.php',
        'Symfony\\Contracts\\Translation\\LocaleAwareInterface' => __DIR__ . '/..' . '/symfony/translation-contracts/LocaleAwareInterface.php',
        'Symfony\\Contracts\\Translation\\Test\\TranslatorTest' => __DIR__ . '/..' . '/symfony/translation-contracts/Test/TranslatorTest.php',
        'Symfony\\Contracts\\Translation\\TranslatableInterface' => __DIR__ . '/..' . '/symfony/translation-contracts/TranslatableInterface.php',
        'Symfony\\Contracts\\Translation\\TranslatorInterface' => __DIR__ . '/..' . '/symfony/translation-contracts/TranslatorInterface.php',
        'Symfony\\Contracts\\Translation\\TranslatorTrait' => __DIR__ . '/..' . '/symfony/translation-contracts/TranslatorTrait.php',
        'Symfony\\Polyfill\\Ctype\\Ctype' => __DIR__ . '/..' . '/symfony/polyfill-ctype/Ctype.php',
        'Symfony\\Polyfill\\Mbstring\\Mbstring' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/Mbstring.php',
        'Symfony\\Polyfill\\Php80\\Php80' => __DIR__ . '/..' . '/symfony/polyfill-php80/Php80.php',
        'System\\Controllers\\Test' => __DIR__ . '/../../..' . '/System/Controllers/Test.php',
        'System\\Controllers\\Welcome' => __DIR__ . '/../../..' . '/System/Controllers/Welcome.php',
        'System\\Core\\DB' => __DIR__ . '/../../..' . '/System/Core/DB.php',
        'System\\Core\\Load' => __DIR__ . '/../../..' . '/System/Core/Load.php',
        'System\\Core\\NSY_AssetManager' => __DIR__ . '/../../..' . '/System/Core/NSY_AssetManager.php',
        'System\\Core\\NSY_CSRF' => __DIR__ . '/../../..' . '/System/Core/NSY_CSRF.php',
        'System\\Core\\NSY_DB' => __DIR__ . '/../../..' . '/System/Core/NSY_DB.php',
        'System\\Core\\NSY_Desk' => __DIR__ . '/../../..' . '/System/Core/NSY_Desk.php',
        'System\\Core\\NSY_Interface' => __DIR__ . '/../../..' . '/System/Core/NSY_Interface.php',
        'System\\Core\\NSY_Migration' => __DIR__ . '/../../..' . '/System/Core/NSY_Migration.php',
        'System\\Core\\NSY_Router' => __DIR__ . '/../../..' . '/System/Core/NSY_Router.php',
        'System\\Core\\NSY_System' => __DIR__ . '/../../..' . '/System/Core/NSY_System.php',
        'System\\Core\\NSY_XSS_Filter' => __DIR__ . '/../../..' . '/System/Core/NSY_XSS_Filter.php',
        'System\\Core\\Razr\\Directive\\BlockDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/BlockDirective.php',
        'System\\Core\\Razr\\Directive\\ControlDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/ControlDirective.php',
        'System\\Core\\Razr\\Directive\\Directive' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/Directive.php',
        'System\\Core\\Razr\\Directive\\DirectiveInterface' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/DirectiveInterface.php',
        'System\\Core\\Razr\\Directive\\ExtendDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/ExtendDirective.php',
        'System\\Core\\Razr\\Directive\\FunctionDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/FunctionDirective.php',
        'System\\Core\\Razr\\Directive\\IncludeDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/IncludeDirective.php',
        'System\\Core\\Razr\\Directive\\RawDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/RawDirective.php',
        'System\\Core\\Razr\\Directive\\SetDirective' => __DIR__ . '/../../..' . '/System/Core/Razr/Directive/SetDirective.php',
        'System\\Core\\Razr\\Engine' => __DIR__ . '/../../..' . '/System/Core/Razr/Engine.php',
        'System\\Core\\Razr\\Exception\\ExceptionInterface' => __DIR__ . '/../../..' . '/System/Core/Razr/Exception/ExceptionInterface.php',
        'System\\Core\\Razr\\Exception\\InvalidArgumentException' => __DIR__ . '/../../..' . '/System/Core/Razr/Exception/InvalidArgumentException.php',
        'System\\Core\\Razr\\Exception\\RuntimeException' => __DIR__ . '/../../..' . '/System/Core/Razr/Exception/RuntimeException.php',
        'System\\Core\\Razr\\Exception\\SyntaxErrorException' => __DIR__ . '/../../..' . '/System/Core/Razr/Exception/SyntaxErrorException.php',
        'System\\Core\\Razr\\Extension\\CoreExtension' => __DIR__ . '/../../..' . '/System/Core/Razr/Extension/CoreExtension.php',
        'System\\Core\\Razr\\Extension\\ExtensionInterface' => __DIR__ . '/../../..' . '/System/Core/Razr/Extension/ExtensionInterface.php',
        'System\\Core\\Razr\\Lexer' => __DIR__ . '/../../..' . '/System/Core/Razr/Lexer.php',
        'System\\Core\\Razr\\Loader\\ChainLoader' => __DIR__ . '/../../..' . '/System/Core/Razr/Loader/ChainLoader.php',
        'System\\Core\\Razr\\Loader\\FilesystemLoader' => __DIR__ . '/../../..' . '/System/Core/Razr/Loader/FilesystemLoader.php',
        'System\\Core\\Razr\\Loader\\LoaderInterface' => __DIR__ . '/../../..' . '/System/Core/Razr/Loader/LoaderInterface.php',
        'System\\Core\\Razr\\Loader\\StringLoader' => __DIR__ . '/../../..' . '/System/Core/Razr/Loader/StringLoader.php',
        'System\\Core\\Razr\\Parser' => __DIR__ . '/../../..' . '/System/Core/Razr/Parser.php',
        'System\\Core\\Razr\\Storage\\FileStorage' => __DIR__ . '/../../..' . '/System/Core/Razr/Storage/FileStorage.php',
        'System\\Core\\Razr\\Storage\\Storage' => __DIR__ . '/../../..' . '/System/Core/Razr/Storage/Storage.php',
        'System\\Core\\Razr\\Storage\\StringStorage' => __DIR__ . '/../../..' . '/System/Core/Razr/Storage/StringStorage.php',
        'System\\Core\\Razr\\Token' => __DIR__ . '/../../..' . '/System/Core/Razr/Token.php',
        'System\\Core\\Razr\\TokenStream' => __DIR__ . '/../../..' . '/System/Core/Razr/TokenStream.php',
        'System\\Libraries\\Cookie' => __DIR__ . '/../../..' . '/System/Libraries/Cookie.php',
        'System\\Libraries\\Curl' => __DIR__ . '/../../..' . '/System/Libraries/Curl.php',
        'System\\Libraries\\Exception\\JsonException' => __DIR__ . '/../../..' . '/System/Libraries/Exception/JsonException.php',
        'System\\Libraries\\File' => __DIR__ . '/../../..' . '/System/Libraries/File.php',
        'System\\Libraries\\ImageResize' => __DIR__ . '/../../..' . '/System/Libraries/ImageResize.php',
        'System\\Libraries\\ImageResizeException' => __DIR__ . '/../../..' . '/System/Libraries/ImageResizeException.php',
        'System\\Libraries\\Ip' => __DIR__ . '/../../..' . '/System/Libraries/Ip.php',
        'System\\Libraries\\Json' => __DIR__ . '/../../..' . '/System/Libraries/Json.php',
        'System\\Libraries\\JsonLastError' => __DIR__ . '/../../..' . '/System/Libraries/JsonLastError.php',
        'System\\Libraries\\LanguageCode' => __DIR__ . '/../../..' . '/System/Libraries/LanguageCode.php',
        'System\\Libraries\\LanguageCodeCollection' => __DIR__ . '/../../..' . '/System/Libraries/LanguageCodeCollection.php',
        'System\\Libraries\\LoadTime' => __DIR__ . '/../../..' . '/System/Libraries/LoadTime.php',
        'System\\Libraries\\Request' => __DIR__ . '/../../..' . '/System/Libraries/Request.php',
        'System\\Libraries\\Session' => __DIR__ . '/../../..' . '/System/Libraries/Session.php',
        'System\\Libraries\\Str' => __DIR__ . '/../../..' . '/System/Libraries/Str.php',
        'System\\Libraries\\Validate' => __DIR__ . '/../../..' . '/System/Libraries/Validate.php',
        'System\\Middlewares\\AfterLayer' => __DIR__ . '/../../..' . '/System/Middlewares/AfterLayer.php',
        'System\\Middlewares\\BeforeLayer' => __DIR__ . '/../../..' . '/System/Middlewares/BeforeLayer.php',
        'System\\Models\\Model_Test' => __DIR__ . '/../../..' . '/System/Models/Model_Test.php',
        'System\\Models\\Model_Welcome' => __DIR__ . '/../../..' . '/System/Models/Model_Welcome.php',
        'System\\Modules\\Homepage\\Controllers\\Hello' => __DIR__ . '/../../..' . '/System/Modules/Homepage/Controllers/Hello.php',
        'System\\Modules\\Homepage\\Models\\Model_Hello' => __DIR__ . '/../../..' . '/System/Modules/Homepage/Models/Model_Hello.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
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