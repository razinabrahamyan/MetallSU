<?php

namespace App\Classes\FilesGenerator\FilesTypes;

use App\Exports\TableExport;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;

class ExcelGenerator implements FilesTypesInterface {
    const TYPE = '.xlsx';
    const PATH = 'email.export_files';

    private $data;
    private $tableId;

    /**
     * @return FilesTypesInterface
     */
    public function prepareData() : FilesTypesInterface {
        $this->setTableId($this->getData()["table"]->id ?? 0);
        return $this;
    }

    /**
     * @return mixed
     */
    public function generateDoc() {
        return Excel::raw(new TableExport($this->getTableId()), BaseExcel::XLSX);
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data) : ExcelGenerator {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getTableId() {
        return $this->tableId;
    }

    /**
     * @param mixed $tableId
     */
    public function setTableId($tableId) : ExcelGenerator {
        $this->tableId = $tableId;
        return $this;
    }
}
