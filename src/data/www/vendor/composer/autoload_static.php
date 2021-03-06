<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf43200f538bd38efefe7e0027767a776
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RomanAM\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RomanAM\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf43200f538bd38efefe7e0027767a776::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf43200f538bd38efefe7e0027767a776::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
