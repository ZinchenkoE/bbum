<?php

namespace app\components;


use yii\base\UnknownPropertyException;
use yii\web\UploadedFile;

/**
 * Class File.
 * Using for save file, that obtained from uploaded files to server.
 * @package app\components\helpers
 */
class File
{
    const BASE_DIR = 'res/';

    private $_file;
    private $_path;

    /**
     * File constructor.
     * @param UploadedFile $uploadedFile
     */
    private function __construct(UploadedFile $uploadedFile)
    {
        $this->_file = $uploadedFile;
    }

    /**
     * Returns false, if file not found.
     * @param  string        $fileName    --> name of the uploaded file (obtained from the form).
     * @param  bool          $required    --> if file should be found by name ($fileName).
     * @return bool|static
     * @throws UnknownPropertyException
     */
    public static function loadFile(string $fileName, bool $required = false)
    {
        $uFile = UploadedFile::getInstancesByName($fileName);
        if (!$uFile && $required) {
            throw new UnknownPropertyException('Не удалось загрузить файл "' . $fileName . '"');
        }
        return $uFile ? new static($uFile[0]) : false;
    }

    /**
     * Save file to directory ($directoryName).
     * Set to $_path the location of saved file or '', if file was not saved.
     * @param  string    $directoryName   --> is name of the directory which will be made to save the file.
     * @param  string    $name            --> file name; default value is time.
     * @param  string    $type            --> file directory in res/; for sort file to necessary directory; 
     *                                     default value is uploaded  file type.
     * @param  string    $extension       --> file extension; default value is uploaded  file extension.
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function save(string $directoryName, string $name = '', string $type = '', string $extension = '')
    {
//        if (!$directoryName) {
//            throw new \InvalidArgumentException("Incorrect directory name ({$directoryName})");
//        }
        $delimiter   = strpos($this->_file->type, '/');
        $extension   = $extension ? $extension : substr($this->_file->type, $delimiter + 1);
        $type        = $type ? $type : substr($this->_file->type, 0, $delimiter);
        $name        = $name ? $name : microtime(true);
        $dir         = self::BASE_DIR . $type . '/' . ($directoryName ? $directoryName . '/' : "");
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->_path = $dir . $name . '.' . $extension;
        $this->_path = $this->_file->saveAs($this->_path) ? $this->_path : '';
        return $this;
    }

    /**
     * Returns saved file's path or false, if file is not saved.
     * @return string|bool
     */
    public function getPath()
    {
        return $this->_path && file_exists($this->_path)? $this->_path : false;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->_file;
    }
}