<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7915dc4cbdc37891513efa8beb14fd63
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'Panopto\\' => 8,
        ),
        'L' => 
        array (
            'League\\OAuth2\\Client\\' => 21,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Panopto\\' => 
        array (
            0 => __DIR__ . '/..' . '/lucisgit/php-panopto-api/lib',
        ),
        'League\\OAuth2\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/oauth2-client/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PanoptoSortingTableGUI' => __DIR__ . '/../..' . '/classes/ui/user/class.PanoptoSortingTableGUI.php',
        'classes\\ui\\admin\\PluginConfigurationMainUI' => __DIR__ . '/../..' . '/classes/ui/admin/class.PluginConfigurationMainUI.php',
        'classes\\ui\\user\\ManageVideosUI' => __DIR__ . '/../..' . '/classes/ui/user/class.ManageVideosUI.php',
        'classes\\ui\\user\\UserContentMainUI' => __DIR__ . '/../..' . '/classes/ui/user/class.UserContentMainUI.php',
        'connection\\PanoptoClient' => __DIR__ . '/../..' . '/classes/connection/class.PanoptoClient.php',
        'connection\\PanoptoLTIHandler' => __DIR__ . '/../..' . '/classes/connection/class.PanoptoLTIHandler.php',
        'connection\\PanoptoLog' => __DIR__ . '/../..' . '/classes/connection/class.PanoptoLog.php',
        'connection\\PanoptoRestClient' => __DIR__ . '/../..' . '/classes/connection/class.PanoptoRestClient.php',
        'ilObjPanopto' => __DIR__ . '/../..' . '/classes/class.ilObjPanopto.php',
        'ilObjPanoptoAccess' => __DIR__ . '/../..' . '/classes/class.ilObjPanoptoAccess.php',
        'ilObjPanoptoGUI' => __DIR__ . '/../..' . '/classes/class.ilObjPanoptoGUI.php',
        'ilObjPanoptoListGUI' => __DIR__ . '/../..' . '/classes/class.ilObjPanoptoListGUI.php',
        'ilPanoptoConfigGUI' => __DIR__ . '/../..' . '/classes/class.ilPanoptoConfigGUI.php',
        'ilPanoptoPlugin' => __DIR__ . '/../..' . '/classes/class.ilPanoptoPlugin.php',
        'platform\\PanoptoConfig' => __DIR__ . '/../..' . '/classes/platform/class.PanoptoConfig.php',
        'platform\\PanoptoDatabase' => __DIR__ . '/../..' . '/classes/platform/class.PanoptoDatabase.php',
        'platform\\PanoptoException' => __DIR__ . '/../..' . '/classes/platform/class.PanoptoException.php',
        'platform\\PanoptoPlatform' => __DIR__ . '/../..' . '/classes/platform/class.PanoptoPlatform.php',
        'platform\\SorterEntry' => __DIR__ . '/../..' . '/classes/platform/class.SorterEntry.php',
        'utils\\DTO\\ContentObject' => __DIR__ . '/../..' . '/classes/utils/DTO/ContentObject.php',
        'utils\\DTO\\ContentObjectBuilder' => __DIR__ . '/../..' . '/classes/utils/DTO/ContentObjectBuilder.php',
        'utils\\DTO\\Playlist' => __DIR__ . '/../..' . '/classes/utils/DTO/Playlist.php',
        'utils\\DTO\\RESTToken' => __DIR__ . '/../..' . '/classes/utils/DTO/RESTToken.php',
        'utils\\DTO\\Session' => __DIR__ . '/../..' . '/classes/utils/DTO/Session.php',
        'utils\\PanoptoUtils' => __DIR__ . '/../..' . '/classes/utils/class.PanoptoUtils.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7915dc4cbdc37891513efa8beb14fd63::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7915dc4cbdc37891513efa8beb14fd63::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7915dc4cbdc37891513efa8beb14fd63::$classMap;

        }, null, ClassLoader::class);
    }
}
