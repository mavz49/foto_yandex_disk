<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class ListSuccessfulFile extends Model
    {
        //
        protected $table = 'list_successful_file';
        protected $fillable = ['revisions_local_id', 'md5'];


    }


}
