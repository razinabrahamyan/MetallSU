<?php

namespace App\Classes\FilesGenerator;

use App\Classes\FilesGenerator\FilesTypes\FilesTypesInterface;
use App\Models\SentMailLog;
use Illuminate\Support\Facades\Mail;

class FileGenerator {
    private $fileType;
    private $fileData;
    private $generatedFile;

    /**
     * FileGenerator constructor.
     * @param $fileType
     */
    public function __construct(FilesTypesInterface $fileType) {
        $this->fileType = $fileType;
    }

    public function generateFile() : FileGenerator {
        $fileType = $this->getFileType();
        $generatorCore = (new $fileType())->setData($this->getFileData())
                                          ->prepareData()
                                          ->generateDoc();

        $this->setGeneratedFile($generatorCore);
        return $this;
    }

    /**
     * Отправка файла на почту
     * @return bool
     */
    public function sendToEmail() {
        $data = $this->getFileData()["email"];
        $file = $this->getGeneratedFile();
        $fileType = $this->fileType;
        if(!empty($file)){
            Mail::send($fileType::PATH, $data, function ($message) use ($data, $file, $fileType) {
                $message->to($data["emails"])
                        ->subject($data["subject"])
                        ->attachData($file, $data['title'].$fileType::TYPE);
            });
            foreach($data["emails"] as $email){
                SentMailLog::create([
                    'user_id' => auth()->id(),
                    'email' => $email,
                    'type' => $fileType::TYPE,
                    'additional' => ['filename' => $data['title'].$fileType::TYPE]
                ]);
            }
            return true;
        }
        return false;
    }

    /**
     * @return FilesTypesInterface
     */
    public function getFileType() : FilesTypesInterface {
        return $this->fileType;
    }

    /**
     * @return mixed
     */
    public function getFileData() {
        return $this->fileData;
    }

    /**
     * @param mixed $fileData
     * @return FileGenerator
     */
    public function setFileData($fileData) : FileGenerator {
        $this->fileData = $fileData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeneratedFile() {
        return $this->generatedFile;
    }

    /**
     * @param mixed $generatedFile
     */
    public function setGeneratedFile($generatedFile) : void {
        $this->generatedFile = $generatedFile;
    }

}
