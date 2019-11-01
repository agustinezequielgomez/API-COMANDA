<?php
namespace Helpers;

use Helpers\AppConfig as Config;
use Helpers\FilesHelper as Files;
class ImagesHelper
{
    public static function SaveImageFromRequest($request, $path, $bkpPath, $newFilename)
    {
        if(!isset($request->getUploadedFiles()["image"]))
            return "";
        
        $image = self::ValidateImage($request->getUploadedFiles()["image"]);

        if(is_null($image))
            return "";

        $newFilename .= Files::GetExtension($image->getClientFilename());
        $destination = Files::GetPath($path, $newFilename);

      

        //choose if watermark or not
        $saved = self::SaveImage($image, $path, $bkpPath, $destination, true);
        
        if(!$saved)
            return "";

        return $newFilename;
    }

    public static function SaveImage($image, $path, $bkpPath, $newPath, $withWatermark = false)
    {
        if($withWatermark)
        {
            self::WatermarkImage($image);
        }

        try
        {
            $filename = Files::GetFilename($newPath);

            //check if file exists in any format -jpg, png, etc-
            //checks for any file named >= the destination filename
            foreach (glob($path."/".$filename."*") as $file)
            {
                $existingFilename = Files::GetFilename($file);

                if($filename == $existingFilename)
                {
                    $newFilename = $filename."_".date('Ymd_His');
                    $newFilename = $newFilename.Files::GetExtension($file);
                   rename($file, Files::GetPath($bkpPath, $newFilename )
                   );

                }
            }

            $image->moveTo($newPath);
            return true;
        }
        catch(\Exception $e)
        {
            var_dump(":("); die();
            return false;
        }
    }

    public static function ValidateImage($image)
    {
        /*
        $validatedImage = null;
        $type = $image->getClientMediaType();
        $size = $image->getSize();
        $extension = Files::GetExtension($image->getClientFilename());

        if(
            ($size <= Config::$imageConstraints["size"])  &&
            (in_array($type, Config::$imageConstraints["types"])) &&
            (in_array($extension, Config::$imageConstraints["extensions"]))
        )
        {
            $validatedImage = $image;
        }
                return $validatedImage;

        */
        return $image;
    }

    public static function WatermarkImage($image)
    {
        //esta funcion reemplaza el /tmp/php por img c/ watermark

        $watermark = imagecreatefrompng(Config::$watermark);
 

        switch(Files::GetExtension($image->getClientFilename()))
        {
            case ".jpg":
            case ".JPG":
            case ".jpeg":
            case ".JPEG":
                $img = imagecreatefromjpeg($image->file);
            break;

            case ".png":
            case ".PNG":
                $img = imagecreatefrompng($image->file);
            break;
        }
    
        // Establecer los márgenes para la estampa y obtener el alto/ancho de la imagen de la estampa
        $margen_dcho = 10;
        $margen_inf = 10;
        $sx = imagesx($watermark);
        $sy = imagesy($watermark);

        // Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
        // ancho de la foto para calcular la posición de la estampa. 
        imagecopy($img, 
            $watermark, 
            imagesx($img) - $sx - $margen_dcho, 
            imagesy($img) - $sy - $margen_inf, 
            0, 0, 
            imagesx($watermark), imagesy($watermark)
        );
        
        // Imprimir y liberar memoria
        $img = imagepng($img, $image->file);
    }
}
