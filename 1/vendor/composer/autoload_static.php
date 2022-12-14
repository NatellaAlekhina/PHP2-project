<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit622ece6889b66ae7e3742da062be8115
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Geekbrains\\Php2\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Geekbrains\\Php2\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit622ece6889b66ae7e3742da062be8115::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit622ece6889b66ae7e3742da062be8115::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit622ece6889b66ae7e3742da062be8115::$classMap;

        }, null, ClassLoader::class);
    }
}
