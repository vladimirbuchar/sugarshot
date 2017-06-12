<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcd0d57fc4cbfdda4d2b96d43bc7ecc1a
{
    public static $files = array (
        '0097ca414fcb37c7130ac24b05f485f8' => __DIR__ . '/..' . '/dibi/dibi/src/loader.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RobRichards\\XMLSecLibs\\' => 23,
            'RobRichards\\WsePhp\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RobRichards\\XMLSecLibs\\' => 
        array (
            0 => __DIR__ . '/..' . '/robrichards/xmlseclibs/src',
        ),
        'RobRichards\\WsePhp\\' => 
        array (
            0 => __DIR__ . '/..' . '/robrichards/wse-php/src',
        ),
    );

    public static $classMap = array (
        'Dibi\\Bridges\\Nette\\DibiExtension21' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Bridges/Nette/DibiExtension21.php',
        'Dibi\\Bridges\\Nette\\DibiExtension22' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Bridges/Nette/DibiExtension22.php',
        'Dibi\\Bridges\\Nette\\Panel' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Bridges/Nette/Panel.php',
        'Dibi\\Bridges\\Tracy\\Panel' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Bridges/Tracy/Panel.php',
        'Dibi\\Connection' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Connection.php',
        'Dibi\\ConstraintViolationException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\DataSource' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/DataSource.php',
        'Dibi\\DateTime' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/DateTime.php',
        'Dibi\\Driver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/interfaces.php',
        'Dibi\\DriverException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\Drivers\\FirebirdDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/FirebirdDriver.php',
        'Dibi\\Drivers\\MsSqlDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/MsSqlDriver.php',
        'Dibi\\Drivers\\MsSqlReflector' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/MsSqlReflector.php',
        'Dibi\\Drivers\\MySqlDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/MySqlDriver.php',
        'Dibi\\Drivers\\MySqlReflector' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/MySqlReflector.php',
        'Dibi\\Drivers\\MySqliDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/MySqliDriver.php',
        'Dibi\\Drivers\\OdbcDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/OdbcDriver.php',
        'Dibi\\Drivers\\OracleDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/OracleDriver.php',
        'Dibi\\Drivers\\PdoDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/PdoDriver.php',
        'Dibi\\Drivers\\PostgreDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/PostgreDriver.php',
        'Dibi\\Drivers\\Sqlite3Driver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/Sqlite3Driver.php',
        'Dibi\\Drivers\\SqliteReflector' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/SqliteReflector.php',
        'Dibi\\Drivers\\SqlsrvDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/SqlsrvDriver.php',
        'Dibi\\Drivers\\SqlsrvReflector' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Drivers/SqlsrvReflector.php',
        'Dibi\\Event' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Event.php',
        'Dibi\\Exception' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\Fluent' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Fluent.php',
        'Dibi\\ForeignKeyConstraintViolationException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\HashMap' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/HashMap.php',
        'Dibi\\HashMapBase' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/HashMap.php',
        'Dibi\\Helpers' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Helpers.php',
        'Dibi\\IDataSource' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/interfaces.php',
        'Dibi\\Literal' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Literal.php',
        'Dibi\\Loggers\\FileLogger' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Loggers/FileLogger.php',
        'Dibi\\Loggers\\FirePhpLogger' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Loggers/FirePhpLogger.php',
        'Dibi\\NotImplementedException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\NotNullConstraintViolationException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\NotSupportedException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\PcreException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\ProcedureException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'Dibi\\Reflection\\Column' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Reflection/Column.php',
        'Dibi\\Reflection\\Database' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Reflection/Database.php',
        'Dibi\\Reflection\\ForeignKey' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Reflection/ForeignKey.php',
        'Dibi\\Reflection\\Index' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Reflection/Index.php',
        'Dibi\\Reflection\\Result' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Reflection/Result.php',
        'Dibi\\Reflection\\Table' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Reflection/Table.php',
        'Dibi\\Reflector' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/interfaces.php',
        'Dibi\\Result' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Result.php',
        'Dibi\\ResultDriver' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/interfaces.php',
        'Dibi\\ResultIterator' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/ResultIterator.php',
        'Dibi\\Row' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Row.php',
        'Dibi\\Strict' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Strict.php',
        'Dibi\\Translator' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Translator.php',
        'Dibi\\Type' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/Type.php',
        'Dibi\\UniqueConstraintViolationException' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/exceptions.php',
        'FilipSedivy\\EET\\Certificate' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Certificate.php',
        'FilipSedivy\\EET\\Dispatcher' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Dispatcher.php',
        'FilipSedivy\\EET\\Exceptions\\ClientException' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Exceptions/ClientException.php',
        'FilipSedivy\\EET\\Exceptions\\RequirementsException' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Exceptions/RequirementsException.php',
        'FilipSedivy\\EET\\Exceptions\\ServerException' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Exceptions/ServerException.php',
        'FilipSedivy\\EET\\Receipt' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Receipt.php',
        'FilipSedivy\\EET\\SoapClient' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/SoapClient.php',
        'FilipSedivy\\EET\\Utils\\Format' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Utils/Format.php',
        'FilipSedivy\\EET\\Utils\\UUID' => __DIR__ . '/..' . '/filipsedivy/php-eet/src/Utils/UUID.php',
        'dibi' => __DIR__ . '/..' . '/dibi/dibi/src/Dibi/dibi.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcd0d57fc4cbfdda4d2b96d43bc7ecc1a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcd0d57fc4cbfdda4d2b96d43bc7ecc1a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcd0d57fc4cbfdda4d2b96d43bc7ecc1a::$classMap;

        }, null, ClassLoader::class);
    }
}
