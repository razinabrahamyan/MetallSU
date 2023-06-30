<?php

namespace App\Classes\FilesGenerator;

use App\Classes\FilesGenerator\FilesTypes\ExcelGenerator;
use App\Classes\FilesGenerator\FilesTypes\PDFGeneration;

class Core {
    const FILES_TYPE_BY_OBJECTS = [
      'pdf' => PDFGeneration::class,
      'xlsx' => ExcelGenerator::class,
    ];

    public static function getFileGeneratorByType(string $type){
        $class = self::FILES_TYPE_BY_OBJECTS[$type];
        return new $class();
    }
}
