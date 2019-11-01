<?php

namespace Helpers;

class FilesHelper
{
    public static function GetPath($folder, $append = null)
    {
        $path = $_SERVER["DOCUMENT_ROOT"].'/'.$folder;

        if($append)
           $path .= '/'.$append;

        return $path;
    }

    public static function GetExtension($string)
    {
        $extension = explode(".", $string);
        $extension = end($extension);
        return ".".$extension;
    }
    
    public static function GetFilename($string)
    {
        $filename = explode("/", $string);
        $filename = end($filename);
        $filename = explode(".", $filename);
        $filename = $filename[0];
        return $filename;
    }
}
