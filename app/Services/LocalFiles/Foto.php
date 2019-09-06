<?php

namespace App\Services\LocalFiles {

use App\Services\LocalFiles\LocalFiles;
//use App\Models\RevisionsYandex;
use DB;

    class Foto
    {
       
        public function __construct($fileName)
        {
        	$this->fileName = $this->homeParth . $fileName;
        	if(LocalFiles::isFileExists($this->fileName)){
                $this->setFileInfo();
                $this->setMonthFoto();
                $this->setYearFoto();
                $this->setDataString();
        		$this->isFotoExists=true;
        	}
        }

            private $homeParth='/';
            public $fileName;
            public $localFilesId;
            public $originalFileName;
            public $mimeType;
            public $fileType;
            public $fileMd5;
            public $isFotoExists=false;
            public $dateTimeDigitized;
            public $dateTimeOriginal;
            public $month;
            public $year;
            public $dataStringFileName;


        private function setMonthFoto(){
            if (empty($this->dateTimeDigitized))return false;
            $this->month = substr($this->dateTimeDigitized, 5, 2);
        }
        private function setYearFoto(){
            if (empty($this->dateTimeDigitized))return false;
            $this->year = substr($this->dateTimeDigitized, 0, 4);
        }
        private function setDataString(){
            if (!empty($this->dateTimeDigitized)){
            $this->dataStringFileName = str_replace([':', ' '] , ['-', '_'] ,$this->dateTimeDigitized);
            $this->dataStringFileName = $this->dataStringFileName.'_'.$this->originalFileName;
            }
            else{
                $this->dataStringFileName = $this->fileMd5.'_'.$this->originalFileName;
            }
        }
        private function setFileInfo(){
            $this->fileMd5 = md5_file($this->fileName); 
            try {
                $exif = exif_read_data($this->fileName, 0, true);
                $this->dateTimeOriginal = $exif['EXIF']['DateTimeOriginal'] ?? null;
                $this->dateTimeDigitized = $exif['EXIF']['DateTimeDigitized'] ?? null;
                $this->originalFileName = $exif['FILE']['FileName'] ?? null;
                $this->mimeType = $exif['FILE']['MimeType'] ?? null;
                $this->fileType = $exif['FILE']['FileType'] ?? null;
            }
            catch (\Exception $e){
                $arr = $pieces = explode(".", $this->fileName);
                $ext = array_pop($arr);
                $strExt = $ext ?? 'xxx';
                $this->originalFileName = 'filenonename.'.$strExt;
            } 
        }

    }

}
