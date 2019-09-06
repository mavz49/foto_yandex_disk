<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;
    //use Laravel\Scout\Searchable;
    use DB;

    class ListFilesYandex extends Model
    {
        //
    	//use Searchable;
        
        protected $table = 'list_files_yandex';
        protected $fillable = ['part', 'name', 'md5'];

        //проверяет наличие дубликатов/уже загруженных файлов
        public static function isDobleExists($fileMd5){
            $yaPart = env('YANDEX_FOTO_DIR_FILTR', '');
            return self::where([['md5', $fileMd5], ['part', 'like', $yaPart.'%']])->count();
        }



    }
}