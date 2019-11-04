<?php

namespace Helpers;

class AppConfig
{
    public static $illuminateDb = [
        'driver' => 'mysql',
        'host' => 'remotemysql.com:3306',
        'database' => 'cW255Z3D49',
        'username' => 'cW255Z3D49',
        'password' => 'Eacp5iw7k9',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ];

    public static $tables = [

        'productos' => "productos"
    ];

    public static $imagesDirectories = [
        'users' => 'public/img/users',
        'empleados' => 'public/img/empleados',
        'empleadosBkp' => 'public/img/empleadosBkp'
    ];

    public static $watermark = 'public/img/watermark.png';

    public static $imageConstraints = [
        'size' => '500000', //0,5mb
        'types' => [
            'image/jpeg', 'image/jpeg', 'image/png'
        ],
        'extensions' => [
            '.jpg', '.jpeg', '.png', '.JPG', '.JPEG', '.PNG'
        ]
    ];

}
