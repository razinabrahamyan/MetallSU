<?php

namespace App\Classes\FilesGenerator\FilesTypes;

interface FilesTypesInterface {
    public function prepareData() : FilesTypesInterface;

    public function generateDoc();

    public function setData(mixed $data);
}
