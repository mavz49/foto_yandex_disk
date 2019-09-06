<?php

namespace App\Services {

use App\Models\ListFilesYandex;
use App\Models\RevisionsYandex;
use App\Models\RevisionsLocal;
use App\Services\LocalFiles\LocalFiles;
use App\Services\YandexFiles\YandexFiles;
use DB;

    class Revisions
    {
       
    public function __construct(LocalFiles $localFiles, YandexFiles $yandexFiles)
    {
    	$this->localFiles = $localFiles;
    	$this->yandexFiles = $yandexFiles;
    }

  
        protected $localFiles;
        protected $yandexFiles;
        //protected $revision;

        //запускается по рассписанию раз в неделю, если старая синхронизация закончилась - инициирует новую
        public function updateScheduleRevision(){
            //пока есть незавершённые файлы синхронизации - не обновляем
            if(RevisionsLocal::isActiveRevision())return true;
        	//инициируем новую ревизию
	        	DB::beginTransaction();
		        try {
                    //обнуляем старые ревизии, создаем новую
                    RevisionsYandex::whereIn('is_active', 
                        [RevisionsYandex::STATE_YANDEX_UPDATE  ,RevisionsYandex::STATE_YANDEX_UPLOAD])->
                        update(['is_active'=>RevisionsYandex::STATE_YANDEX_OLD]);
		    		$revision = new RevisionsYandex();
                    $revision->last_number = 0;
		    		$revision->is_active = RevisionsYandex::STATE_YANDEX_UPDATE;
                    $revision->save();
		    		DB::table('list_files_yandex')->delete();//удаляем старый список
		        }
		        catch (\Exception $e){
		            DB::rollBack();
		            throw $e;
		        }
		        DB::commit();
    	}
        //по частям заполняет 
        public function updateListFiles(){
        	//пока есть незавершённые файлы синхронизации - не обновляем
        	if(RevisionsLocal::isActiveRevision())return true;
        	if(RevisionsYandex::isActiveRevisionUpload())return true;
        	//обновление списка файлов должно инициироватся во внешнем методе
        	if(!RevisionsYandex::isActiveRevisionUpdate())return true;
        	//получаем незаконченную ревизию полного списка файлов яндекс-диска
        	$revision = RevisionsYandex::where('is_active', RevisionsYandex::STATE_YANDEX_UPDATE)->first();
        	$filesJson  = $this->yandexFiles->getListAllFiles($revision->last_number);
        	if(!$filesJson) return false;
        	$files = json_decode($filesJson);
        	//нет новых файлов на сервере завершаем ревизию, начинаем синхронизацию
        	if(empty($files->items)){
				$revision->is_active = RevisionsYandex::STATE_YANDEX_UPLOAD;
				$revision->save();
				$localFiles = new LocalFiles;
				$localFiles->updateLocalDir();
				return true;
        	}
        	$revision->last_number++;
        	DB::beginTransaction();
	        try {
	        	foreach ($files->items as $file) {
	        		ListFilesYandex::updateOrCreate(['md5' => $file->md5],
	        					 ['part' => $file->path,
								  'md5' => $file->md5]
	        					);
	        		 $revision->last_number++;
	        	}
	        	$revision->save();
	        }
	        catch (\Exception $e){
	            DB::rollBack();
	            throw $e;
	        }
	        DB::commit();
        }

    }


}