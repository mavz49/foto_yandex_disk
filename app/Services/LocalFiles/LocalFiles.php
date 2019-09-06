<?php

namespace App\Services\LocalFiles {

use Storage;
use App\Models\ListFilesLocal;
use App\Models\ListFilesYandex;
use App\Models\RevisionsLocal;
use App\Services\LocalFiles\Foto;
use App\Services\YandexFiles\YandexFiles;
use App\Jobs\UploadYandexFile;
use DB;

    class LocalFiles
    {
        

	    public function __construct()
	    {
	        $this->disk = Storage::disk('home');
	        $this->dirName = env('LOCAL_FOTO_DIR', '');
	    }

	    protected $dirName;
	    protected $disk;
	    //новый список ревизии
	    public function updateLocalDir(){
	    	$files = $this->disk->allFiles($this->dirName);
	        DB::beginTransaction();
	        try {
	        	DB::table('list_files_local')->delete();//удаляем старый список
	        	foreach ($files as $file) {
	        		$filename = ListFilesLocal::updateOrCreate(['name' => $file], ['name' => $file]);
	        	}
	        	//обнуляем старые ревизии
	        	RevisionsLocal::where('is_active', 1)->update(['is_active'=>0]);
	        	$revisionsLocal = new RevisionsLocal();
	        	$revisionsLocal->is_active = 1;
	        	$revisionsLocal->save();
	        }
	        catch (\Exception $e){
	            DB::rollBack();
	            throw $e;
	        }
	        DB::commit();
	    	dd($files);
	    }

	    //проверка существует ли данный файл
	    public static function isFileExists($fileName){
	    	return Storage::disk('home')->exists($fileName);
	    }
	    //список файлов для  анализа и отправки
	    public function addListFiles(){
	    	//$yandex = new YandexFiles;
	    	$limit = 100;
	    	$files = ListFilesLocal::limit($limit)->get();
	    	//кончились файлы в списке
	    	if(!count($files)){
	    		$ochered = DB::table('jobs')->count();
	    		//кончились задания в очереди
	    		if($ochered == 0){
		    		//обнуляем старые ревизии
		        	RevisionsLocal::where('is_active', 1)->update(['is_active'=>0]);
	    		}
	    		return true;
	    	}
	    	//TUDO if count()
	    	foreach ($files as $file) {
				$foto = new Foto($file->name);
				//файл отсутствует на диске
				if(!$foto->isFotoExists){
					$file->delete();
					continue;
				}
				//файл возможно уже загружен
	    		if(ListFilesYandex::isDobleExists($foto->fileMd5)){
					$file->delete();
					continue;
	    		}
	    		dispatch(new UploadYandexFile($foto));
	    		$file->delete();
	    	}

	    }


    }


}