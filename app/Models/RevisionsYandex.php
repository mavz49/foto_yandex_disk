<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class RevisionsYandex extends Model
    {
        //
        protected $table = 'revisions_yandex';
        protected $fillable = ['last_number', 'is_active'];

        const STATE_YANDEX_OLD    = 0;//работа с данными завершена
        const STATE_YANDEX_UPDATE = 1;//данные обновляются с yandex
        const STATE_YANDEX_UPLOAD = 2;//данные синхронизирутся с yandex

        //вернёт текущий объект  незаконченной ревизии. или вновь созданный
        public static function getActiveRevision(){
	    	$model = self::where('is_active', self::STATE_YANDEX_UPDATE)->first();
	    	if(empty($model)){
	    		$model = new RevisionsYandex();
	    		$model->is_active = self::STATE_YANDEX_UPDATE;
	    	}
	    	return $model;	
        }

        //проверка на незаконченную ревизию синхронизации
        public static function isActiveRevisionUpload(){
        	return self::where('is_active', self::STATE_YANDEX_UPLOAD)->count();
        }
        //проверка на незаконченный процесс получения списка удалённых файлов
        public static function isActiveRevisionUpdate(){
            return self::where('is_active', self::STATE_YANDEX_UPDATE)->count();
        }


    }


}
