<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class RevisionsLocal extends Model
    {
        //
        protected $table = 'revisions_local';
        protected $fillable = ['is_active'];

        //проверка на незаконченную ревизию
        public static function isActiveRevision(){
        	return self::where('is_active', 1)->count();
        }



    }



}