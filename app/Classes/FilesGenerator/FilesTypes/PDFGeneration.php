<?php

namespace App\Classes\FilesGenerator\FilesTypes;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class PDFGeneration implements FilesTypesInterface {
    const TYPE = '.pdf';
    const PATH = 'email.export_files';
    const FILE_PATH = 'files.pdf_file';

    private $data;
    private $tableColumns;
    private $table;
    private $title;
    private $rows;

    /**
     * Подготовка параметров для создания файла
     * @return void
     */
    public function prepareData() : FilesTypesInterface {
        $data = $this->getData();
        $this->setTable($data['table'])
             ->setRows($data['rows'])
             ->setTableColumns($data['tableColumns'])
             ->setTitle($data['email']['title']);
        return $this;
    }

    /**
     * Формирует сам PDF файл
     * @param string $path
     * @return mixed
     */
    public function generateDoc() {
        return PDF::loadView(self::FILE_PATH, [
            "tableColumns" => $this->getTableColumns(),
            "table"        => $this->getTable(),
            "rows"         => $this->getRows(),
            "time"         => Carbon::now()->format('d/m/Y  h:i'),
            "title"        => $this->getTitle(),
        ])->output();
    }

    /**
     * @return mixed
     */
    public function getTableColumns() {
        return $this->tableColumns;
    }

    /**
     * @param mixed $tableColumns
     * @return PDFGeneration
     */
    public function setTableColumns($tableColumns) : PDFGeneration {
        $this->tableColumns = $tableColumns;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @param mixed $table
     * @return PDFGeneration
     */
    public function setTable($table) : PDFGeneration {
        $this->table = $table;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRows() {
        return $this->rows;
    }

    /**
     * @param mixed $rows
     * @return PDFGeneration
     */
    public function setRows($rows) : PDFGeneration {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return PDFGeneration
     */
    public function setData($data) : PDFGeneration {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) : PDFGeneration {
        $this->title = $title;
        return $this;
    }

}
