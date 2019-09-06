<?php

namespace App\Services\YandexFiles {

use GuzzleHttp\Client;
use App\Models\ListFilesYandex;
use App\Models\RevisionsYandex;
use App\Services\LocalFiles\LocalFiles;
use DB;

    class YandexFiles
    {
        

    public function __construct()
    {
        $this->client = new Client(
            ['base_uri' =>'https://cloud-api.yandex.net/v1/disk',
            'rpc_error'=>true,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => env('YANDEX_TOKEN', ''), 
                ],
            'timeout' => 300
            ]
        );
    }

        protected $client;

        //return  json files list
        public function getListAllFiles($offset=0){
        	$url ='https://cloud-api.yandex.net/v1/disk/resources/files';//Получить список всех файлов
	        $res = $this->client->request('GET', $url, [
	              'query' => ['limit'=>'3000',
	                          'fields'=>'items.md5, items.path',
	                          'media_type'=>'image',
	                          'offset'=>$offset
	                         ]
	        ]);
	        if ($res->getStatusCode() != 200) return false;
	        return $res->getBody();
        }

        //отправка файла
        public function uploadFile($foto){
            //создать все необходимые папки
           $path = $this->getPathName($foto);
           //полный путь к файлу на диске
           $file = $path.'/'.$foto->dataStringFileName;
           //dd($file);
           //ссылка для загрузки
           $link = $this->getUploadFileLink($file);
           //dd([$foto, $link]);
           if(!$link) return false;
           //выгрузка файла на диск
           return $this->uploadFileToLink($link, $foto->fileName);
        }

        //создаёт на диске указанную папку
        private function createPath($path){
            try{
                $url ='https://cloud-api.yandex.net/v1/disk/resources';//создать папку
                $res = $this->client->request('PUT', $url, [
                      'query' => ['path'=>$path]
                ]);
                return true;            
            }catch(\Throwable $e){
                return false;
            }
        }


        //формирует название директирии, где хранятся фотографии
        private function getPathName($foto){
            $path = env('FOTO_DIR', '');//'/test/foto';//эта часть уже должна быть создана
            $pathR = env('FOTO_DIR_REZ', '');//'/test/rez';// аварийная папка, эта часть уже должна быть создана
            //добавляем к полному пути год
            if(empty($foto->year)){ $path = $path.'/undefinedYear';}
            else {$path = $path.'/'.$foto->year;}
            $this->createPath($path);//создаём часть пути
            //добавляем к полному пути месяц
            if(empty($foto->month)){$path = $path.'/undefinedMonth';}
            else {$path = $path.'/'.$foto->month;}
            $this->createPath($path);//создаём оставшуюся часть пути
            //проверяем, имеется ли необходимая папка
            if($this->isDiskPath($path)) return $path;
            return $pathR;
        }
        //проверяет наличие на диске указанной папки
        private function isDiskPath($path){
            try{
                $url ='https://cloud-api.yandex.net/v1/disk/resources';//вернуть информацию о ресурсе
                $res = $this->client->request('GET', $url, [
                      'query' => ['path'=>$path]
                ]);
                if($res->getStatusCode() == 200) return true;
                return false;            
            }catch(\Throwable $e){
                return false;
            }
        }
        //Запрос URL для загрузки файла
        private function getUploadFileLink($file){
            try{
                $url ='https://cloud-api.yandex.net/v1/disk/resources/upload';//получить URL для загрузки файла
                $res = $this->client->request('GET', $url, [
                      'query' => ['path'=>$file]
                ]);
                if ($res->getStatusCode() != 200) return false;
                $respons = json_decode($res->getBody());
                return $respons->href;
                return true;            
            }catch(\Throwable $e){
                return false;
            }
        }
        //выгрузка файла на полученный URL 
        private function uploadFileToLink($link, $fileName){
           // dd([$link, $fileName]);
             try{
                $client = new Client(
                    ['base_uri' =>$link,
                    'rpc_error'=>true,
                        'headers' => [],
                    'timeout' => 300
                    ]
                );
                $fp = fopen($fileName,'r');
                $res = $this->client->request('PUT', $link, [
                      'body' => $fp
                ]);
                //fclose($fp);
                if ($res->getStatusCode() != 200) return false;
                return true;            
             }catch(\Throwable $e){
                //dd($e);
                 return false;
             }
        }


    }


}